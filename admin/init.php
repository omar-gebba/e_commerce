<?php
//error reporting
ini_set("display_errors", "1");
error_reporting(E_ALL);

include 'config.php';
$tpl = 'includes/templates/';
$languages = 'includes/languages/';
$functions = 'includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';


// important files

include $languages . 'eng.php';
include $functions . 'functions.php';
include $tpl . 'header.php';
// include navbar on all pages except which has the variable $noNavbar
if  (! isset($noNavbar)) {
    include $tpl . 'navbar.php';
}
