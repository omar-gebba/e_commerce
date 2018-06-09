<?php
session_start();

include 'init.php';

$categories = countCats();
foreach ($categories as $cat) {
    echo $cat['name'] . "<br />";
}

include $tpl . "footer.php";

?>