<?php
include("connect.php");


$tpl = "includes/templates/"; // template directory
$css = "layout/css/"; // css directory
$js = "layout/js/"; // js directory
$languages = "includes/languages/"; // languages directory
$functions = 'includes/functions/'; // funtions directory

include($functions . 'function.php');
include($languages . "english.php");
include($tpl . "header.inc.php");

// include Navbar in All Pages except the one with $noNavbar Variable
if (!isset($noNavbar)) {

    include($tpl . "navbar.php");

}