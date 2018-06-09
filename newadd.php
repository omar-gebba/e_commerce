<?php
session_start();


include "init.php";
$pageTitle = "New Add";
    
if (isset($_SESSION['frontUser'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $itemError = array();
        
        $name        = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc        = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price       = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country     = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status      = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category    = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags        = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
        // start to fetch image data
        $avatar = $_FILES['image'];
        $allowedExtensions = array('jpg', 'png', 'jpeg', 'gif');
        $temp1 = explode('.', $avatar['name']);
        $avatarExtension = strtolower(end($temp1));
        $avatarSize = $avatar['size'];
        // end image data
        if(strlen($name) < 4) {
            $itemError[] = "name must be not less than 4 ch.";
        }
        if(strlen($desc) < 10) {
            $itemError[] = "description must be not less than 10 ch.";
        }
        if(empty($price)) {
            $itemError[] = "please write the price";
        }
        if(empty($country)) {
            $itemError[] = "country must be not empty";
        }
        if(empty($status)) {
            $itemError[] = "status must be not empty";
        }
        if(empty($category)) {
            $itemError[] = "category must be not empty";
        }
        // ERRORS OF IMAGE
        
        if (! empty($avatar) && ! in_array($avatarExtension, $allowedExtensions)) {
            $itemError[] = "this file not allowed";
        }
        if ($avatarSize > (5*1024*1024)) {
            $itemError[] = "Image size must be <strong>5MB OR less</strong>";
        }
       
        if (empty($itemError)){
            $avatarName = rand(0, 10000000) . $_FILES['image']['name'];
            $direPath  = 'admin/uploads/items/';
            $avatarPath = $direPath . $avatarName;
            if (is_dir($direPath) && is_writable($direPath)) {
               move_uploaded_file($avatar['tmp_name'], $avatarPath);
            } else{
                if (!is_dir($direPath)) {
                    $itemError[] = 'the path <b>' . __DIR__ . $direPath . '</b> is not directroy';
                }
                elseif (! is_writable($direPath)){
                    $itemError[] = 'this directory is not writable';
                }
            }
            $stmt = $con->prepare("INSERT INTO items(name, description, price, add_date, country_made,
                                                        status, cat_ID, member_ID, tags, item_img)
                                            VALUES(?, ?, ?, NOW(), ?, ?, ? , ?, ?, ?)");
            $stmt->execute(array($name, $desc, $price, $country, $status,
                                 $category, $_SESSION['uID'], $tags, $avatarPath));
            $count = $stmt->rowCount();
            if ($count > 0) {
                $succMessage = "<div class='alter alert-success text-center'> The data inserted</div>";
            }
        }
    }
  ?>
    <div class='container text-center'>
        <h1>Add New Advertise</h1>
    </div>
    <div class='adding'>
        <div class='container'>
            <div class='panel panel-primary'>
                <div class='panel-heading'> New Add </div>
                <div class='panel-body'>
                <!-- strat the form div -->
                    <div class='col-xs-9'>
                        <div class='row'>
                            <form class='form-horizontal' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='POST' enctype='multipart/form-data' >
                                 <!-- input of name -->
                                <div class='form-group'>
                                    <label class='col-xs-3 control-label'> Item Name</label>
                                    <input 
                                        type='text' 
                                        class='col-xs-8 live' 
                                        name='name'
                                        placeholder='type item name' 
                                        data-class='.live-name'
                                        required
                                    />
                                </div> 
                                <!-- input of description -->
                                <div class='form-group'>
                                    <label class='col-xs-3 control-label'> Description</label>
                                    <input 
                                        type='text' 
                                        class='col-xs-8 live' 
                                        name='description'
                                        placeholder='type a description'
                                        data-class='.live-desc'
                                        required
                                    />
                                </div> 
                                <!-- input of price -->
                                <div class='form-group'>
                                    <label class='col-xs-3 control-label'> Price</label>
                                    <input 
                                        type='text' 
                                        class='col-xs-8 live' 
                                        maxlength='8'
                                        name='price'
                                        placeholder='type a price' 
                                        data-class='.live-price'
                                        required
                                    />
                                </div> 
                                <!-- input of country  -->
                                <div class='form-group'>
                                    <label class='col-xs-3 control-label'> Country Of Made</label>
                                    <input 
                                        type='text' 
                                        class='col-xs-8' 
                                        name='country'
                                        required
                                    />
                                </div> 
                                <!-- select box of status -->
                                <div class='form-group'> 
                                    <label class='control-label col-xs-3'>Status </label>
                                    <select class='col-xs-8' name='status'> 
                                        <option value='0'>....</option>
                                        <option value='1'>New</option>
                                        <option value='2'>Like New</option>
                                        <option value='3'>Used</option>
                                        <option value='4'>Very Old</option>
                                    </select> 
                                </div> 
                                <!-- select box of categories -->
                                <div class='form-group'> 
                                    <label class='control-label col-xs-3'>Category </label>
                                    <select class='col-xs-8' name='category'> 
                                    <option value='0'>....</option>
                                        <?php
                                        $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ");
                                        $stmt2->execute();
                                        $cats = $stmt2->fetchAll();
                                        foreach ($cats as $cat) {
                                            echo "<option value='" . $cat['ID']  . "'>" . $cat['name'] . "</option>";
                                            
                                            $where = 'WHERE parent =' . $cat['ID'];
                                            $stmt3 = $con->prepare("SELECT * FROM categories $where");
                                            $stmt3->execute();
                                            $child = $stmt3->fetchAll();
                                            foreach($child as $c) {
                                                echo "<option value='" . $cat['ID']  . "'>--" . $c['name'] . "</option>";
                                                
                                            }
                                        }
                                        ?>
                                    </select> 
                                </div> 
                                            <!-- input of image-->
                                <div class='form-group'>
                                    <label class='col-xs-3 control-label'> Choose Image</label>
                                    <input 
                                        type='file' 
                                        class='col-xs-8 live img' 
                                        name='image'
                                        required
                                    />
                                </div> 
                                            <!--input of tags -->
                                <div class='form-group'>
                                    <label class='col-sm-3 control-label'>Tags</label>
                                    <input 
                                        class='col-sm-8' 
                                        type='txt' name='tags' 
                                        placeholder='Type a tags sperated by [,]'
                                    />
                                </div>
                                <input class='btn btn-primary col-xs-offset-5  col-xs-2' name='login' type='submit'  value='Add Item' />
                            </form>
                        </div>
                        <!-- start of form errors -->

                    <div class='item-error'> 
                        <?php
                        if (! empty($itemError)) {

                            foreach($itemError as $error){
                                echo "<div class='alert alert-danger text-center'>" . $error  . "</div>";
                            }
                        }
                        if (isset($succMessage)) {
                            echo $succMessage;
                        }
                        ?>
                    </div> 
                    <!-- end of form errors -->

                    </div>
                    <!-- end of form div  -->
                    
                    <!-- start the live review div -->
                    <div class='thumbnail col-xs-3 live-preview text-center'>
                        <img class='img-responsive live' src='house.png' />
                        <span class='price'>
                            $<span class='live-price'></span>
                        </span>
                        <h3 class='live-name'>item name</h3>
                        <p class='live-desc'>description of item</p> 
                    </div> 
                    <!-- end of live review div -->
                    
                </div>
            </div>
        </div>
    </div
    <?php
}else {
    header('location: login.php');
}
include $tpl . "footer.php";
?>
