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
                        <p> Full Name :
                            <?php echo $info['FullName'] ?>
                        </p>
                        <p> User Name :
                            <?php echo $info['Username'] ?>
                        </p>
                        <p> Email :
                            <?php echo $info['Email'] ?>
                        </p>
                        <p> Registration Date :
                            <?php echo $info['Date'] ?>
                        </p>
                        <p> Favorite Category : </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="ads block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Ads</div>
                    <div class="panel-body">
                        <div class="row">
                            <?php
                            $items = getItems('Member_ID', $info['UserID']);
                            foreach ($items as $item) {
                                print <<<HTML
                                            <div class="col-sm-6 col-md-3">
                                                <div class="thumbnail item-box">
                                                    <span class="price-tag">$item[Price]</span>
                                                    <img src="pair-trainers.jpg" alt="" class="img-responsive">
                                                    <div class="caption">
                                                        <h3>$item[Name]</h3>
                                                        <p>$item[Description]</p>
                                                    </div>
                                                </div>
                                            </div>
                                        HTML;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="comments block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Comments</div>
                    <div class="panel-body">
                        <div class="row">
                            <?php
                            $stmt = $conn->prepare("SELECT Comment FROM comments WHERE user_id = ?");
                            $stmt->execute(array($info['UserID']));
                            $comments = $stmt->fetchAll();
                            foreach($comments as $comment) {
                                echo '<p>' . $comment['Comment'] . '</p>';
                            }

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