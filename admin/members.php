<?php
session_start();
$pageTitle = 'Members';
if (isset($_SESSION['user'])) { 
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
// start manage page

        if ($do == 'Manage') { 
            $query = ' ';
            if (isset($_GET['page']) && $_GET['page'] == 'pending'){
                $query = "AND regstatus = 0";
            }
            $stmt = $con->prepare("SELECT * FROM users  WHERE groupID != 1 $query");
            $stmt->execute();
            $rows = $stmt->fetchall();
            ?>
            <div class='container text-center'>
                <h1 class='text-center text-success'>Manage Members</h1>
                <div class='table-responsive'>
                    <table class='table table-bordered'>
                        <tr>
                            <td> #ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Regirsterd Date </td>
                            <td>control</td>
                        </tr>
                        <?php

                            foreach ($rows as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['userID'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['fullname'] . "</td>";
                                echo "<td>" . $row['regdate'] . "</td>";
                                echo "<td> <a href='?do=Edit&id=" . $row['userID'] . "' class='btn btn-success'> Edit</a>
                                           <a href='?do=Delete&id=" . $row['userID'] . "' class='btn btn-danger confirm'>Delete</a>";
                                           if ($row['regstatus'] == 0){
                                            echo " <a href='?do=Activate&id=" . $row['userID'] . "' class='btn btn-primary confirm'>Activate</a>";
                                             }  "</td>"; 
                            }    
                       ?>   
                    </table>
                </div>
                <a href='members.php?do=Add' class='btn btn-primary'><i class="fa fa-plus" aria-hidden="true"></i>  Add New Member</a>
            </div>
           <?php 

  // start add page................................

         } elseif ($do == 'Add'){ ?>
                    <!-- start html content --> 
                    <div class='container text-center'>
                        <h1 class='text-success'> Add New Member </h1>
                        <form class='form-horizontal' action='?do=Insert' method='POST' enctype='multipart/form-data' >
                                <!-- username feild -->
                            <div class='form-group'>
                                <lable class='col-sm-2'>User Name</lable>
                                <input class='col-sm-9 input-lg' type='txt' name='username' placeholder='Echo User Name' required='required' />
                            </div>
                                    <!-- password feild -->
                            <div class='form-group'>
                                <lable class='col-sm-2'>Password</lable>
                                <input class='password col-sm-9 input-lg' type='password' name='password' placeholder='Echo Password'  required='required' />
                                <div class='col-sm-1'>  <i class="fa fa-eye fa-2x" aria-hidden="true"></i> </div>
                            </div>
                                    <!-- Email feild -->
                            <div class='form-group'>
                                <lable class='col-sm-2'>Email</lable>
                                <input class='col-sm-9 input-lg' type='email' name='email' placeholder='Echo Email' required='required' />
                            </div>
                                    <!-- fullname feild -->
                            <div class='form-group'>
                                <lable class='col-sm-2'>Full Name</lable>
                                <input class='col-sm-9 input-lg' type='txt' name='fullname' placeholder='Echo Full Name' required='required' />
                            </div>
                                    <!-- image feild -->
                            <div class='form-group'>
                                <lable class='col-sm-2'>Choose Image</lable>
                                <input class='col-sm-9 input-lg' type='file' name='avatar' />
                            </div>
                            <input class='btn btn-success col-xs-offset-4 col-xs-4' type='submit' value='Add' >
                        </form>
                    </div>

                    <!-- end html content -->
                <?php
                } elseif ($do == 'Insert') {
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            echo "<div class='container text-center'>";
                            echo "<h1> Member Adding </h1>";

                                // preparing every thing about user image
                            $avatar = $_FILES['avatar'];
                            $avatarName  = $avatar['name'];
                            $avatarTemp_name = $avatar['tmp_name'];
                            $avatarSize = $avatar['size'];
                            $avatarType = $avatar['type'];
                            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
                            // get the extension of user image from its name 
                            $tempExtension = explode('.', $avatarName);
                            $avatarExtension = strtolower(end($tempExtension));
                            // post other form data
                            $username   = $_POST['username'];
                            $password   = $_POST['password'];
                            $email      = $_POST['email'];
                            $fullname   = $_POST['fullname'];
                            $hashedpass = sha1($_POST['password']);
                        
                            $formerrors = array();
                            if (empty($username)) {
                                $formerrors[] = 'password is reqiered';
                            }
                            if (strlen($username) < 4) {
                                $formerrors[] = 'user name must greater than 4 letters';
                            }
                            if (empty($password)) {
                                $formerrors[] = 'this input field is reqiered';
                            }
                            if ($password != '000000') {
                                $formerrors[] = 'password must be 000000';
                            }
                            if (empty($email)) {
                                $formerrors[] = 'email is reqiered';
                            }
                            if (empty($fullname)) {
                                $formerrors[] = ' name is reqiered';
                            }
                            // errors of image

                            if (empty($avatar)) {
                                $formerrors[] = 'Tchoose an image';
                            }
                            if (! empty($avatarName) && ! in_array($avatarExtension, $allowedExtensions)) {
                                $formerrors[] = 'This file extension Not allowed here';
                            }
                            
                            if ($avatarSize > (2*1024*1024)) {
                                $formerrors[] = "your file must be less than 2MB";
                            }
                            // deal with database
                            if (empty($formerrors)) {
                                // make a random image name 
                                $avatar = rand(0, 10000000) . "_" . $avatarName;
                                $avatarPath = 'uploads/avatars/' . $avatar;
                                echo '<br>';
                                $test = 'uploads/avatars/';
                                if (is_dir($test) && is_writeable($test)) {
                                    move_uploaded_file($avatarTemp_name, $avatarPath);
                                } else {
                                    echo 'the file<strong> [' . __DIR__ . '/'  . $test .']</strong>is not directory or not writable';
                                    //chmod($test, 0755);
                                }
                                
                               
                                // check if user name exists in database
                                $check = checkvalue('username', 'users', $username);
                                if ($check == 1) {
                                    echo 'sorry ' . $username . ' exists in database';
                                } else {
                                    $stmt = $con->prepare("INSERT INTO 
                                        users(username, password, email, fullname, regdate, regstatus, img)
                                    VALUES(?, ?, ?, ?, now(), 1, ?)");
                                    $stmt->execute(array($username, $hashedpass, $email, $fullname, $avatarPath));
                                    $count = $stmt->rowCount();

                                    $message = "<div class='alert alert-success'>Number Of Data Inserted Is " . $count . " </div>";
                                    //redirect($message,'back');
                                    }
                            } else {
                                foreach ($formerrors as $errors) {
                                    
                                    $meassage = "<div class='alert alert-danger'>" . $errors . "</div>";
                                    $url = 'back';
                                    redirect($meassage, $url, 5);
                                }
                            }
                            echo '</div>';
                        } else { 
                            $message = "<div class='alert alert-danger text-center'> sorry you can\'t browse this page</div>";
                            $url ='back';
                            redirect($message, $url, 6);
                        }
                } elseif ($do == 'Edit') {  
                $userID = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
                $stmt = $con->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1");
                $stmt->execute(array($userID));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($count > 0) { ?>
                    <!--start Edit form-->
                    <div class='container text-center'>
                        <h1 class='text-center text-primary'><i class="fa fa-pencil-square" aria-hidden="true"></i>Edit Members</h1>
                        <form class='form-horizontal member_form' action='?do=Update' method='POST' enctype='multipart/form-data'>
                            <div class='form-group'>
                                <label class='col-sm-2'>User Name  </label>
                                <input class='col-sm-9 col-offset-1' type='text' name='username' value='<?php echo $row['username']; ?>' autocomplete='off' />
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-2'>Password  </label>
                                <input type='hidden' name='userID' value='<?php echo $row["userID"]; ?>' />
                                <input type='hidden' name='oldpassword' value='<?php echo $row["password"]; ?>' />
                                <input class='col-sm-9 col-offset-1' type='password' name='newpassword' autocomplete="new-password" placeholder='leave it blank if you need old password' />
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-2'>User Email  </label>
                                <input class='col-sm-9 col-offset-1' type='email' name='email' value='<?php echo $row['email']; ?>' autocomplete='off' />
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-2'>Full Name </label>
                                <input class='col-sm-9 col-offset-1' type='text' name='fullname' value='<?php echo $row['fullname']; ?>' autocomplete='off' />
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-2'>choose image</label>
                                <input name='avatar' type='file' class='col-sm-9'>
                            </div>
                            <input class='col-sm-5 col-sm-offset-4 btn btn-primary btn-lg marg' type='submit' name='submit' value='Save Data' />
                            
                        </form>
                    </div>
                    <!-- end Edit form-->
                    <?php 
                } else {
                $measage = 'noooo you are not here';
                $url = 'back';
                redirect($meassage, $url, 5);
                }                                         
            
             } elseif ($do == 'Update') {
                 echo '<h1 class="text-center">Member Update</h1>';
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
                        $userID     = $_POST['userID'];
                        $username   = $_POST['username'];
                        $email      = $_POST['email'];
                        $fullname   = $_POST['fullname'];

                        $formErrors = array();
                        
                        echo "<div class='container'>";
                        if (empty($username)) {
                            $formErrors[] = '<div class="alert alert-danger text-center">user name cannot be empty</div>';
                        }
                        if (strlen($username) < 4) {
                            $formErrors[] = '<div class="alert alert-danger text-center">user name cannot be less than 4 chars.</div>';
                        }
                        if (empty($email)) {
                            $formErrors[] = '<div class="alert alert-danger text-center">email cannot be empty</div>';
                        }
                        if (empty($fullname)) {
                            $formErrors[] = '<div class="alert alert-danger text-center">full name cannot be empty</div>';
                        }
                        // fetch image data
                        if (isset($_FILES['avatar'])) {
                            $avatar = $_FILES['avatar'];
                            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
                            $temp = explode('.', $avatar['name']);
                            $avatarExtension = strtolower(end($temp));
                            $avatarSize = $avatar['size'];
                            if(! empty($avatar) && ! in_array($avatarExtension, $allowedExtensions)) {
                                $formErrors[] = '<div class="alert alert-danger text-center">this file not allowed</div>';
                            }
                            if ($avatarSize > (4*1024*1024)) {
                                $formerrors[] = "<div class='alert alert-danger text-center'>file size must be not exceed 5MB</div>";
                            }
                        }
                        
                        if(empty($formErrors)) {
                            if (isset($avatar)) {
                                $path = "uploads/avatars/";
                                $tmp = rand(0, 1000) . "_" . $avatar['name'];
                                $avatarName = $path . $tmp;
                                move_uploaded_file($avatar['tmp_name'], $avatarName);
                            }
                            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
                            
                            $stmt = $con->prepare("UPDATE users SET username = ?, email = ?, fullname = ?, password = ?, img = ? WHERE userID = ?");
                            $stmt->execute(array($username, $email, $fullname, $pass, $avatarName, $userID));
                            $count = $stmt->rowCount();

                            $message = "<div class='alert alert-success text-center'> " . $count . " records is executed </div>";
                            $url = 'back';
                            redirect($message, $url);

                        }else{
                            foreach ($formErrors as $error) {
                                echo $error ;
                            }
                        }
                    } else {
                        
                        $meassage = 'you should come by post method';
                        $url = 'back';
                        redirect($message, $url, 4);
                    }
                    echo "</div>";
          // delete page           
                } elseif ($do == 'Delete') {
                    $userID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval ($_GET['id']) : 0;
                    // start delete statement
                    $stmt = $con->prepare('DELETE FROM users WHERE userID = ?');
                    $stmt->execute(array($userID));
                    $count = $stmt->rowCount();
                    
                    if($count > 0) {
                        $meassage = "<div class='alert alert-danger text-center'> "  . $count . " Member Deleted </div>";
                        redirect($meassage, $url, 5);
                    } else { 
                        $meassage = "<div class='alert alert-info text-center'> No Members Deleted </div>";
                        $url = 'back';
                        redirect($meassage, $url, 5);
                    }
            // activate members page      
                } elseif ($do == 'Activate') {
                    $userID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
                    $stmt = $con->prepare("UPDATE users SET regstatus = '1' WHERE userID= ?");
                    $stmt->execute(array($userID));
                    $count = $stmt->rowCount();
                    
                     if ($count > 0) {

                        $message = "<div class='alert alert-success text-center'> " . $count . " records is executed </div>";
                        redirect($message, $url);
                     } else {

                     }
                }

    include $tpl . "footer.php";
} else {
   header('location: index.php');
    exit();
       
}