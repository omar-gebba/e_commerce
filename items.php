<?php
session_start();
include "init.php";
$pageTitle = "items";

$ID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
if ($ID > 0) {
    $stmt = $con->prepare("SELECT items.*, categories.name AS cat_name, users.username, users.fullname
                            FROM items
                            INNER JOIN categories
                            ON categories.ID = items.cat_ID
                            INNER JOIN users
                            ON users.userID = items.member_ID
                            WHERE item_ID = ?
                            AND aprove = 1");
    $stmt->execute(array($ID));
    $count = $stmt->rowCount();

    if ($count > 0) {
        $data = $stmt->fetchAll();
        foreach ($data as $ch) {
            ?>
            <div class='container items'>
                <h1 class='text-center'> <?php echo ucfirst($ch['name']); ?></h1>
                <div class='col-md-4 col-xs-3'>
                    <img  class='img-thumbnail img-responsive center-block' src='<?php echo $ch['item_img'] ?>' />
                </div> 
                <div class='col-xs-offset-1 col-md-6 col-xs-8'> 
                    <div class='item-data'><span>Name</span>: <?php echo $ch['name']; ?> </div>
                    <div class='item-data'><span>Description</span>: <?php echo $ch['description']; ?></div>
                    <?php
                    $stat = array(
                        '1' => 'New',
                        '2' => 'Like New',
                        '3' => 'Used',
                        '4' => 'Very Old',
                         );
                         ?>
                    <div class='item-data'><span>Status</span>: <?php echo $stat[$ch['status']]; ?></div>
                    <div class='item-data'><span>price</span>: <?php echo "$" . $ch['price']; ?> </div>
                    <div class='item-data'><span>Date</span>: <?php echo $ch['add_date']; ?> </div>
                    <div class='item-data'><span>Country</span>: <?php echo $ch['country_made']; ?> </div>
                    <div class='item-data'><span>Category</span>: 
                        <a href="categories.php?id=<?php echo $ch["cat_ID"]; ?>&pageName=<?php echo $ch["cat_name"]; ?>"> <?php echo $ch['cat_name'];?> </div></a> 
                    <div class='item-data'><span>User</span>: <a><?php echo $ch['username']; ?> </a></div>
                    <div class='item-data'><span>Tags</span>:
                    <?php 
                    $allTags = explode(',', $ch['tags']);
                    foreach ($allTags as $tag) {
                        $tag = str_replace(' ', '', $tag);
                        $tag = strtolower($tag);
                        if (! empty($tag)) {
                            echo "<a href='tags.php?name=" . $tag  . "'>" . $tag  . "|</a>";
                        }else {
                            echo "there is no tags";
                        }
                    }
                    ?>
                    </div>
                </div> 
            </div>
         <?php   
            if (isset($_SESSION['frontUser'])) {?>
                <hr class='custom-hr'>
                <!-- comments section  -->
                <div class='container'> 
                    <div class='col-md-offset-5'>
                        <div class='add-comment'> 
                            <h3> Add Your Comment</h3> 
                            <form action='<?php echo $_SERVER["PHP_SELF"]. '?id=' .$ch['item_ID'] ?>' method='post'> 
                                <textarea name='comment'></textarea>
                                <input class='btn btn-primary' type='submit' value='Add Comment' />
                            </form>
                            <?php
                    
                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                    $itemid = $ch['item_ID'];
                                    $userid = $_SESSION['uID'];
                                    if (! empty($comment)) {
                                        $stmtc = $con->prepare("INSERT INTO comments(comment, status, add_date, item_ID, user_ID) 
                                                                VALUES(?, 0, NOW(), $itemid, ?)");
                                        $stmtc->execute(array($comment, $_SESSION['uID']));
                                        if ($stmtc) {
                                            echo "<div class='ad-msg'>your comment is added and waiting approve from admin <div class='hide-msg'>hide</div></div";
                                        }
                                    }
                                }
                            
                            ?>
                        </div>
                    </div>
                </div>
                
                <hr class='custom-hr'>
                <?php
                        
                    $stmt4 = $con->prepare("SELECT comments.*, users.fullname AS member
                                            FROM comments
                                            INNER JOIN users
                                            ON users.userID = comments.user_ID
                                            WHERE item_ID = ?
                                            AND status = 1");
                    $stmt4->execute(array($ID));
                    $coms = $stmt4->fetchAll();
                    
                    foreach ($coms as $com) { 
                        ?>
                    
                        <div class='container'>
                            <div class='col-xs-1'> 
                                <img  class='img-thumbnail img-circle img-responsive center-block' src='house.png' />
                            </div> 
                            <div class='col-xs-10 comment-div'>
                            <?php
                                echo "<ul class='list-unstyled'>";
                                    echo "<li class='name'>" . ucfirst($com['member']) . "</li>";
                                    echo "<li class='comment'>" . $com['comment'] . "</li>";
                                    echo "<span class='com-date'>" . $com['add_date'] . "</span>";
                                    
                                echo "</ul>";
                            echo "</div> ";
                        echo "</div> ";
                        
                        echo "<hr class='custom-hr'>";
                    }
                ?>
            <?php
            }
        } 
    }else {
        echo "there is no such item";
    }
} else {
    echo "there is no items to show";
}


include $tpl . "footer.php";
?>