<?php
session_start();
$pageTitle = 'dashboard';
if (isset($_SESSION['user'])) { 
    include 'init.php';
    $latestUsres = 5;
    $lastItem = 3;
    $latest =  latestItems("*", "users", "userID", $latestUsres); // latest user array to fech data in the panel below
    $last_items = latestItems('*', 'items', 'item_ID', $lastItem);
    ?>
    <div class='container text-center dashboard-stats'>
        <div class='row'>
            <h1> Dashboards </h1>
            <div class='col-md-3 col-xs-12 st-members'>
                Total Members
                <span><a href='<?php echo'members.php'; ?>'><?php countItem('userID', 'users'); ?> </a> </span>
            </div>
            <div class='col-md-3 col-xs-12 st-pending'>
                Pending Members
                <span><a href='<?php echo'members.php?do=Manage&page=pending'; ?>'> <?php countItem('userID', 'users', 'regstatus', '0');  ?></a></span>
            </div>
            <div class='col-md-3 col-xs-12 st-items'>
                Total Items
                <span><a href='items.php?do=Manage'> <?php countItem('item_ID', 'items', $status = null, $value = null); ?></a></span>
            </div>
            <div class='col-md-3 col-xs-12 st-comments'>
                Total Comments
                <span><a href='comments.php?do=Manage'> <?php countItem('comID', 'comments ', $status = null, $value = null); ?></a></span>
            </div>
        </div>
    </div>
    <div class='container latest'>
        <div class='row'>
            <!-- start members panel -->
            <div class='col-sm-6'>
                <div class='panel panel-default'>
                    <div class='panel-heading'>
                        <i class='fa fa-users'></i> Latest  <?php  echo $latestUsres; ?> Registered Users
                    </div>
                    <div class='panel-body'>
                        <ul class='list-unstyled latest-user'>
                        <?php
                        
                        foreach ($latest as $user) {
                            echo "<li>";
                            echo $user['username'] . '<a href="members.php?do=Edit&id=' . $user['userID']  .'" class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</a>';
                            if ($user['regstatus'] == '0') {
                                echo '<a href="members.php?do=Activate&id=' . $user['userID']  . '" class="btn btn-primary pull-right confirm"><i class="fa fa-active"></i> Activate</a>';
                            }
                            echo "</li>";
                        }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end of members panel -->
            <!-- start items panel -->
            <div class='col-sm-6'>
                <div class='panel panel-default'>
                    <div class='panel-heading'>
                        <i class='fa fa-tag'></i> Latest Item
                    </div>
                    <div class='panel-body'>
                        <ul class='list-unstyled latest-user'>
                            <?php 
                            foreach ($last_items as $item) {
                                echo '<li>';
                                    echo $item['name'] . "<a class='btn btn-success pull-right' href='items.php?do=Edit&id=" . $item['item_ID']  . "'>Edit</a> ";
                                    if ($item['aprove'] == 0) {
                                        echo "<a href='items.php?do=Aprove&page=aprove&id=" . $item['item_ID'] . "' class='btn btn-primary pull-right confirm'><i class='fa fa-check'></i> Aprove</a>";
                                     }
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>  
        </div>
        <!-- end of items panel -->
        <!--start comments panel -->
        <div class='row'>
            <div class='col-sm-6 comments'>
                <div class='panel panel-default'>
                    <div class='panel-heading'>
                        <i class='fa fa-users'></i> Last 5 Comments </div>

                    <div class='panel-body'>
                        <div class='comments'>
                        <?php
                        $stmt = $con->prepare("SELECT 
                                    comments.*, users.username 
                              AS
                                member
                              FROM 
                                    comments 
                              
                              INNER JOIN 
                                    users 
                              ON 
                                    users.userID = comments.user_ID
                                ORDER BY comID DESC
                                LIMIT 5");
                        $stmt->execute();
                        $comms = $stmt->fetchall();
                        foreach($comms as $com) {
                            echo "<div>";
                                echo "<span class='com-name'>" . $com['member']  . " </span>";
                                echo "<p class='com-comment'>" . $com['comment']  . "</p";
                            echo "</div>";
                        }          
                        ?>
                        </div>
                    </div>
                </div>
            </div>
           <!-- end of comments panel -->
        </div>
    </div>
    <?php
    include $tpl . "footer.php";
} else {
    header('location: index.php');
    exit();
}