<?php

session_start();
$pageTitle = "Home Page";


include("init.php");

echo "Welcome To Home Page";

include($tpl . "footer.inc.php");
