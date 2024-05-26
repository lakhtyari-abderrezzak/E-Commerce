<?php
ob_start();
session_start();

$pageTitle = 'Items';
include('init.php');

//Check if We Have item id in The Link and is It numeric Then then Get It intvalue
$itemId = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
// Use The Int Value to Get Itmes From Data Base 
$stmt = $conn->prepare("SELECT items.*, categories.Name As catName
                            , users.Username
                            FROM items
                            INNER JOIN categories 
                            ON categories.ID = items.Cat_ID
                            INNER JOIN users 
                            ON users.UserID = items.Member_ID
                            WHERE 
                                Item_ID = ?
                            AND 
                                Approve = 1");
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
        <div class="col-md-9 show-item">
            <h2><?php echo $item['Name'] ?></h2>
            <p><?php echo $item['Description'] ?></p>
            <ul class="list-unstyled">
                <li class="date">
                    <i class="fa-regular fa-calendar-days"></i>
                    <span>Added Date</span>: <?php echo $item['Add_Date'] ?>
                </li>
                <li>
                    <i class="fa-solid fa-globe"></i>
                    <span>Made In</span>: <?php echo $item['Made_In'] ?>
                </li>
                <li> 
                    <i class="fa-solid fa-barcode"></i>
                    <span>Price</span>: <?php echo $item['Price'] ?>
                </li>
                <li> 
                    <i class="fa-regular fa-user"></i>
                    <span>Added By</span>: <?php echo $item['Username'] ?>
                </li>
                <li>
                    <i class="fa-solid fa-list"></i>
                    <span>Category</span>: <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['catName'] ?></a>
                </li>
            </ul>
        </div>
    </div>
    <hr class="custum">
    <?php if(isset($_SESSION['user'])){ ?>
    <div class="row">
        <div class="col-md-offset-3">
            <div class="add-comment">
                <h3>Add Comment</h3>
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                    <textarea name="comment" class="comment" required></textarea>
                    <input type="submit" class="btn btn-primary" value="submit" id="">
                </form>
            </div>
        </div>
        <?PHP 
        if($_SERVER['REQUEST_METHOD' ] == "POST") {
            $comment = $_POST['comment'] ;
            $comment_sanitized = html_entity_decode($comment);
            $item_id = $item['Item_ID'];
            $user_id = $_SESSION['uid'];
            
            if( !empty($comment)){

                $stmt = $conn->prepare('INSERT INTO comments(Comment, `Status`, Comment_Date, Item_id, user_id )
                VALUES(?, 0, NOW(), ?, ?);');
                $stmt->execute(array($comment_sanitized, $item_id, $user_id));

                if($stmt){
                    echo '<div class="alert alert-success text-center"> Comment Added Successfully</div>';
                }
            }else{
                    echo '<div class="m-4 alert alert-danger text-center"> Can\'t Leave Comment Empty</div>';
            }
        }
        ?>
    </div>
    <?php }else{
        echo "<p><a href='login.php' >Login/Signup</a> To Add a Comment</p>";
    } ?>
    <hr class="custum">
    <?php 
                $stmt = $conn->prepare('SELECT 
                                        comments.*, users.UserName AS Member
                                        FROM 
                                        comments
                                        INNER JOIN
                                        users 
                                        ON
                                        users.UserID = comments.user_id
                                        WHERE Item_ID = ?
                                        AND `Status` = 1
                                        ORDER BY 
                                        c_id DESC ;');
                $stmt->execute(array($itemId));
                $comments = $stmt->fetchAll();
         
                foreach ($comments as $comment){ ?>
                <div class="comment-box">
                    <div class="row">
                        <div class="col-sm-2">
                            <img class="img-responsive img-circle img-thumbnail" src="profile.jpg">
                            <p class="text-center"> <?php echo $comment['Member']  ?></p>
                        </div>
                        <div class="col-sm-10">
                            <p class="lead"><?php echo $comment['Comment'] ?></p>
                        </div>
                    </div>
                </div>
                <hr class="custum">
                <?php } ?>
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