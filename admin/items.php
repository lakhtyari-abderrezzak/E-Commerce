<?php

session_start();

$pageTitle = 'Items';
if (isset($_SESSION['Username'])) {
    include("init.php");
    isset($_GET['do']) ? $do = $_GET['do'] : $do = 'Manage';

    if ($do == 'Manage') {

        $stmt = $conn->prepare('SELECT items.*, categories.Name AS Category_Name, users.Username FROM items
        INNER JOIN categories ON categories.ID = items.Cat_ID
        INNER JOIN users ON users.UserID = items.Member_ID'
        );
        $stmt->execute();
        $items = $stmt->fetchAll();
        $message = "<div class='record-message'>No Records Found</div>";
        if(!empty($items)) {
        ?>

        <h1 class="text-center edit-members">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table  text-center table table-bordered">
                    <tr>
                        <th>#id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Registerd Date</th>
                        <th>Category</th>
                        <th>Username</th>
                        <th>Control</th>
                    </tr>
                    <?php


                    foreach ($items as $item) {
                        echo '<tr>';
                        echo '<td>' . $item['Item_ID'] . '</td>';
                        echo '<td>' . $item['Name'] . '</td>';
                        echo '<td class="text-wrap" >' . $item['Description'] . '</td>';
                        echo '<td>' . $item['Price'] . '</td>';
                        echo '<td>' . $item['Add_Date'] . '</td>';
                        echo '<td>' . $item['Category_Name'] . '</td>';
                        echo '<td>' . $item['Username'] . '</td>';

                        echo '<td>';
                        echo '<a class="btn btn-success" href="items.php?do=Edit&ItemID=' . $item['Item_ID'] . '">
                                        <i class="fa-solid fa-pen-fancy"></i>
                                        Edit
                                </a >';
                        echo '<a href="items.php?do=Delete&ItemID=' . $item['Item_ID'] . '" class="btn btn-danger confirm ">
                                        <i class="fa-solid fa-trash"></i> 
                                        Delete 
                                        </a>';
                        if ($item['Approve'] == 0) {
                            echo '<a href="items.php?do=Approve&ItemID=' . $item['Item_ID'] . '" class="btn btn-primary confirm ">
                                    <i class="fa-solid fa-check"></i>
                                     Approve 
                                    </a>';
                        }
                        ;
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>

                </table>
            </div>

            <a href="items.php?do=Add" class="btn btn-primary "> <i class="fa-solid fa-plus"></i> Add New Item</a>
        </div>
        
    <?php } else {
        echo $message;
    }

 } elseif ($do == 'Add') { ?>
        <h1 class="text-center edit-members">Add New Category</h1>
        <div class="container">
            <form action="?do=Insert" class="form-horizontal" method="POST">
                <!-- start name of Item  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Name</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" required="required" class="form-control"
                            placeholder="Enter Valid Item Name">
                    </div>
                </div>
                <!-- End name of Item  -->
                <input type="hidden" name="ItemId" value="<?php echo $row['ID'] ?>">
                <!-- Start Description of Item  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Description</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="description" required="required"
                            placeholder="Enter a Description for Your Product">
                    </div>
                </div>
                <!-- End Description of Item  -->
                <!-- Start Price of Item  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Price</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="price" required="required" placeholder="Price Here">
                    </div>
                </div>
                <!-- End Price of Item  -->
                <!-- Start Made In of Item  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Made In?</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="made" required="required" placeholder="Made In ..?">
                    </div>
                </div>
                <!-- End Made In of Item  -->
                <!-- Start Status In of Item  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Status</lable>
                    <div class="col-sm-10 col-md-6">
                        <select name="status" class="" id="">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>
                        </select>
                    </div>
                </div>
                <!-- End Status of Item  -->
                <!-- Start Users  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Members</lable>
                    <div class="col-sm-10 col-md-6">
                        <select name="members" class="" required='required'>
                            <option value="0">...</option>

                            <?php
                            $stmt = $conn->prepare('SELECT * FROM users WHERE GroupID = 0');
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach ($users as $user) {
                                echo '<option value="' . $user['UserID'] . '">' . $user['Username'] . '</option>';
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <!-- End Users -->
                <!-- Start Users  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Categories</lable>
                    <div class="col-sm-10 col-md-6">
                        <select name="categories" required='required'>
                            <option value="0">...</option>

                            <?php
                            $stmt = $conn->prepare('SELECT * FROM categories');
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach ($users as $user) {
                                echo '<option value="' . $user['ID'] . '">' . $user['Name'] . '</option>';
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <!-- End Users -->
                <!-- start Submit  -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-md-10">
                        <input type="submit" name="add" value="Add Item" class="btn btn-primary btn-sm text-center">
                    </div>
                </div>
            </form>
        </div>

        <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center edit-members">Insert Member</h1>';
            echo '<div class="container">';

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $made = $_POST['made'];
            $status = $_POST['status'];
            $categories = $_POST['categories'];
            $members = $_POST['members'];


            // Check If Any Of the Fields Are Empty
            $formErrors = [];
            if (empty($user)) {
                $formErrors[] = "Name Field Can't Be Left <strong>Empty</strong>";
            }
            if (empty($description)) {
                $formErrors[] = "Description Field Can't Be Left <strong>Empty</strong>";
            }
            if (empty($price)) {
                $formErrors[] = "Price Field Can't Be Left <strong>Empty</strong>";
            }
            if (empty($made)) {
                $formErrors[] = "Made In Field Can't Be Left <strong>Empty</strong>";
            }
            if (empty($status)) {
                $formErrors[] = "Status Field Can't Be Left <strong>Empty</strong>";
            }
            if (empty($members)) {
                $formErrors[] = "Members Field Can't Be Left <strong>Empty</strong>";
            }
            if (empty($categories)) {
                $formErrors[] = "Categories Field Can't Be Left <strong>Empty</strong>";
            }
            // Loop true FormErrors to Display Availible Errors
            foreach ($formErrors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";

            }

            if (empty($formErrors)) {

                $stmt = $conn->prepare('INSERT INTO items  (`Name`, `Description`, Price, Made_In, `Status`, Add_Date, Cat_ID, Member_ID)
                     VALUES (?,?,?,?,?,now(),?,?)');
                $stmt->execute(array($name, $description, $price, $made, $status, $categories, $members));
                $stmt->rowCount() > 0 ? $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record inserted </div>' :
                    $theMsg = '<div class="alert alert-dnager">' . $stmt->rowCount() . ' Record inserted </div>';
                redierctHome($theMsg);

            }
            echo '</div>';
        } else {
            $errorMsg = "<div class='alert alert-dnager'>You Can't Enter This page";
            redierctHome($theMsg);
        }
    } elseif ($do == 'Edit') {
        $itemID = isset($_GET['ItemID']) && is_numeric($_GET['ItemID']) ? intval($_GET['ItemID']) : 0;
        //check if user Exist in data base
        $stmt = $conn->prepare('SELECT * FROM items WHERE Item_ID = ?;');
        $stmt->execute(array($itemID));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center edit-members">Edit Category</h1>
            <div class="container">
                <form action="?do=Update" class="form-horizontal" method="POST">
                    <!-- start name of Item  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Name</lable>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" required="required" class="form-control"
                                placeholder="Enter Valid Item Name" value="<?php echo $row['Name'] ?>">
                        </div>
                    </div>
                    <!-- End name of Item  -->
                    <input type="hidden" name="ItemID" value="<?php echo $row['Item_ID'] ?>">
                    <!-- Start Description of Item  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Description</lable>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" required="required"
                                placeholder="Enter a Description for Your Product" value="<?php echo $row['Description'] ?>">
                        </div>
                    </div>
                    <!-- End Description of Item  -->
                    <!-- Start Price of Item  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Price</lable>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="price" required="required" placeholder="Price Here"
                                value="<?php echo $row['Price'] ?>">
                        </div>
                    </div>
                    <!-- End Price of Item  -->
                    <!-- Start Made In of Item  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Made In?</lable>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="made" required="required" placeholder="Made In ..?"
                                value="<?php echo $row['Made_In'] ?>">
                        </div>
                    </div>
                    <!-- End Made In of Item  -->
                    <!-- Start Status In of Item  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Status</lable>
                        <div class="col-sm-10 col-md-6">
                            <select name="status" class="" id="">
                                <option value="1" <?php if ($row['Status'] == 1) {
                                    echo 'selected';
                                } ?>>New</option>
                                <option value="2" <?php if ($row['Status'] == 2) {
                                    echo 'selected';
                                } ?>>Like New</option>
                                <option value="3" <?php if ($row['Status'] == 3) {
                                    echo 'selected';
                                } ?>>Used</option>
                                <option value="4" <?php if ($row['Status'] == 4) {
                                    echo 'selected';
                                } ?>>Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status of Item  -->
                    <!-- Start Users  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Members</lable>
                        <div class="col-sm-10 col-md-6">
                            <select name="members" class="" required='required'>
                                <?php
                                $stmt = $conn->prepare('SELECT * FROM users WHERE GroupID = 0');
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                    echo '<option value="' . $user['UserID'] . '"';
                                    if ($row['Member_ID'] == $user['UserID']) {
                                        echo 'selected';
                                    }
                                    echo '>' . $user['Username'] . '</option>';
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <!-- End Users -->
                    <!-- Start Users  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Categories</lable>
                        <div class="col-sm-10 col-md-6">
                            <select name="categories" required='required'>
                                <?php
                                $stmt = $conn->prepare('SELECT * FROM categories');
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                    // echo '<option value="' . $user['ID'] . '">' . $user['Name'] . '</option>';
                                    echo '<option value="' . $user['ID'] . '"';
                                    if ($row['Cat_ID'] == $user['ID']) {
                                        echo 'selected';
                                    }
                                    echo '>' . $user['Name'] . '</option>';
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <!-- End Users -->
                    <!-- start Submit  -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-md-10">
                            <input type="submit" name="edit" value="Save Changes" class="btn btn-primary btn-sm text-center">
                        </div>
                    </div>
                </form>
                <?php
                
                $stmt = $conn->prepare('SELECT comments.*, users.Username AS users_id
                                    FROM 
                                        comments
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = comments.user_id
                                    WHERE Item_ID = ?');
                $stmt->execute([$itemID]);
                $result = $stmt->fetchAll();
                if(! empty($result)) {
                ?>

                <h1 class="text-center edit-members">Manage [
                    <?php echo $row['Name'] ?>] Comments
                </h1>
                <div class="table-responsive">
                    <table class="main-table  text-center table table-bordered">
                        <tr>
                            <th>comment</th>
                            <th>User Name</th>
                            <th>Added Date</th>
                            <th>Control</th>
                        </tr>
                        <?php


                        foreach ($result as $row) {
                            echo '<tr>';
                            echo '<td>' . $row['Comment'] . '</td>';
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
                    <?php } ?>
                </div>
            </div>
            <?php
        } else {
            echo "<div class ='container'>";
            $editError = '<div class="alert alert-danger">No such ID was found</div>';
            redierctHome($editError, 4);
            echo "</div>";
        }
    } elseif ($do == 'Update') {
        echo '<h1 class="text-center edit-members">Update Member</h1>';
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['ItemID'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $made = $_POST['made'];
            $status = $_POST['status'];
            $members = $_POST['members'];
            $categories = $_POST['categories'];


            // Check If Any Of the Fields Are Empty
            $formErrors = [];
            if (empty($name)) {
                $formErrors[] = 'Item Name Can\'t be Left Empty ';
            }
            if (empty($description)) {
                $formErrors[] = 'Item description Can\'t be Left Empty ';
            }
            if (empty($price)) {
                $formErrors[] = 'Item price Can\'t be Left Empty ';
            }
            if (empty($made)) {
                $formErrors[] = 'Item Country  Can\'t be Left Empty ';
            }
            if (empty($status)) {
                $formErrors[] = 'Item status Can\'t be Left Empty ';
            }
            if (empty($members)) {
                $formErrors[] = 'Item Username Can\'t be Left Empty ';
            }
            if (empty($categories)) {
                $formErrors[] = 'Item categories Can\'t be Left Empty ';
            }

            // Loop true FormErrors to Display Availible Errors
            foreach ($formErrors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }

            if (empty($formErrors)) {

                $stmt = $conn->prepare('UPDATE 
                                                    items 
                                            SET     `Name` = ?, 
                                                    `Description` = ?,
                                                     Price = ?, 
                                                     Made_In = ?, 
                                                     `Status` = ?, 
                                                     Member_ID = ?, 
                                                     Cat_ID = ? 
                                            WHERE    Item_ID = ? 
                                            LIMIT 1;');
                $stmt->execute([$name, $description, $price, $made, $status, $members, $categories, $id]);
                $stmt->rowCount() > 0 ? $updateMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated</div>' :
                    $updateMsg = '<div class="alert alert-danger">' . $stmt->rowCount() . ' Record Updated</div>';

                redierctHome($updateMsg, 'back', 4);

            }
            echo '</div>';
        } else {
            $errorMsg = "<div class='alert alert-danger'>You Can't Enter Update Page This Way </div>";
            redierctHome($errorMsg, 'back', 4);
        }
    } elseif ($do == 'Delete') {

        echo '<h1 class="text-center">Delete Item</h1>
            <div class="container">';

        $itemID = isset($_GET['ItemID']) && is_numeric($_GET['ItemID']) ? intval($_GET['ItemID']) : 0;
        $check = checkUser('Item_ID', 'items', $itemID);

        if ($check == 1) {
            $stmt = $conn->prepare('DELETE FROM items WHERE Item_id = :xid');
            $stmt->bindParam(":xid", $itemID);
            $stmt->execute();

            $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Item Deleted. ID Number: ' . $itemID . '</div>';
            redierctHome($msg);
        } else {
            $msg = '<div class="alert alert-danger">You Can\'t Browse This Page This Way</div>';
            redierctHome($msg);
        }

        echo '</div>';

    } elseif ($do == 'Approve') {
        echo '<h1 class="text-center">Approve Item </h1>';
        echo '<div class="container">';

        $itemID = isset($_GET['ItemID']) && is_numeric($_GET['ItemID']) ? intval($_GET['ItemID']) : 0;
        $check = checkUser('Item_ID', 'items', $itemID);

        if ($check == 1) {
            $stmt = $conn->prepare('UPDATE items SET Approve = 1 WHERE Item_ID = ?');
            $stmt->execute([$itemID]);

            $msg = '<div class="alert alert-success" >' . $stmt->rowCount() . 'Item Was <b>Approved</b></div>';
            redierctHome($msg, 'back', 2);
        }

    }
    include($tpl . 'footer.inc.php');
} else {
    header('Location: index.php');
    exit();
}