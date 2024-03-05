<?php

include("init.php");
echo"welcome";
$stmt = $conn->prepare('SELECT items.*, categories.Name 
                                AS Category_Name, users.Username 
                                FROM items
                                INNER JOIN categories 
                                ON categories.ID = items.Cat_ID
                                INNER JOIN users 
                                ON users.UserID = items.Member_ID
                                ORDER BY Item_ID DESC'
        );
        $stmt->execute();
        $items = $stmt->fetchAll();
        foreach ($items as $item) {
            // echo $item['Item_ID'];
            echo'This is '. $item['Member_ID'] .'<br>';
        }
include($tpl . "footer.inc.php");
