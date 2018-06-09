<?php
// get title function 
function getTitle() {

    global $pageTitle;

    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo lang('default');
    }
}
// redirect function 
// functionb v2.0
function redirect($message, $url = null, $time = 3) {
    
    if ($url === null) {
        $url = "dashboard.php";
        $link = "Home Page";
    } else{
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ' ') {
        $url = $_SERVER['HTTP_REFERER'];
        $link = 'Previos Page';
        } else {
            $url = "dashboard.php";
            $link = "Home Page";
        }
    }
    echo "<div class='container'>";
        echo $message;
        echo "<div class='alert alert-success'> You Will Be Redirected to " . $link . " After " . $time . " Seconeds </div>";
    echo "</div>";
    header("refresh:$time;url=$url");
    exit();
}

// function .... check if an value exist in database or not 
function checkvalue($selected, $table, $value) {
    global $con;

    $stmt = $con->prepare("SELECT $selected FROM $table WHERE $selected = ?");
    $stmt->execute(array($value));
    $count = $stmt->rowCount();
    return $count;
   
}
// function to count items in dashboard page  v2.0 


function countItem($item, $table, $status = null, $value = null) {
    global $con;
    
    if ($status != null && $value != null){
        $query = "WHERE $status = $value";
    } else { 
        $query = "";
    }
    

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table $query");
    $stmt2->execute();
    $count = $stmt2->fetchColumn();
    echo $count;
}

// function to get the latest items from database

function latestItems($select, $table, $order, $limit = 5) {
    global $con;

    $stmt3 = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt3->execute();
    $rows = $stmt3->fetchAll();
    return $rows;
}
// function to count categories
function countCats() {
    global $con;

    $stmt3 = $con->prepare("SELECT * FROM  categories WHERE parent = 0 ORDER BY ID ASC");

    $stmt3->execute();
    $cats = $stmt3->fetchAll();
    return $cats;
}
// function to count All
function getAll($select, $table, $where = null, $and = null, $order) {
    global $con;
    

    $stmt3 = $con->prepare("SELECT $select FROM  $table $where $and $order");

    $stmt3->execute();
    $all = $stmt3->fetchAll();
    return $all;
}