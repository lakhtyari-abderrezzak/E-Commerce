<?php
ob_start();
session_start();
$pageTitle = "Home Page";
include("init.php");
?>
<div class="container m-5">
    <h1 class="text-center">All Categories</h1>
    <div class="row">
        <?php
        $stmt = $conn->prepare("SELECT items.*, users.RegStatus, users.Username
                                FROM items 
                                INNER JOIN users
                                ON users.UserID = items.Member_ID
                                WHERE Approve = 1
                                ORDER BY Item_ID ASC");
        $stmt->execute();
        $allItems = $stmt->fetchAll();
            foreach ($allItems as $item) {
             if($item['RegStatus'] === 1){
        ?>
            <div class="col-sm-6 col-md-3">
                <div class="thumbnail item-box">
                    <span class="price-tag"><?php echo $item['Price'] ?></span>
                    <img src="pair-trainers.jpg" alt="" class="img-responsive">
                    <div class="caption">
                        <h3><a href='items.php?itemid=<?php echo $item['Item_ID'] ?>'><?php echo $item['Name']?></a></h3>
                        <p><?php echo $item['Description'] ?> </p>
                        <span class="date pull-left"><?php echo $item['Username']?></span>
                        <span class="date pull-right"><?php echo $item['Add_Date']?></span>
                    </div>
                </div>
            </div>
            <?php }
         } ?>
        
    </div>
</div>

<?php
include($tpl . "footer.inc.php");
ob_end_flush();
