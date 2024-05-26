<?php 
session_start();
$pageTitle = "Categories";
include("init.php"); ?>

<div class="container">
    <h1 class="text-center">Show Categories</h1>
    <div class="row">
        <?php
        $items = getItems('Cat_ID', $_GET['pageid']);
        
        
            foreach ($items as $item) {
                $stmt = $conn->prepare('SELECT * FROM users WHERE UserID = ?');
                $stmt->execute([$item['Member_ID']]);
                $user = $stmt->fetch();
            if($user['RegStatus'] === 1){
        ?>
            <div class="col-sm-6 col-md-3">
                <div class="thumbnail item-box">
                    <span class="price-tag"><?php echo $item['Price'] ?></span>
                    <img src="pair-trainers.jpg" alt="" class="img-responsive">
                    <div class="caption">
                        <h3><a href='items.php?itemid=<?php echo $item['Item_ID'] ?>'><?php echo $item['Name']?></a></h3>
                        <p><?php echo $item['Description'] ?> </p>
                        <span class="date"><?php echo $user['Username']?></span>
                        <span class="date"><?php echo $item['Add_Date']?></span>
                    </div>
                </div>
            </div>
            <?php }
         } ?>
        
    </div>
</div>

<?php include($tpl . "footer.inc.php");
