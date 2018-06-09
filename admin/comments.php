<?php

session_start();
$pageTitle = 'Comments';

if (isset($_SESSION['user'])) {
   include 'init.php';

   $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
if ($do == 'Manage') {

      $stmt = $con->prepare("SELECT 
                                    comments.*, items.name 
                               AS 
                                    item_name, users.username 
                              FROM 
                                    comments 
                              INNER JOIN 
                                    items
                              ON 
                                    items.item_ID = comments.item_ID 
                              INNER JOIN 
                                    users 
                              ON 
                                    users.userID = comments.user_ID");
        $stmt->execute();
        $comms = $stmt->fetchall();
        ?>
        <div class='container text-center'>
            <h1 class='text-center text-success'>Manage Comments</h1>
            <div class='table-responsive'>
                <table class='table table-bordered'>
                    <tr>
                        <td> #ID</td>
                        <td>The Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Date </td>
                        <td> Control </td>
                    </tr>
                    <?php

                        foreach ($comms as $comm) {
                            echo "<tr>";
                                echo "<td>" . $comm['comID'] . "</td>";
                                echo "<td>" . $comm['comment'] . "</td>";
                                echo "<td>" . $comm['item_name'] . "</td>";
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
       <?php
} elseif ($do == 'Edit') {  
    $comID = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
    $stmt = $con->prepare("SELECT * FROM comments WHERE comID = ? LIMIT 1");
    $stmt->execute(array($comID));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) { ?>
        <!--start of form and header-->
        <div class='container text-center'>
            <h1 class='text-center text-primary'><i class="fa fa-pencil-square" aria-hidden="true"></i>Edit Comment</h1>
            <form class='form-horizontal' action='?do=Update' method='POST'>
                <div class='form-group'>
                    
                    <textarea class='col-sm-9 col-sm-offset-2' type='text' name='comment' value='<?php echo $row['comment']; ?>' autocomplete='off' required='required' ><?php echo $row['comment']; ?></textarea>
                </div>
                <input type='hidden' name='ID' value='<?php echo $comID; ?>'/>
                <input class='col-sm-5 col-sm-offset-4 btn btn-primary btn-lg marg' type='submit' name='submit' value='Save Data' />
            </form>
           
        </div>
        <!-- end of form-->
        <?php 
        
    } else {
    $message = 'noooo you are not here';
    $url = 'back';
    redirect($message, $url, 5);
    }                                         

 } elseif ($do == 'Update') {
     echo '<h1 class="text-center">Member Update</h1>';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $comID     = $_POST['ID'];
            $comment   = $_POST['comment'];
            
            echo "<div class='container'>";
                
                $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE comID = ?");
                $stmt->execute(array($comment, $comID));
                $count = $stmt->rowCount();

                $message = "<div class='alert alert-success text-center'> " . $count . " records is executed </div>";
                $url = 'back';
                redirect($message, $url);

        } else {
            
            $meassage = 'you should come by post method';
            $url = 'back';
            redirect($message, $url, 4);
        }
        echo "</div>";
// delete page           
    } elseif ($do == 'Delete') {
        $comID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval ($_GET['id']) : 0;
        // start delete statement
        
        $stmt = $con->prepare('DELETE FROM comments WHERE comID = ?');
        $stmt->execute(array($comID));
        $count = $stmt->rowCount();
        
        if($count > 0) {
            $meassage = "<div class='alert alert-danger text-center'> "  . $count . " Member Deleted </div>";
            redirect($meassage, $url, 5);
        } else { 
            $meassage = "<div class='alert alert-info text-center'> No Members Deleted </div>";
            $url = 'back';
            redirect($meassage, $url, 5);
        }
/* activate members page   */        
    } elseif ($do == 'Aprove') {
        $comID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        $stmt = $con->prepare("UPDATE comments SET status = '1' WHERE comID= ?");
        $stmt->execute(array($comID));
        $count = $stmt->rowCount();
        
         if ($count > 0) {

            $message = "<div class='alert alert-success text-center'> " . $count . " records is executed </div>";
            redirect($message, $url);
         } else {

         }
        }
else {
    echo "there is no \$do here.";
}

include $tpl . "footer.php";
} else {
    header('location:index.php');
    exit();
}
