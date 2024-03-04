<?php
session_start();
$pageTitle = "Members";
if (isset($_SESSION["Username"])) {
    include("init.php");

    isset($_GET['do']) ? $do = $_GET['do'] : $do = 'Manage';

    if ($do == 'Manage') { // Manage Page 
        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $stmt = $conn->prepare('SELECT * FROM users WHERE GroupID != 1 AND RegStatus = 0');
        } else {
            $stmt = $conn->prepare('SELECT * FROM users WHERE GroupID != 1');
        }
        $stmt->execute();
        $result = $stmt->fetchAll();
        $message = "<div class='record-message'>No Records Found</div>";
        if (!empty($result)) {
            ?>

            <h1 class="text-center edit-members">Manage Member</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table  text-center table table-bordered">
                        <tr>
                            <th>#id</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Registerd Date</th>
                            <th>Control</th>
                        </tr>
                        <?php


                        foreach ($result as $row) {
                            echo '<tr>';
                            echo '<td>' . $row['UserID'] . '</td>';
                            echo '<td>' . $row['Username'] . '</td>';
                            echo '<td>' . $row['Email'] . '</td>';
                            echo '<td>' . $row['FullName'] . '</td>';
                            echo '<td>' . $row['Date'] . '</td>';
                            echo '<td> 
                                <a class="btn btn-success" href="members.php?do=Edit&UserID=' . $row['UserID'] . '"><i class="fa-solid fa-pen-fancy"></i> Edit</a >
                                <a href="members.php?do=Delete&UserID=' . $row['UserID'] . '" class="btn btn-danger confirm "><i class="fa-solid fa-trash"></i> Delete </a> ';
                            if ($row['RegStatus'] == 1) {
                                echo '<a href="members.php?do=Disactivate&UserID=' . $row['UserID'] . '" class="btn btn-warning "><i class="fa-solid fa-xmark"></i> Disactivate </a>';
                            } else if ($row['RegStatus'] == 0) {
                                echo '<a href="members.php?do=Activate&UserID=' . $row['UserID'] . '" class="btn btn-info "><i class="fa-brands fa-creative-commons-by"></i> Activate</a >';
                            }
                            echo '</td>';
                            echo '</tr>';

                        }
                        ?>

                    </table>
                </div>

                <a href="members.php?do=Add" class="btn btn-primary "> <i class="fa-solid fa-plus"></i> Add New Member</a>
            </div>

        <?php } else {
            echo $message;
        }
    } elseif ($do == "Add") { // Add New Member             ?>

        <h1 class="text-center edit-members">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start Username Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Username</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="username" class="form-control" placeholder="Enter User Name"
                            required='required' autocomplete="off">
                    </div>
                </div>
                <!-- End Username Field  -->
                <input type="hidden" name="userID" value="<?php echo $userID ?>">
                <!-- Start Password Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Password</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" name="password" class="pwd form-control" autocomplete="new-passowrd"
                            placeholder="Password" required="required">
                        <i class="show-pass fa-solid fa-eye"></i>
                    </div>
                </div>
                <!-- End Password Field  -->
                <!-- Start Email Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Email</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" class="form-control" placeholder="Enter Valid Email"
                            required='required' autocomplete="off">
                    </div>
                </div>
                <!-- End Email Field  -->
                <!-- Start Full Name Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Fullname</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="fullname" class="form-control" placeholder="Full Name" required='required'
                            autocomplete="off">
                    </div>
                </div>
                <!-- End Full Name Field  -->
                <!-- Start Submit Button   -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-md-10 ">
                        <input type="submit" name="add" value="Add Member" class="btn btn-primary btn-lg text-center">
                    </div>
                </div>
                <!-- End Submit Button  -->

            </form>
        </div>
    <?php } elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center edit-members">Update Member</h1>';
            echo '<div class="container">';

            $user = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['fullname'];

            $hashedPass = SHA1($password);

            // Check If Any Of the Fields Are Empty
            $formErrors = [];
            if (empty($user)) {
                $formErrors[] = "User Field Can't Be Left Empty";
            } elseif (strlen($user) < 4) {
                $formErrors[] = "User Can't Be Less Than 4 Caracters";
            } elseif (strlen($user) > 20) {
                $formErrors[] = "User Can't Be More Than 20 Caracters";
            }
            if (empty($password)) {
                $formErrors[] = "Password Field Can't Be Left Empty";
            }
            if (empty($email)) {
                $formErrors[] = "Email Field Can't Be Left Empty";
            }
            if (empty($name)) {
                $formErrors[] = "Fullname Field Can't Be Left Empty";
            }

            // Loop true FormErrors to Display Availible Errors
            foreach ($formErrors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }

            if (empty($formErrors)) {
                $value = $user;
                $check = checkUser('Username', 'users', $value);
                if ($check == 1) {
                    $errMsg = '<div class="alert alert-danger"> User Already Exists In Data Base</div>';
                    redierctHome($errMsg, 'back');
                } else {
                    $stmt = $conn->prepare('INSERT INTO users  (Username, Password, Email, FullName,RegStatus, Date)
                     VALUES (?,?,?,?,1,now()) ');
                    $stmt->execute(array($user, $hashedPass, $email, $name));
                    $stmt->rowCount() > 0 ? $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record inserted </div>' :
                        $theMsg = '<div class="alert alert-dnager">' . $stmt->rowCount() . ' Record inserted </div>';
                    redierctHome($theMsg);
                }
            }
            echo '</div>';


        } else {
            $errorMsg = "<div class='alert alert-dnager'>You Can't Enter This page";
            redierctHome($theMsg);
        }
    } elseif ($do == 'Edit') {

        $userID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
        //check if user Exist in data base
        $stmt = $conn->prepare('SELECT * FROM users WHERE UserID = ?  LIMIT 1;');
        $stmt->execute(array($userID));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center edit-members">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $row['UserID'] ?>">
                    <!-- Start Username Field  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Username</lable>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>"
                                required='required' autocomplete="off">
                        </div>
                    </div>
                    <!-- End Username Field  -->
                    <input type="hidden" name="userID" value="<?php echo $userID ?>">
                    <!-- Start Username Field  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Password</lable>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                            <input type="password" name="newpassword" class="form-control"
                                placeholder="Leave Blank If You don't Want To Change Password">
                        </div>
                    </div>
                    <!-- End Username Field  -->
                    <!-- Start Username Field  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Email</lable>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>"
                                required='required' autocomplete="off">
                        </div>
                    </div>
                    <!-- End Username Field  -->
                    <!-- Start Username Field  -->
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Fullname</lable>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="fullname" class="form-control" value="<?php echo $row['FullName'] ?>"
                                required='required' autocomplete="off">
                        </div>
                    </div>
                    <!-- End Username Field  -->
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

        echo '<h1 class="text-center edit-members">Update Member</h1>';
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['userID'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['fullname'];
            $pass = (empty($_POST['newpassword'])) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            // Check If Any Of the Fields Are Empty
            $formErrors = [];
            if (empty($user)) {
                $formErrors[] = "User Field Can't Be Left Empty";
            } elseif (strlen($user) < 4) {
                $formErrors[] = "User Can't Be Less Than 4 Caracters";
            } elseif (strlen($user) > 20) {
                $formErrors[] = "User Can't Be More Than 20 Caracters";
            }
            if (empty($email)) {
                $formErrors[] = "Email Field Can't Be Left Empty";
            }
            if (empty($name)) {
                $formErrors[] = "Fullname Field Can't Be Left Empty";
            }

            // Loop true FormErrors to Display Availible Errors
            foreach ($formErrors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }

            if (empty($formErrors)) {

                $stmt = $conn->prepare('UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ? LIMIT 1;');
                $stmt->execute(array($user, $email, $name, $pass, $id));

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
        echo '<h1 class="text-center edit-members">Delete Member</h1>';
        echo '<div class="container">';

        $userID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
        //check if user Exist in data base
        $check = checkUser('UserId', 'users', $userID);
        if ($check > 0) {
            $stmt = $conn->prepare('DELETE FROM users WHERE UserID = :xuser');
            $stmt->bindParam(':xuser', $userID);
            $stmt->execute();

            $msg = '<div class="alert alert-success">User #ID ' . $userID . ' Was Deleted Successfully</div>';
            redierctHome($msg, 'back');
        } else {
            $errorMsg = '<div class="alert alert-danger">You Can\'t Enter This Page From The Url</div>';
            redierctHome($errorMsg);
        }
        echo '</div>';
    } elseif ($do == 'Activate') {
        echo '<h1 class="text-center edit-members">Activate Member</h1>';
        echo '<div class="container">';

        $userID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
        //check if user Exist in data base
        $check = checkUser('UserId', 'users', $userID);
        if ($check > 0) {
            $stmt = $conn->prepare('UPDATE users SET RegStatus = 1 WHERE UserID = ?');
            $stmt->execute(array($userID));

            $msg = '<div class="alert alert-success">User #ID ' . $userID . ' Was Activated Successfully</div>';
            redierctHome($msg, 'back');
        } else {
            $errorMsg = '<div class="alert alert-danger">You Can\'t Enter This Page From The Url</div>';
            redierctHome($errorMsg);
        }
        echo '</div>';
    } elseif ($do == 'Disactivate') {
        echo '<h1 class="text-center edit-members">Disactivate Member</h1>';
        echo '<div class="container">';

        $userID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
        //check if user Exist in data base
        $check = checkUser('UserId', 'users', $userID);
        if ($check > 0) {
            $stmt = $conn->prepare('UPDATE users SET RegStatus = 0 WHERE UserID = ?');
            $stmt->execute(array($userID));

            $msg = '<div class="alert alert-success">User #ID ' . $userID . ' Was Disactivated Successfully</div>';
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