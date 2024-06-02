<?php 
session_start();
$pageTitle = "Categories";
include("init.php"); ?>

<div class="container">
    <h1 class="text-center">
        <?php
        if(isset($_GET['pageid'])) {
            $stmt = $conn->prepare("SELECT * FROM categories WHERE ID = ?");
            $stmt->execute([$_GET['pageid']]);
            $row = $stmt->fetch();
            echo $row['Name'];
        }else{
            echo 'Categories';
        }
        ?>
    </h1>
    <div class="row">
        <?php
        $stmt = $conn->prepare("SELECT items.*, users.RegStatus, users.Username
                                FROM items 
                                INNER JOIN users
                                ON users.UserID = items.Member_ID
                                WHERE Cat_ID = ?
                                AND Approve = 1
                                ");
        $stmt->execute([$_GET['pageid']]);
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
                            <span class="date"><?php echo $item['Username']?></span>
                            <span class="small "><?php echo $item['Add_Date']?></span>
                            
                        </div>
                    </div>
                </div>
                <?php 
            }
         } ?>
        
    </div>
</div>

<?php include($tpl . "footer.inc.php");
