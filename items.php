<?php
ob_start();
session_start();

$pageTitle = 'Items';
include('init.php');

//Check if We Have item id in The Link and is It numeric Then then Get It intvalue
$itemId = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
// Use The Int Value to Get Itmes From Data Base 
$stmt = $conn->prepare("SELECT items.*, categories.Name 
                            , users.Username
                            FROM items
                            INNER JOIN categories 
                            ON categories.ID = items.Cat_ID
                            INNER JOIN users 
                            ON users.UserID = items.Member_ID
                            WHERE 
                                Item_ID = ?");
//link the id from the link with the id in DB
$stmt->execute(array($itemId));
//fetch data
if($stmt->rowCount() > 0){
    $item = $stmt->fetch();


?>
<h1 class="text-center"><?php echo $item['Name']; ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img src="pair-trainers.jpg" class="img-responsive img-thumbnail" alt="<?php $item['Name'] ?> ">
        </div>
        <div class="col-md-9">
            <h2><?php echo $item['Name'] ?></h2>
            <p><?php echo $item['Description'] ?></p>
            <span class="date"><?php echo $item['Add_Date'] ?></span>
            <p><?php echo "Made In: " . $item['Made_In'] ?></p>
            <div><?php echo "Price: $" . $item['Price'] ?></div>
            <div><?php echo "Added By: " . $item['Username'] ?></div>
            <div><?php echo "Category: " . $item['Name'] ?></div>

        </div>
    </div>
</div>
<?php 
}else{
    $msg = "<div class='alert alert-danger text-center'> ID: <b>" . $_GET["itemid"] . "</b>  Doesn't Exist</div>";
    echo "<div class='container mt-2'>" ;
            redierctHome($msg,"back") ;
    echo "</div>";
   
}
include($tpl . "footer.inc.php");
ob_end_flush();