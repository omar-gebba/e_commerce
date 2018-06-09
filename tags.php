<?php
session_start();
include "init.php";
$pageTitle = "New Add";
if (isset($_GET['name'])) {
    $tagName = $_GET['name'];
    ?>
    <div class='container'>
        <h1 class='text-center'><?php echo ucfirst($tagName); ?></h1>
        <div class='row'>
        <?php 
        $where = "WHERE tags LIKE '%" . $tagName  . "%'";
        $items = getAll("*", "items", $where, " ", " ");

        foreach ($items as $item) {
                echo "<div class='col-sm-3'>";
                    echo "<div class='tags-div'>";
                        echo "<img class='img-responsive' src='house.png' />";
                        echo "<span class='price'>" . $item['price'] . "</span>";
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
}else {
    echo "No Tags ";
}
include $tpl . "footer.php";
?>