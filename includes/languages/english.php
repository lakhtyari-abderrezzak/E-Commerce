<?php 

function lang($phrase){
    
    // English Navbar Links
    static $lang = array(
        "HOME-ADMIN"=> "Home",
        "CATEGORIES"=> "Categories",
        "ITEMS"=> "Items",
        "MEMBERS"=> "Members",
        "COMMENTS" => "Comments",
        "STATISTICS"=> "Statistics",
        "LOGS"=> "Logs",
    );

    return $lang[$phrase];
}

