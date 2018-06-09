<?php
session_start();
include 'init.php';
if ( isset($_GET['id']) && is_numeric($_GET['id'])) {
    ?>
    <div class='container text-center'>
        <h1><?php echo ucfirst($_GET['pageName']); ?></h1>
        <div class='row'>
        <?php 

        $ID = $_GET['id'];

        $items = countItems('cat_ID', $ID);
        
        foreach ($items as $item) {
                echo "<div class='col-sm-3'>";
                    echo "<div class='my-div'>";
                        echo "<img class='cat-img img-thumbnail img-responsive' src='" . $item['item_img']  ."' />";
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
} else {
    echo " you must enter ID";
}
include $tpl . "footer.php";
?>