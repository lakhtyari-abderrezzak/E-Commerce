<?php
session_start();
if (isset($_SESSION["Username"])) {
    $pageTitle = "Dashboard";
    include("init.php");

    $num = 5; // Variable Used For Limt And for Number of User In The Panel
    $latestUsers = getLatest('*', 'users', 'UserID', $num); // Our Connection With data base To Bring Back The Users
    $latestItems = getLatest('*', 'items', 'Item_ID', $num); // Our Connection With data base To Bring Back The Items
    $stmt = $conn->prepare('SELECT comments.*, users.Username AS users_id
                                    FROM 
                                        comments
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = comments.user_id');
    $stmt->execute();
    $latetsComments = $stmt->fetchAll();

    ?>
    <div class="container dash-stats text-center">
        <h1 class="text-center">Dashboard</h1>
        <div class="row">
            <div class="col-md-3">

                <div class="stats st-members">
                    <i class="fa-solid fa-user"></i>
                    <div class="info">
                        Total Users
                        <span>
                            <?php echo "<a href='members.php'>" . countItems('UserID', 'users') . "</a>" ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats st-pending">
                    <i class="fa-solid fa-user-secret"></i>
                    <div class="info">
                        Pending Members
                        <span>
                            <a href='members.php?do=Manage&page=Pending'>
                                <?php echo checkUser('RegStatus', 'users', 0) ?>
                            </a>
                        </span>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="stats st-items">
                    <i class="fa-solid fa-shop"></i>
                    <div class="info">Total Items
                        <span>
                            <?php echo "<a href='items.php'>" . countItems('Item_ID', 'Items') . "</a>" ?>
                        </span>
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="stats st-comments">
                    <i class="fa-solid fa-comment"></i>
                    <div class="info">
                        Total Comments
                        <span>
                            <?php echo "<a href='comments.php'>" . countItems('c_id', 'comments') . "</a>" ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa-solid fa-users"></i>
                            <?php echo $num; ?> Last Registered Users
                            <div class="pull-right panel-info">
                                <i class="fa-solid fa-sort-up fa-lg"></i>
                            </div>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-user">
                                <?php
                                foreach ($latestUsers as $user) {
                                    $id = $user['UserID'];
                                    echo <<<HTML
                                        <li class="">
                                            $user[Username]
                                            <a href="members.php?do=Edit&UserID=$id">
                                                <span class="btn btn-success pull-right">
                                                    <i class="fa-solid fa-edit"></i> 
                                                    Edit
                                                </span>
                                            </a>
                                        </li>
                                     HTML;
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa-solid fa-tag"></i>
                            <?php echo $num; ?> Last Items
                            <div class="pull-right panel-info">
                                <i class="fa-solid fa-sort-up fa-lg"></i>
                            </div>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-user">
                                <?php
                                foreach ($latestItems as $item) {
                                    $id = $item['Item_ID'];
                                    echo <<<HTML
                                        <li class="">
                                            $item[Name]
                                            <a href="items.php?do=Edit&ItemID=$id">
                                                <span class="btn btn-success pull-right">
                                                    <i class="fa-solid fa-edit"></i> 
                                                    Edit
                                                </span>
                                            </a>
                                        </li>
                                     HTML;
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa-solid fa-comment"></i>
                            Latest Comments
                            <div class="pull-right panel-info">
                                <i class="fa-solid fa-sort-up fa-lg"></i>
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php
                            foreach ($latetsComments as $comment) {
                                $id = $comment['c_id'];
                                echo <<<HTML
                                        <div class="comments">
                                            <span class="user-comment">$comment[users_id]</span>
                                            <p class="cmnt"> $comment[Comment]</p>
                                        </div>
                                        <!-- <span class="pull-right btn btn-success">
                                            <a href='comments.php?do=Edit&comid=$id'>
                                                Edit
                                            </a>
                                        </span> -->
                                     HTML;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include($tpl . "footer.inc.php");
} else {
    header("Location : index.php");
    exit();
}