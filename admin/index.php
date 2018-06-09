<?php

session_start();
$noNavbar = '';
$pageTitle = 'admin login';
if (isset($_SESSION['user'])) {
  header('location: dashbord.php');
}
include 'init.php';


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
                           AND 
                                groupID = 1
                            LIMIT 1");
    $stmt->execute(array($user, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    
    if ($count > 0) {
        $_SESSION['user'] = $user;
        $_SESSION['ID'] = $row['userID'];
        header('location: dashboard.php');
    } else { header('location: index.php'); }

    }
    ?>

<form class="login" action=' <?php echo $_SERVER['PHP_SELF']; ?> ' method='post'>
    <h1 class='text-center'> Admin Login </h1>
    <input class='form-control' type='txt' name='user' placeholder='admin name' autocomplete='off' />
    <input class='form-control' type='password' name='password' placeholder='password' autocomplete='new-password' />
    <input class='btn btn-primary btn-lg btn-block' type='submit'  value='Login' />
</form>

<?php

include $tpl . "footer.php";

?>