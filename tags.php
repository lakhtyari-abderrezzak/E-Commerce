<?php 
$pageTitle = "Tags";
include("init.php"); ?>

<div class="container">
    <div class="row">
<?php
    if(isset($_GET['name'])){
        $tag = $_GET['name'];
        echo '<h1 class="text-center">' .  $tag . '</h1>';
        $allTags = getAllFromAnyTable("*", "items", "where tags like '%$tag%'", "Item_ID" );
        foreach ($allTags as $tags) {
            // if($tags['RegStatus'] === 1 && $tags['Allow_Ads'] === 1){
            ?>
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail item-box">
                        <span class="price-tag"><?php echo $tags['Price'] ?></span>
                        <img src="pair-trainers.jpg" alt="" class="img-responsive">
                        <div class="caption">
                            <h3><a href='items.php?itemid=<?php echo $tags['Item_ID'] ?>'><?php echo $tags['Name']?></a></h3>
                            <p><?php echo $tags['Description'] ?> </p>
                            <span class="small "><?php echo $tags['Add_Date']?></span>
                            
                        </div>
                    </div>
                </div>
                <?php
            // }
         } 
    }
?>    
</div>    
</div>

<?php include($tpl . "footer.inc.php");