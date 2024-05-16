<?php

session_start();

$pageTitle = "Profile";

include("init.php");

$getUser = $conn->prepare("SELECT * FROM users WHERE Username = ?");
$getUser->execute([$sessionUser]);
$info = $getUser->fetch();

if (isset($_SESSION['user'])) {
    ?>
    <div class="profil">
        <h1 class="text-center">My Profile</h1>
        <div class="info block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Info</div>
                    <div class="panel-body">
                        <ul class="list-unstyled">
                        <li> 
                            <i class="fa-solid fa-user"></i>
                            <span>Full Name</span> :
                            <?php echo $info['FullName'] ?>
                        </li>
                        <li>
                            <i class="fa-solid fa-id-card"></i>
                            <span>User Name</span> :
                            <?php echo $info['Username'] ?>
                        </li>
                        <li> 
                            <i class="fa-solid fa-envelope"></i>
                            <span>Email</span> :
                            <?php echo $info['Email'] ?>
                        </li>
                        <li> 
                            <i class="fa-regular fa-clock"></i>
                            <span>Registration Date</span> :
                            <?php echo $info['Date'] ?>
                        </li>
                        <li> 
                            <i class="fa-regular fa-star"></i>
                            <span>Favorite Category</span> :
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="ads block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Ads</div>
                    <div class="panel-body">
                            <?php
                            if(!empty (getItems('Member_ID', $info['UserID']))){
                                $items = getItems('Member_ID', $info['UserID']);
                        
                                echo "<div class='row'>";
                                foreach ($items as $item) {
                                    print <<<HTML
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="thumbnail item-box">
                                                        <span class="price-tag">$$item[Price]</span>
                                                        <img src="pair-trainers.jpg" alt="" class="img-responsive">
                                                        <div class="caption">
                                                            <h3><a 
                                                            href='items.php?itemid=$item[Item_ID]'>$item[Name]</a>
                                                            </h3>
                                                            <p>$item[Description]</p>
                                                            <div  class="date">$item[Add_Date]</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            HTML;
                                }
                                
                            }else{
                                echo "<p>There Are No Items To Show <a href='newad.php'>New Ad</a></p>";
                            }
                            echo '</div>';
                            ?>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="comments block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Comments</div>
                    <div class="panel-body">
                        
                            <?php
                            echo '<div class"row">';
                            $stmt = $conn->prepare("SELECT Comment FROM comments WHERE user_id = ?");
                            $stmt->execute(array($info['UserID']));
                            $comments = $stmt->fetchAll();
                            if(!empty ($comments)){
                                foreach($comments as $comment) {
                                    echo '<p>' . $comment['Comment'] . '</p>';
                                }
                            }else{
                                echo "<p>There Are No Comments To Show</p>";
                            }
                            echo '</div>';
                            

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    header("Location: login.php");
    exit();
}
include($tpl . "footer.inc.php");