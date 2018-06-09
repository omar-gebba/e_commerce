            <?php

session_start();
$pageTitle = 'Categories';

if (isset($_SESSION['user'])) {
   include 'init.php';

   $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
if ($do == 'Manage') {
// start manage page 
    $sort = 'ASC';
    $sort_array = array('ASC', 'DESC');
    if (isset($_GET['Sort']) && in_array($_GET['Sort'], $sort_array)) {
        $sort = $_GET['Sort'];
    }
    $stmt = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ordering $sort");
        $stmt->execute();
        $cats = $stmt->fetchAll();
?>
        <h1 class='text-center text-primary'> Manage Your Categories </h1>
        <div class='container'>
            <div class='panel panel-info'>
                <div class='panel-heading'>
                    Manage Categories
                    <div class='ordering pull-right'>
                        Ordering: 
                        <a class='<?php if($sort == 'ASC') {echo "active"; }  ?>' href='<?php echo "?Sort=ASC"; ?>'> ASC </a>|
                        <a class='<?php if($sort == 'DESC') {echo "active"; }  ?>' href='<?php echo "?Sort=DESC"; ?>'> DESC </a> 
                    </div>
                </div>
                <div class='panel-body cat-panel'>
                    <?php
                        foreach($cats as $cat) {
                            echo "<div class='cats'>";
                                echo "<div class='btns-hidden'>";
                                    echo '<a href="?do=Edit&ID=' . $cat['ID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                                    echo '<a href="?do=Delete&ID=' . $cat['ID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
                                echo "</div>";
                                echo  "<h3>" . ucfirst($cat['name'])  . "</h3>";
                                if ($cat['describtion'] == ''){
                                    echo "<div>This Category has no describtion. </div>";
                                }else{echo $cat['describtion'] . "<br>";}
                                if ($cat['visibility'] == 1) {
                                    echo "<span class='vis'> Hidden</span>";
                                }
                                if ($cat['allow_comments'] == 1) {
                                    echo "<span class='com'> Allo Comments</span>";
                                }
                                if ($cat['allow_ads'] == 1) {
                                    echo "<span class='ads'>Ads Disabled</span>";
                                }

                             $where = "WHERE parent = " . $cat['ID'];
                             $order = "order by ID desc";
                             $child = getAll("*", "categories", $where, " ", $order);
                             if (! empty($child)) {
                                echo "<h3 class='child-h3'>Child categories</h5>";
                                foreach ($child as $c) {
                                    
                                    echo "<ul class='list-unstyled'>";
                                        echo "<li><a href='categories.php?do=Edit&ID=" .  $c['ID']  . "'> - " . ucfirst($c['name'])  . "</a>
                                              <a class='confirm child-delete' href='categories.php?do=Delete&ID=" . $c['ID']  . "'>Delete</a></li>";
                                    echo "</ul>";
                                }
                            }
                            echo "</div>";
                            echo "<hr>";
                        }
                    ?>
                </div>
            </div>
            <a href="categories.php?do=Add" class='btn btn-primary'><i class='fa fa-plus'></i>Add New Category </a>
        </div>
<?php
// start add page 
}elseif ($do == 'Add') { ?>

<!-- form of ADD pge -->
        <div class='container text-center'>
        <h1 class='text-primary'> Add New Category </h1>
        <form class='form-horizontal' action="categories.php?do=Insert" method="POST" >
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Name </label> 
                <input class='col-sm-9' type='text' name='name' placeholder='Type Category Name' required='required'>
            </div>
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Describtion </label> 
                <input class='col-sm-9' type='text' name='describtion' placeholder='Describe Category'>
            </div>
                    <!-- category parent  -->
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'>Parent?</label>
                <select class='col-sm-9' name='parent'>
                    <option value='0'>None</option>
                    <?php 
                    $count = countCats();
                    foreach ($count as $cat) {
                        echo "<option value='" . $cat['ID'] . "'>" . $cat['name']  . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Ordering </label>
                <input class='col-sm-9' type='text' name='ordering' placeholder='Number To Arrange Categories'required='required'>
            </div>


            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Visibile </label> 
                <div class='col-sm-1'>
                    <div>
                        <input id='vis-yes' type='radio' name='visibility' value='0' checked />
                        <label for='vis-yes'>Yes </label>
                    </div>        
                    <div>
                        <input id='vis-no' type='radio' name='visibility' value='1' />
                        <label for='vis-no'>No </label>
                    </div>
                </div>
            </div>
            
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Comment </label> 
                <div class='col-sm-1'>
                    <div>
                        <input id='com-yes' type='radio' name='commenting' value='0' checked />
                        <label for='com-yes'>Yes </label>
                    </div>        
                    <div>
                        <input id='com-no' type='radio' name='commenting' value='1' />
                        <label for='com-no'>No </label>
                    </div>
                </div>
            </div>

            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Advertise </label> 
                <div class='col-sm-1'>
                    <div>
                        <input id='ads-yes' type='radio' name='ads' value='0' checked />
                        <label for='ads-yes'>Yes </label>
                    </div>        
                    <div>
                        <input id='ads-no' type='radio' name='ads' value='1' />
                        <label for='ads-no'>No </label>
                    </div>
                </div>
            </div>
            <div class='form-group'>
                <input class='btn btn-primary col-sm-offset-1 col-md-4 col-xs-12 input-lg marg' type='submit' value='Add' >
            </div>
        </form>
        
    </div> <!-- end of container div  of form page (add page)-->  
    <?php
    }elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name           = $_POST['name'];
            $describtion    = $_POST['describtion'];
            $parent         = $_POST['parent'];
            $ordering       = $_POST['ordering'];
            $visibility     = $_POST['visibility'];
            $allow_comments = $_POST['commenting'];
            $allow_ads      = $_POST['ads'];

            echo "<div class='container'>";

            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = '<div class="alert alert-danger text-center">name must be include</div>';
            }
            if (empty($ordering)) {
                $formErrors[] = '<div class="alert alert-danger text-center">sorry ordering must be include</div>';
            }


            foreach($formErrors as $errors)  {
                $message =  $errors;
                $url = 'back';
                redirect($message, $url = null, $time = 3); 
                
            }

            if (empty($formErrors)) {
                $selected = 'name';
                $table    = 'categories';
                $value    = $name;
                $check    = checkvalue($selected, $table, $value);
                if ($check == 1) {
                    $message = "<div class='alert alert-danger text-center'> doublicate entery no data inserted</div>"; 
                    redirect($message, "back", $time = 3);
                }else {
                    $stmt = $con->prepare("INSERT INTO categories(name, describtion, parent,
                                                                  ordering, visibility,
                                                                  allow_comments, allow_ads)
                                    VALUES(?, ?, ?, ?, ?, ?, ?)");
                                    $stmt->execute(array($name, $describtion, $parent,
                                                         $ordering, $visibility,
                                                         $allow_comments, $allow_ads));
                                    $count = $stmt->rowCount();

                                    $message = "<div class='alert alert-success'>Number Of Data Inserted Is " . $count . " </div>";
                                    redirect($message,'back');
                }
            }
            echo "</div>";
 
        } else { // if request method != post     
            $message = "<div class='alert alert-danger text-center>'> You should come by legal post method</div>";
            $url = "back";
            redirect($message, $url = null, $time = 3); 
        }   
        echo "</div>";

    }elseif ($do == 'Edit') {
        $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
        $stmt->execute(array($ID));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();
        
?>
        <h1 class='text-primary text-center'> Edit A Category </h1>
        <div class='container text-center'>
            <form class='form-horizontal' action='categories.php?do=Update' method='POST'>
                         <!-- category name -->
                <div class='form-group'>
                    <label class='col-md-2 col-xs-12 control-label'>Name</label>
                    <input class='col-md-9 col-xs-12' name='name' type='text' placeholder='Type Category Name' value="<?php echo $cat['name']; ?>" required='required' />
                </div>
                        <!-- category desc -->
                <div class='form-group'>
                    <label class='control-label col-md-2 col-xs-12 control-label'>Description </label>
                    <input class='col-md-9 col-xs-12' name='description' type='text' placeholder='Type Description' value="<?php echo $cat['describtion']; ?>">
                </div>
                        <!-- category order -->
                <div class='form-group'>
                    <label class='control-label col-md-2 col-xs-12'> Ordering</label>
                    <input class='col-md-9 col-xs-12' name='ordering' type='text' placeholder='Type Ordering' value="<?php echo $cat['ordering']; ?>">
                </div>
                  <!-- category parent  -->
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'>Parent?</label>
                <select class='col-sm-9' name='parent'>
                    <option value='0'>None</option>
                    <?php 
                    $count = countCats();
                    foreach ($count as $c) {
                        echo "<option value='" . $c['ID'] . "'";
                        if($c['ID'] == $cat['parent']) { echo "selected";} 
                        echo ">" . $c['name']  . "</option>";
                    }
                    ?>
                </select>
            </div>
                        <!-- category visibility -->
                <div class='form-group'>
                    <label class='control-label col-md-2 col-xs-12'> Visibility</label>
                    <div class='col-xs-1'>
                        <div>
                            <input id='vis-yes' name='visibility' type='radio' value='0' <?php if ($cat['visibility']== 0) { echo"checked"; }  ?> />
                            <label for='vis-yes'> Yes </label>
                        </div>
                        <div>
                            <input id='vis-no' name='visibility' type='radio' value='1' <?php if ($cat['visibility']== 1) { echo"checked"; }  ?> />
                            <label for='vis-no'> NO </label>
                        </div>
                    </div>
                </div>
                            <!-- category comment -->
                <div class='form-group'>
                    <label class='control-label col-md-2 col-xs-12'> Allow Comments</label>
                    <div class='col-xs-1'>
                        <div>
                            <input id='com-yes' name='com' type='radio' value='0' <?php if ($cat['allow_comments']== 0) { echo"checked"; }  ?> />
                            <label for='com-yes'> Yes </label>
                        </div>
                        <div>
                            <input id='com-no' name='com' type='radio' value='1' <?php if ($cat['allow_comments']== 1) { echo"checked"; }  ?> />
                            <label for='com-no'> NO </label>
                        </div>
                    </div>
                </div>
                                <!-- category adds -->
                <div class='form-group'>
                    <label class='control-label col-md-2 col-xs-12'> Allow Ads</label>
                    <div class='col-xs-1'>
                        <div>
                            <input id='ads-yes' name='ads' type='radio' value='0' <?php if ($cat['allow_ads']== 0) { echo"checked"; }  ?> />
                            <label for='ads-yes'> Yes </label>
                        </div>
                        <div>
                            <input id='ads-no' name='ads' type='radio' value='1' <?php if ($cat['allow_ads']== 1) { echo"checked"; }  ?> />
                            <label for='ads-no'> NO </label>
                        </div>
                    </div>
                </div>

                <div class='form-group'>
                    <input class='btn btn-primary col-sm-offset-4 col-md-4 col-xs-12 input-lg marg' type='submit' value='Edit' />
                </div>
                <input type='hidden' name='ID' value="<?php echo $cat['ID']; ?>" >
            </form>
            
        </div>
<?php
    }elseif ($do == 'Update') {
        echo '<h1 class="text-center">Category Update</h1>';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $ID             = $_POST['ID'];
            $name           = $_POST['name'];
            $describtion    = $_POST['describtion'];
            $parent         = $_POST['parent'];
            $ordering       = $_POST['ordering'];
            $visibility     = $_POST['visibility'];
            $allow_comments = $_POST['commenting'];
            $allow_ads      = $_POST['ads'];

            echo "<div class='container'>";

            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = '<div class="alert alert-danger text-center">name must be include</div>';
            }

            foreach($formErrors as $errors)  {
                echo $errors; 
                
            }

            if (empty($formErrors)) {
                $selected = 'name';
                $table    = 'categories';
                $value    = $name;
                $check    = checkvalue($selected, $table, $value);
                if ($check == 1) {
                    $message = "<div class='alert alert-danger text-center'> doublicate entery no data inserted</div>"; 
                    redirect($message, "back", $time = 3);
                }else {
                    $stmt = $con->prepare("UPDATE categories SET name = ?, describtion = ?, parent = ?, ordering = ?, visibility = ?, allow_comments = ?, allow_ads = ? WHERE ID = ?");
                    $stmt->execute(array($name, $describtion, $parent, $ordering, $visibility, $allow_comments, $allow_ads, $ID));
                    $count = $stmt->rowCount();

                    $message = "<div class='alert alert-success'>Number Of Data Updated Is " . $count . " </div>";
                    redirect($message);
                }
            }
    
        } else { // if request method != post     
            $message = "<div class='alert alert-danger text-center>'> You should come by legal post method</div>";
            $url = "back";
            redirect($message, $url = null, $time = 3); 
        }   
        echo "</div>";
            
    }elseif ($do == 'Delete') {
        // check if  there is ID  or not ant fetch ID   
        $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
        
        //check if this ID exists in the table or not (ensuring step... double check)
        $selected = 'ID';
        $table = 'categories';
        $value = $ID;
        $check = checkvalue($selected, $table, $value);
        if ($check == 1){
            // send the qury
            $stmt = $con->prepare('DELETE FROM categories WHERE ID = ?');
            $stmt->execute(array($ID));
            $count = $stmt->rowCount();
            // check if the item deletd and show meassage.
            echo $count;
            if($count > 0) {
                $meassage = "<div class='alert alert-danger text-center'> "  . $count . " Category Deleted </div>";
                $url = 'back';
                redirect($meassage, $url);
            }
        } else {
            $message = "<div class='alert alert-danger'> This Category Is Not Exist</div>";
            $url = 'back';
            redirect($message, $url = null, $time = 3);
        }
    } else {
        echo 'there is no $do';
    }

   include $tpl . "footer.php";
} else {
    header('location:index.php');
    exit();
}
