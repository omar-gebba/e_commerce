<?php
session_start();

include "init.php";

$pageTitle = "Profile";

if (isset($_SESSION['frontUser'])) {
    $pageTitle = "Profile";
  // connection with database to fetch information  of user from users table
    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(array($_SESSION['frontUser']));
    $info = $stmt->fetch();
   //connection with database to fetch information  of user from users table
    ?>
    <div class='container text-center'>
        <h1>Profile Page</h1>
    </div>
    <!-- information pannel -->
    <div class='information'>
        <div class='container'>
            <div class='panel panel-primary'>
                <div class='panel-heading'> My Own Data </div>
                <div class='panel-body'> 
                    <ul class='list-unstyled'>
                        <li>
                            <i class='fa fa-unlock-alt fa-fw'></i>
                            <span>User Name </span> : <?php echo $info['username'] ?> 
                        </li>
                        <li>
                            <i class='fa fa-user fa-fw'></i>
                            <span>Full Name</span> : <?php echo $info['fullname'] ?> 
                        </li> 
                        <li>
                            <i class='fa fa-envelope fa-fw'></i>
                            <span>Email</span>  : <?php echo $info['email'] ?> 
                        </li>
                        <li>
                            <i class='fa fa-calendar fa-fw'></i>
                            <span>Regersting Date</span> :  <?php echo $info['regdate'] ?> 
                        </li>
                        <li>
                            <i class='fa fa-tags fa-fw'></i>
                            <span>Fav. Categories</span> :   
                        </li>
                    </ul> 
                </div>
            </div>
        </div>
    </div>
    <!-- my-ads panel -->
    <div id='my-items' class='my-ads'>
        <div class='container'>
            <div class='panel panel-primary'>
                <div class='panel-heading'> My Ads </div>
                <div class='panel-body'>
                    <div class='row'>
                        <?php 
                        $where = 'member_ID';
                        $value = $info['userID'];
                        $items = countItems($where, $value, 10);

                        foreach ($items as $item) { 
                                echo "<div class='col-sm-3'>";
                                    echo "<div class='my-div'>";
                                        echo "<img class='img-responsive' src='house.png' />";
                                        if ($item['aprove'] == 0){
                                            echo "<span class='pend-aprove'> Waiting Aproval</span>";
                                        }
                                        echo "<span class='profile-span price'>$" . $item['price'] . "</span>";
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
            </div>
        </div>
    </div> 
    <!-- my-comments panel -->
    <div class='user-com'>
        <div class='container'>
            <div class='panel panel-primary'>
                <div class='panel-heading'> my comments </div>
                <div class='panel-body comments'> 
                        <?php 
                        $where = 'member_ID';
                        $value = $info['userID'];
                        $coms = countIComment($value);
                        foreach ($coms as $com) {
                                    echo "<div class='my-div'>";
                                        echo "<p>" . $com['comment'] . "</p>";
                                        echo "<span>" . $com['add_date'] . "</span>";
                                    echo "</div>";
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
    <a class='btn btn-info col-xs-offset-4 col-xs-4 add-new' href='newadd.php'> New Add</a>
    <?php
}else {
    header('location: login.php');
}
include $tpl . "footer.php";
?>