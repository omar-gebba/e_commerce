<?php

    session_start();
    $pageTitle = 'Categories';

    if (isset($_SESSION['user'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
        
        $stmt = $con->prepare("SELECT 
                                    items.*, categories.name 
                               AS 
                                    cat_name, users.username 
                              FROM 
                                    items 
                              INNER JOIN 
                                    categories 
                              ON 
                                    categories.ID = items.cat_ID 
                              INNER JOIN 
                                    users 
                              ON 
                                    users.userID = items.member_ID");
        $stmt->execute();
        $items = $stmt->fetchall();
        ?>
        <div class='container text-center'>
            <h1 class='text-center text-success'>Manage Items</h1>
            <div class='table-responsive'>
                <table class='table table-bordered'>
                    <tr>
                        <td> #ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Date </td>
                        <td>Country Of Made</td>
                        <td>User Name</td>
                        <td>Category Name</td>
                        <td> Control </td>
                    </tr>
                    <?php

                        foreach ($items as $item) {
                            echo "<tr>";
                                echo "<td>" . $item['item_ID'] . "</td>";
                                echo "<td>" . $item['name'] . "</td>";
                                echo "<td>" . $item['description'] . "</td>";
                                echo "<td>" . $item['price'] . "</td>";
                                echo "<td>" . $item['add_date'] . "</td>";
                                echo "<td>" . $item['country_made'] . "</td>";
                                echo "<td>" . $item['username'] . "</td>";
                                echo "<td>" . $item['cat_name'] . "</td>";
                                echo "<td> <a href='items.php?do=Edit&id=" . $item['item_ID'] . "' class='btn btn-success'> Edit</a>
                                        <a href='items.php?do=Delete&id=" . $item['item_ID'] . "' class='btn btn-danger confirm'>Delete</a>";
                                if ($item['aprove'] == 0) {
                                   echo "<a href='items.php?do=Aprove&page=aprove&id=" . $item['item_ID'] . "' class='btn btn-primary confirm'><i class='fa fa-check'></i> Aprove</a>";
                                }
                        }    
                   ?>   
                </table>
            </div>
            <a href='items.php?do=Add' class='btn btn-primary'><i class="fa fa-plus" aria-hidden="true"></i>  Add New Item</a>
        </div>
       <?php 

    } elseif ($do == 'Add') { ?>
    <!-- form of ADD pge -->
    <div class='container text-center'>
        <h1 class='text-primary'> Add New Item </h1>
        <form class='form-horizontal' action="items.php?do=Insert" method="POST">
                    <!-- item name -->
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Name </label> 
                <input 
                    class='col-sm-9' 
                    type='text' 
                    name='name' 
                    placeholder='Type Item Name'
                    required
                    >
            </div>
                    <!-- item desc -->
            <div class='form-group'>
            <label class='col-sm-2 col-xs-12 control-label'> Description </label> 
            <input 
                class='col-sm-9' 
                type='text' 
                name='description' 
                placeholder='Type Description'
                required
                >
            </div>
                    <!-- item price -->
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Price </label> 
                <input 
                    class='col-sm-9' 
                    type='text' 
                    name='price' 
                    placeholder='Type The Price'
                    required
                    >
            </div>           
                    <!-- country of made -->
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> country </label> 
                    <input 
                        class='col-sm-9' 
                        type='text' 
                        name='country' 
                        placeholder='Country Of Made'
                        required
                       >
            </div>
                    <!-- item status -->
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Status </label> 
                <select class='col-sm-9' name="status" required> 
                    <option value='0'>....</option>
                    <option value='1'>New</option>
                    <option value='2'>Like New</option>
                    <option value='3'>Used</option>
                    <option value='4'>Very Old</option>
                </select>   
            </div>
                    <!-- member -->
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Member </label> 
                <select class='col-sm-9' name="member" required> 
                    <option value='0'>....</option>
                    <?php
                    $stmt = $con->prepare("SELECT * FROM users");
                    $stmt->execute();
                    $users = $stmt->fetchAll();
                    foreach ($users as $user) {
                        echo "<option value='" . $user['userID']  . "'> " . $user['username']  . "</option>";
                    }
                    ?> 
                </select>   
            </div>
                        <!-- category -->
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Category </label> 
                <select class='col-sm-9' name="category"> 
                    <option value='0'>....</option>
                    <?php            
                    $cats = getAll("*", "categories", "WHERE parent = 0", " ", " ");
                    foreach ($cats as $cat) {
                        echo "<option value='" . $cat['ID']  . "'> " . $cat['name']  . "</option>";

                        $where = "WHERE parent = " . $cat['ID'];
                        $childCats = getAll("*", "categories", $where, " ", " ");
                        foreach ($childCats as $c) {
                            echo "<option value='" . $c['ID']  . "'> -- " . $c['name']  . "</option>";
                        }
                    }
                    ?>
                </select>   
            </div>
                     <!-- tags -->
            <div class='form-group'>
                <label class='col-sm-2 control-label'>Tags</label>
                <input class='col-sm-9' type='txt' name='tags' placeholder='Type a tags sperated by [,]'>
            </div>
                    <!-- submit button -->
            <div class='form-group'>
                <input class='btn btn-primary col-sm-offset-2 col-md-2 col-xs-12 marg'
                 type='submit'
                value='Add'>
            </div>
        </form>
        
    </div> <!-- end of container div  of form page (add page)-->
<?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<div class='container text-center'>";
            echo "<h1>  Adding New Item </h1>";

            $name        = $_POST['name'];
            $description = $_POST['description'];
            $price       = $_POST['price'];
            $country     = $_POST['country'];
            $status      = $_POST['status'];
            $member      = $_POST['member'];
            $category    = $_POST['category'];
            $tags        = $_POST['tags'];
            
        
            $formerrors = array();
            if (empty($name)) {
                $formerrors[] = 'Name is reqiered';
            }
            if (empty($description)) {
                $formerrors[] = 'Description is reqiered';
            }
            if (empty($price)) {
                $formerrors[] = 'Price Is Required';
            }
            if (empty($country)) {
                $formerrors[] = 'Country is reqiered';
            }
            if ($status === 0) {
                $formerrors[] = 'you must choose the status';
            }
            if ($member === 0) {
                $formerrors[] = 'you must choose the member';
            }
            if ($category === 0) {
                $formerrors[] = 'you must choose the category';
            }

            foreach ($formerrors as $errors) {
                
                $message = "<div class='alert alert-danger'>" . $errors . "</div>";
                
                redirect($message, $url = null, $time = 3);
              }

            if (empty($formerrors)) {
               
                $stmt = $con->prepare("INSERT INTO items(name, description, price, country_made, status, add_date, cat_ID, member_ID, tags, aprove)
                                       VALUES(?, ?, ?, ?, ?, now(), ?, ?, ?, 1)");

                $stmt->execute(array($name, $description, $price, $country, $status, $category, $member, $tags));
                $count = $stmt->rowCount();

                $message = "<div class='alert alert-success'>Number Of Data Inserted Is " . $count . " </div>";
                redirect($message,'back');

            } 
            
        } else { 
            $message = "<div class='alert alert-danger'> sorry you can\'t browse this page</div>";
            $url ='back';
            redirect($message, $url, 6);
        }
        echo '</div>';

} elseif ($do == 'Edit') {
    $ID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
    $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ? limit 1");
    $stmt->execute(array($ID));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) {?>
         <!-- form of Edit page -->
    <div class='container text-center'>
        <h1 class='text-primary'> Edit An Item </h1>
        <form class='form-horizontal' action="items.php?do=Update" method="POST">
            
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Name </label> 
                <input 
                    class='col-sm-9' 
                    type='text' 
                    name='name' 
                    placeholder='Type Item Name'
                    value='<?php echo $row['name']; ?>' >
            </div>
            <div class='form-group'>
            <label class='col-sm-2 col-xs-12 control-label'> Description </label> 
            <input 
                class='col-sm-9' 
                type='text' 
                name='description' 
                placeholder='Type Description'
                value='<?php echo $row['description']; ?>'
                >
            </div>
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Price </label> 
                <input 
                    class='col-sm-9' 
                    type='text' 
                    name='price' 
                    placeholder='Type The Price'
                    value='<?php echo $row['price']; ?>'
                    >
            </div>           
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> country </label> 
                    <input 
                        class='col-sm-9' 
                        type='text' 
                        name='country' 
                        placeholder='Country Of Made'
                        value='<?php echo $row['country_made']; ?>'
                       >
            </div>
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Status </label> 
                <select class='col-sm-9' name="status"> 
                    
                    <option value='1' <?php if ($row['status'] == 1) { echo 'selected'; }  ?>>New</option>
                    <option value='2' <?php if ($row['status'] == 2) { echo 'selected'; }  ?>>Like New</option>
                    <option value='3' <?php if ($row['status'] == 3) { echo 'selected'; }  ?>>Used</option>
                    <option value='4' <?php if ($row['status'] == 4) { echo 'selected'; }  ?>>Very Old</option>
                </select>   
            </div>
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Member </label> 
                <select class='col-sm-9' name="member"> 
                    
                    <?php
                    $stmt = $con->prepare("SELECT * FROM users");
                    $stmt->execute();
                    $users = $stmt->fetchAll();
                    foreach ($users as $user) {
                        echo "<option value='" . $user['userID']  . "'";  if ($row['member_ID'] == $user['userID']) { echo 'selected'; }  echo "> " . $user['username']  . "</option>";
                    }
                    ?> 
                </select>   
            </div>
            <div class='form-group'>
                <label class='col-sm-2 col-xs-12 control-label'> Category </label> 
                <select class='col-sm-9' name="category"> 
                    
                <?php
                $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ");
                $stmt2->execute();
                $cats = $stmt2->fetchAll();
                foreach ($cats as $cat) {
                    echo "<option value='" . $cat['ID']  . "'"; if ($row['cat_ID'] == $cat['ID']) { echo "selected"; }
                    echo ">";
                    echo $cat['name'];
                    echo  "</option>";
                    $where = 'WHERE parent =' . $cat['ID'];
                    $stmt3 = $con->prepare("SELECT * FROM categories $where");
                    $stmt3->execute();
                    $child = $stmt3->fetchAll();
                    foreach($child as $c) {
                        echo "<option value='" . $c['ID']  . "'"; if ($row['cat_ID'] == $c['ID']) { echo "selected"; }
                        echo ">";
                        echo "--" . $c['name'];
                        echo  "</option>";
                    }
                }
                    ?>
                </select>   
            </div>
                    <!-- tags input -->
            <div class='form-group'>
                <label class='col-sm-2 control-label'>Tags</label>
                <input 
                    class='col-sm-9' 
                    type='txt' name='tags' 
                    value='<?php echo $row['tags']; ?>'  
                    placeholder='Type a tags sperated by [,]'
                />
            </div>
            <div class='form-group'>
                <input class='btn btn-primary col-sm-offset-2 col-md-2 col-xs-12 marg'
                 type='submit'
                value='Update'>
            </div>
           <input type='hidden' name='ID' value='<?php echo $ID; ?>' >   
        </form>
        <?php
        $stmt = $con->prepare("SELECT 
                                    comments.*, users.username 
                              FROM 
                                    comments 
                              
                              INNER JOIN 
                                    users 
                              ON 
                                    users.userID = comments.user_ID 
                              WHERE
                                    item_ID = $ID ");
        $stmt->execute();
        $comms = $stmt->fetchall();
        ?>
        <div class='container text-center'>
            <h1 class='text-center text-success'>Manage [ <?php echo $row['name']; ?> ] Comments</h1>
            <div class='table-responsive'>
                <table class='table table-bordered'>
                    <tr>
                        <td>The Comment</td>

                        <td>User Name</td>
                        <td>Date </td>
                        <td> Control </td>
                    </tr>
                    <?php

                        foreach ($comms as $comm) {
                            echo "<tr>";
                                echo "<td>" . $comm['comment'] . "</td>";

                                echo "<td>" . $comm['username'] . "</td>";
                                echo "<td>" . $comm['add_date'] . "</td>";
                                echo "<td> <a href='comments.php?do=Edit&id=" . $comm['comID'] . "' class='btn btn-success'> Edit</a>
                                        <a href='comments.php?do=Delete&id=" . $comm['comID'] . "' class='btn btn-danger confirm'>Delete</a>";
                                if ($comm['status'] == 0) {
                                   echo "<a href='comments.php?do=Aprove&id=" . $comm['comID'] . "' class='btn btn-primary confirm'><i class='fa fa-check'></i> Aprove</a>";
                                }
                        }    
                   ?>   
                </table>
       
        
    </div> <!-- end of container div  of form page (Edit page)-->
    <?php
    }
    
} elseif ($do == 'Update') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "<div class='container text-center'>";
            echo "<h1 class='text-primary'> Update Page</h1>";
        $ID          = $_POST['ID'];
        $name        = $_POST['name'];
        $description = $_POST['description'];
        $price       = $_POST['price'];
        $country     = $_POST['country'];
        $status      = $_POST['status'];
        $member      = $_POST['member'];
        $category    = $_POST['category'];
        $tags        = $_POST['tags'];

        $formerrors = array();

        if (empty($name)) {
            $formerrors[] = 'Name is reqiered';
        }
        if (empty($description)) {
            $formerrors[] = 'Description is reqiered';
        }
        if (empty($price)) {
            $formerrors[] = 'Price Is Required';
        }
        if (empty($country)) {
            $formerrors[] = 'Country is reqiered';
        }
        foreach ($formerrors as $errors) {
            $message = "<div class='alert alert-danger'>" . $errors  . "</div> ";
            redirect($message, $url = null, $time = 3);
        }
        if (empty($formerrors)) {

            $stmt = $con->prepare("UPDATE items SET name = ?, 
                                                    description = ?, 
                                                    price = ?, 
                                                    country_made = ?, 
                                                    status = ?, 
                                                    cat_ID = ?, 
                                                    member_ID = ?,
                                                    tags = ?
                                                WHERE item_ID = ?");
            $stmt->execute(array($name, $description, $price, $country, 
                                 $status, $category, $member, $tags, $ID));
            $count = $stmt->rowCount();

           $message = "<div class='alert alert-success text-center'> " . $count . " records is executed </div>";
            redirect($message, "items.php", $time = 3);
        }
        echo "</div>";
    }
} elseif ($do == 'Delete') {
    $ID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
   $check = checkvalue('item_ID', 'items', $ID);
   if ($check == 1){
    // send the qury
    $stmt = $con->prepare('DELETE FROM items WHERE item_ID = ?');
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
    $ulr = 'back';
    redirect($message, $ulr = null, $time = 3);
}

} elseif ($do == 'Aprove') {
    $ID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
    $stmt = $con->prepare("UPDATE items SET aprove = '1' WHERE item_ID = ?");
    $stmt->execute(array($ID));
    $count = $stmt->rowCount();
    
     if ($count > 0) {

        $message = "<div class='alert alert-success text-center'> " . $count . " records is executed </div>";
        redirect($message, 'back');
     } else {

     }
} else {
        echo 'there is no $do';
    }

    include $tpl . "footer.php";
    } else {
        header('location:index.php');
        exit();
    }