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


// function to count categories 

function countCats($where = NULL) {
    global $con;

    $stmt3 = $con->prepare("SELECT * FROM  categories $where");

    $stmt3->execute();
    $cats = $stmt3->fetchAll();
    return $cats;
}
                  //-----------//

// function to count items v2

function countItems($where, $value, $sql = NULL) {
    global $con;
    if ($sql == Null){
        $sql = "AND aprove = 1";
    } else {
        $sql = " ";
    }
    $stmt3 = $con->prepare("SELECT * FROM items WHERE $where = $value $sql ORDER BY item_ID DESC");
    $stmt3->execute();
    $items = $stmt3->fetchAll();
    return $items;
}

// function to select not aproved users

function checkUsereStatus($user) {
    global $con;

    $stmt = $con->prepare("SELECT username, regstatus 
                            FROM 
                                users 
                            WHERE 
                                username = ? 
                            AND 
                                regstatus = 0");
    $stmt->execute(array($user));
    $status = $stmt->rowCount();
    return $status;
}

// function to count commets 

function countIComment($value) {
    global $con;

    $stmt3 = $con->prepare("SELECT * FROM comments WHERE user_ID = $value ORDER BY comID DESC");
    $stmt3->execute();
    $items = $stmt3->fetchAll();
    return $items;
}

// function .... check if an value exist in database or not 
function checkvalue($selected, $table, $value) {
    global $con;

    $stmt = $con->prepare("SELECT $selected FROM $table WHERE $selected = ?");
    $stmt->execute(array($value));
    $count = $stmt->rowCount();
    return $count;
}// function to count all items in home page.

function countAll() {
    global $con;

    $stmt6 = $con->prepare("SELECT * FROM  items WHERE aprove = 1 ORDER BY item_ID ASC");

    $stmt6->execute();
    $all = $stmt6->fetchAll();
    return $all;
}
// function to count All
function getAll($select, $table, $where = null, $and = null, $order) {
    global $con;
    

    $stmt3 = $con->prepare("SELECT $select FROM  $table $where $and $order");

    $stmt3->execute();
    $all = $stmt3->fetchAll();
    return $all;
}
//----------------------------------------------------------//













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