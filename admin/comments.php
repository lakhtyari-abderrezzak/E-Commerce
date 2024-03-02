<?php
session_start();
$pageTitle = "Comments";
if (isset($_SESSION["Username"])) {
    include("init.php");

    isset($_GET['do']) ? $do = $_GET['do'] : $do = 'Manage';

    if ($do == 'Manage') { // Manage Page 
        $query = '';
        $stmt = $conn->prepare('SELECT comments.*, items.Name AS item_name, users.Username AS users_id
        FROM 
            comments
        INNER JOIN 
            items
        ON
            items.Item_ID = comments.item_id
        INNER JOIN
            users
        ON
            users.UserID = comments.user_id
        ');
        $stmt->execute();
        $result = $stmt->fetchAll();
        ?>

        <h1 class="text-center edit-members">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table  text-center table table-bordered">
                    <tr>
                        <th>#id</th>
                        <th>comment</th>
                        <th>Item Name </th>
                        <th>User Name</th>
                        <th>Added Date</th>
                        <th>Control</th>
                    </tr>
                    <?php


                    foreach ($result as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['c_id'] . '</td>';
                        echo '<td>' . $row['Comment'] . '</td>';
                        echo '<td>' . $row['item_name'] . '</td>';
                        echo '<td>' . $row['users_id'] . '</td>';
                        echo '<td>' . $row['Comment_Date'] . '</td>';
                        echo '<td> 
                                <a class="btn btn-success" href="comments.php?do=Edit&comid=' . $row['c_id'] . '"><i class="fa-solid fa-pen-fancy"></i> Edit</a >
                                <a href="comments.php?do=Delete&comid=' . $row['c_id'] . '" class="btn btn-danger confirm "><i class="fa-solid fa-trash"></i> Delete </a> ';
                        if ($row['Status'] == 0) {
                            echo '<a href="comments.php?do=Approve&comid=' . $row['c_id'] . '" class="btn btn-warning "><i class="fa-solid fa-xmark"></i> Approve </a>';
                        }
                        echo '</td>';
                        echo '</tr>';

                    }
                    ?>

                </table>
            </div>

        </div>

    <?php } elseif ($do == 'Edit') {

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        //check if user Exist in data base
        $stmt = $conn->prepare('SELECT * FROM comments WHERE c_id = ?  LIMIT 1;');
        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center edit-members">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $row['c_id'] ?>">
                    <!-- Start Username Field  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Comment</lable>
                        <div class="col-sm-10 col-md-6">
                            <textarea name="comments" class="form-control"><?php echo $row['Comment'] ?></textarea>
                        </div>
                    </div>

                    <!-- Start Username Field  -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-md-10 ">
                            <input type="submit" name="submit" value="Save" class="btn btn-primary btn-lg text-center">
                        </div>
                    </div>
                    <!-- End Username Field  -->

                </form>
            </div>
            <?php
        } else {

            $editError = '<div class="alert alert-danger">No such ID was found</div>';
            redierctHome($editError, 4);

        }

    } elseif ($do == 'Update') {

        echo '<h1 class="text-center edit-members">Update comment</h1>';
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $comid = $_POST['comid'];
            $comments = $_POST['comments'];


            $stmt = $conn->prepare('UPDATE comments SET Comment = ? WHERE c_id = ?;');
            $stmt->execute(array($comments, $comid));

            $stmt->rowCount() > 0 ? $updateMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated</div>' :
                $updateMsg = '<div class="alert alert-danger">' . $stmt->rowCount() . ' Record Updated</div>';

            redierctHome($updateMsg);


            echo '</div>';


        } else {
            $errorMsg = "<div class='alert alert-danger'>You Can't Enter Update Page This Way </div>";
            redierctHome($errorMsg, 'back', 4);
        }

    } elseif ($do == 'Delete') {
        echo '<h1 class="text-center edit-members">Delete Comment</h1>';
        echo '<div class="container">';

        $comID = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        //check if user Exist in data base
        $check = checkUser('c_id', 'comments', $comID);
        if ($check > 0) {
            $stmt = $conn->prepare('DELETE FROM comments WHERE c_id = :xid');
            $stmt->bindParam(':xid', $comID);
            $stmt->execute();

            $msg = '<div class="alert alert-success">User #ID ' . $comID . ' Was Deleted Successfully</div>';
            redierctHome($msg, 'back');
        } else {
            $errorMsg = '<div class="alert alert-danger">There Is No Such ID</div>';
            redierctHome($errorMsg);
        }
        echo '</div>';
    } elseif ($do == 'Approve') {
        echo '<h1 class="text-center edit-members">Approve Comment</h1>';
        echo '<div class="container">';

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        //check if user Exist in data base
        $check = checkUser('c_id', 'comments', $comid);
        if ($check > 0) {
            $stmt = $conn->prepare('UPDATE comments SET `Status` = 1 WHERE c_id = ?');
            $stmt->execute(array($comid));

            $msg = '<div class="alert alert-success">User #ID ' . $comid . ' Was Activated Successfully</div>';
            redierctHome($msg, 'back');
        } else {
            $errorMsg = '<div class="alert alert-danger">You Can\'t Enter This Page From The Url</div>';
            redierctHome($errorMsg);
        }
        echo '</div>';
    }

    include($tpl . "footer.inc.php");
} else {
    header("Location : index.php");
    exit();
}