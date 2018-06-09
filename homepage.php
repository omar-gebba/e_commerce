<?php
ob_start();
session_start();
$pageTitle = "Home Page";
include "init.php";
?>
<div class='container text-center'>
    <div class='row'>
    <?php 
    $items = countAll();

    foreach ($items as $item) {
            echo "<div class='col-sm-3'>";
                echo "<div class='my-div'>";
                     echo "<img class='img-responsive' src='house.png' />";
                     echo "<span class='price'>$" . $item['price'] . "</span>";
                     echo "<div>";
                       echo "<a href='items.php?id=" . $item['item_ID']  . "'><h3>" . $item['name'] . "</h3></a>";
                       echo "<p>" . $item['description'] . "</p>";
                     echo "</div>";
                echo "</div>";
            echo "</div>";   
    }
    ?>
    </div>
</div>
<?php
include $tpl . "footer.php";
ob_end_flush();
?>