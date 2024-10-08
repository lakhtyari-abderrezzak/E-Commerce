<?php

function getAllFromAnyTable($feilds, $table, $where = NULL, $orderBy = NULL , $ordering = 'ASC'){
    global $conn;
    $stmt = $conn->prepare("SELECT $feilds FROM $table $where ORDER BY $orderBy $ordering");
    $stmt->execute();
    $all = $stmt->fetchAll();
    return $all;
}
//Function That Get One record .
function oneRecord($feilds, $table, $where = null){
    global $conn;
    $stmt = $conn->prepare("SELECT $feilds FROM $table $where");
    $stmt->execute();
    $all = $stmt->fetch();
    return $all;
}

// Function that echos pageTitle incase
// the page has $pageTitle variable in it 

function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo "Default";
    }
}

/*
 *** Fucntion That Takes Two Arguments
 *** Redirects Users That Try To Access Pages In From Url
 */
function redierctHome($Msg, $url = Null, $seconds = 3)
{
    if ($url === Null) {
        $url = "index.php";
        $link = "Home Page";
    } else {
        $url = isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
        $link = "Previous Page"; 
    }
    echo $Msg;
    echo "<div class='alert alert-info'> You Will be Redirected To The " . $link . " After " . $seconds . " Seconds</div>";

    header("refresh:$seconds;url=$url");
    exit();
}
/*
 *** Fucntion That Takes Three Arguments [$Select, $From, $Value]
 *** Fucntion Will Look Like This checkUSer(SELECT * 'Usernsme' FROM 'usres' WHERE Usernme = Value )
 *** Function CheckIetm [v1.0]
 *** Checkes Username Exists in Database 
 */

function checkUser($select, $from, $value)
{
    global $conn;
    $statement = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();

    return $count;

}

/*
*** Function That Takes Two Arguments Item And Table
*** This Function Will Count Number Of Rows And It Will Return A Number 
*** Function countItems()
*/

function countItems($item, $table){
    global $conn;
    $stmt2 = $conn->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}

/*
*** Function GetLatest 
*** Function That Selects latest [Users, Items or Comments]
*** Function GetLatest Takes Arguments 
*/

function getLatest($select, $table, $order, $limit){
    global $conn;
    $stmt = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt->execute();
    $row = $stmt->fetchAll();
    return $row;
}