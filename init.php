<?php
ini_set('display_errer', 'On');
error_reporting(E_ALL);
include("admin/connect.php");

$sessionUser = '';
if(isset($_SESSION['user'])){
    $sessionUser =  $_SESSION['user'];
}

$tpl = "includes/templates/"; // template directory
$css = "layout/css/"; // css directory
$js = "layout/js/"; // js directory
$languages = "includes/languages/"; // languages directory
$functions = 'includes/functions/'; // funtions directory

include($functions . 'function.php');
include($languages . "english.php");
include($tpl . "header.inc.php");



    

