<?php 

$dns = "mysql:host=localhost;dbname=shop";
$user = "root";
$pass = "";
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
);


try{
    $conn = new PDO($dns, $user, $pass, $option);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e){
    echo "Failed To Connect". $e->getMessage();
}