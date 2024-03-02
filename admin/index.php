<?php
session_start();
$noNavbar = "";
$pageTitle = "Login";
if (isset($_SESSION["Username"])) {
    header("Location: dashboard.php"); // Redirected To Dashboard
}
include("init.php");


// checking if user is coming from http post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password);

    //check if user Exist in data base
    $stmt = $conn->prepare('SELECT UserID, Username , Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1;');
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();


    //if count > 0 this means the database contains some Elememts 
    if ($count > 0) {
        $_SESSION["Username"] = $username; // Registered Session Name 
        $_SESSION["ID"] = $row['UserID']; // Registered Session UserID 
        header("Location: dashboard.php"); // Redirected To Dashboard
        exit();
    } else {
    }

}

?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Log In</h4>
    <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off">
    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" value="Log In">
</form>

<?php
include($tpl . "footer.inc.php");
?>