<?php 
$do = '';
if (isset($_GET['do'])) {
    $do = $_GET['do'];
} else {
    $do = 'Manage';
}
// if the page is main page

if ($do == 'Manage') {
    echo 'welcom you are in the Manage page';
} elseif ($do == 'Add') {
    echo 'welcom you are in the Add page';
} elseif ($do == 'Insert') {
    echo 'welcom you are in the Insert page';
} elseif ($do == 'Category') {
    echo 'welcom you are in the Category page';
} else {
    echo 'Error you are not wellcome here';
}