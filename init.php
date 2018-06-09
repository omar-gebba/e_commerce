<?php

//error reporting
ini_set("display_errors", "1");
error_reporting(E_ALL);

include 'admin/config.php';
$tpl = 'includes/templates/';
$languages = 'includes/languages/';
$functions = 'includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';



// session for user in variable to help shortcuts
$sessionUser = " ";
if (isset($_SESSION['frontUser'])) {
    $sessionUser = $_SESSION['frontUser'];
}
// important files
include $languages . 'eng.php';
include $functions . 'functions.php';
include $tpl . 'header.php';

?>