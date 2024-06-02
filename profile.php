<?php

session_start();

$pageTitle = "Profile";

include("init.php");
$stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
$stmt->execute([$sessionUser]);
$info = $stmt->fetch();
$userid = $info['UserID'];

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
                    <a href="#"class="btn btn-primary" ><i class="fa-solid fa-edit" > </i> Edit Info</a>
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
                            $items = getAllFromAnyTable("*", "Items", "WHERE Member_ID = $userid", "Item_ID", "DESC");
                            if(!empty ($items)){
                                echo "<div class='row'>";
                                foreach ($items as $item) {?>
                                    
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="thumbnail item-box <?php 
                                                            if($item['Approve'] == 0){
                                                                echo'not-approved';
                                                            }
                                                                ?>">
                                                        <span class="price-tag">$<?php echo $item['Price']; ?></span>
                                                        <img src="pair-trainers.jpg" alt="" class="img-responsive">
                                                        <div class="caption">
                                                            <h3><a 
                                                            href='items.php?itemid=<?php echo $item['Item_ID']; ?>'><?php echo $item['Name'];?></a>
                                                            </h3>
                                                            <p><?php echo $item['Description']; ?></p>
                                                            <div  class="date"><?php echo $item['Add_Date']; ?></div>
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                            
                               <?php }
                                
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
                            $comments = getAllFromAnyTable("Comment", "comments", "WHERE user_id = $userid", "c_id");
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