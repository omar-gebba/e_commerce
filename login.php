<?php
session_start();
if (isset($_SESSION['frontUser'])) {
 //  header('location: index.php');
}
include "init.php";
if (isset($_POST['login'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $user = $_POST['user'];
        $pass = $_POST['password'];
        $hashedPass = sha1($pass);

        $stmt = $con->prepare("SELECT
                                  userID, username, password
                                FROM 
                                    users
                                WHERE 
                                    username = ? 
                                AND 
                                password = ? 
                                    ");

        $stmt->execute(array($user, $hashedPass));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();
 
        if ($count > 0) {
            $_SESSION['frontUser'] = $user;
            $_SESSION['uID'] = $get['userID'];
            header('location: index.php');
        } else { 
            // there is a fault appear when write page to redirect
        }
    }
}else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name   = $_POST['name'];
        $email  = $_POST['email'];
        $pass1  = $_POST['password1'];
        $pass2  = $_POST['password2'];
        // form containing errors 
        $signupErrors = array();
        // post and sanitize name
        if (isset($_POST['name'])) {
            if (empty($name)) {
                $signupErrors[] = 'name cannot be empty';
            }
            $check = checkvalue("username", "users", $name);
            if ($check > 0) {
                $signupErrors[] = "this username is used ... try another one";
            }
            $fillsmall = filter_var ( $_POST['name'], FILTER_SANITIZE_STRING);
            if (strlen($fillsmall) < 4) {
                $signupErrors[] = 'name must be greater than 4 charachtars'; 
            }
        }
        // post and make sure the password fields is identical
        if (isset($_POST['password1']) && isset($_POST['password2'])) {
            if (empty($pass1)) {
                $signupErrors[] = 'password cannot be empty';
            }
            $hpass1 = sha1($pass1);
            $hpass2 = sha1($pass2);
            if ($hpass1 != $hpass2) {
                $signupErrors[] = 'password dosn\'t match';
            }
        }
        // post, sanitize and validate email
        if (isset($email)) {
            if (empty($email)) {
                $signupErrors[] = 'email must be included';
            }
            $filterEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $validateEmail = filter_var($filterEmail, FILTER_VALIDATE_EMAIL);
            if ($validateEmail != true) {
                 $signupErrors[] = 'type a valid email';
             }
         }
         // check if no errors and check if the email exists by function $checkvalue()
         if (empty($signupErrors)) {
             $selected = 'email';
             $table = 'users';
             $value = $email;
             $check = checkvalue($selected, $table, $value);
             if ($check > 0){
                 $signupErrors[] = 'This email already exisit ... please retry another one';
             } else {
                    $stmt4 = $con->prepare("INSERT INTO 
                                        users(username, password, email, regdate)
                                    VALUES(?, ?, ?, now())");
                    $stmt4->execute(array($name, $hpass1, $email));
                    $count = $stmt4->rowCount();
                    // success measage to show
                    $inserted = "the data is inserted";
             }
         }
    }
 }
?>
<div class='container text-center front-login'>
    <h1>
        <span class='active' data-class='login'>Login</span> | <span data-class='signup'>Signup</span>
    </h1>
    <!-- login form -->
    <form class="login" action=' <?php echo $_SERVER['PHP_SELF']; ?> ' method='post'>
    <div>
        <input class='form-control' type='txt' name='user' placeholder='Member name' autocomplete='off' />
    </div>
    <div> 
        <input class='form-control' type='password' name='password' placeholder='password' autocomplete='new-password' />
    </div>
    <input class='btn btn-primary btn-lg btn-block' name='login' type='submit'  value='Login' />
</form>
   <!-- signup form  -->
   <form class="signup" action=' <?php echo $_SERVER['PHP_SELF']; ?> ' method='post'>
   <div class='d_astrisk'>
        <input
            pattern='.{4,}'
            title='name must be > 4 chars'
            class='form-control'
            type='text'
            name='name'
            placeholder='Type Your name' 
            autocomplete='off' 
            required="required"
        />
    </div> 
    <div class='d_astrisk'>
        <input 
            class='form-control' 
            type='email' 
            name='email' 
            placeholder='Type Your mail' 
            autocomplete='off'
            required="required" 
        />
    </div> 
    <div class='d_astrisk'> 
        <input 
            minlength='4'
            class='form-control' 
            type='password' 
            name='password1' 
            placeholder='Type Your password' 
            autocomplete='new-password' 
            required="required"
        />
    </div> 
    <div class='d_astrisk'>
        <input 
            minlength='4'
            class='form-control' 
            type='password' 
            name='password2' 
            placeholder='Retype Your password' 
            autocomplete='new-password' 
            required="required"
        />
   </div> 
   <input class='btn btn-primary btn-block' name='signup' type='submit'  value='Signup' />
</form>
</div>
<div class='ontainer text-center signup-errors'>
    <?php
   if (! empty($signupErrors)) {
        foreach($signupErrors as $error){
            echo $error . "<br />";
        }
    }
   if (isset($inserted)){
       echo $inserted;
   }
    ?>
</div>
<?php
include $tpl . "footer.php";
?>