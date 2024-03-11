<?php
session_start();
$pageTitle = "Login";

if (isset($_SESSION['user'])) {
    header('Location: index.php');
}
include 'init.php';

// checking if user is coming from http post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $_POST['user'];
    $pass = $_POST['password'];
    $hashedPass = sha1($pass);

    //check if user Exist in data base
    $stmt = $conn->prepare('SELECT UserID, Username , Password FROM users WHERE Username = ? AND Password = ?;');
    $stmt->execute(array($user, $hashedPass));
    $count = $stmt->rowCount();


    //if count > 0 this means the database contains some Elememts 
    if ($count > 0) {
        $_SESSION["user"] = $user; // Registered Session Name 
        header("Location: index.php"); // Redirected To index.php
        exit();
    } 

}
?>

<div class="container login-page">
    <h1 class="text-center"><span data-class="login" class="selected">Login</span> | <span
            data-class="signup">Signup</span></h1>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login">
        <div class="input-container">
            <input name="user" type="text" class="form-control" autocomplete="off" placeholder="Enter Your User Name"
                required="required">
        </div>
        <div class="input-container">
            <input name="password" type="password" class="form-control" autocomplete="new-password"
                placeholder="Enter a Valid Password" required="required">
        </div>

        <input type="submit" class="btn btn-primary" value="Login">
    </form>
    <form action="" class="signup">
        <div class="input-container">
            <input name="username" type="text" class="form-control" autocomplete="off" required="required"
                placeholder="Enter Your User Name">
        </div>
        <div class="input-container">
            <input name="email" type="email" class="form-control" autocomplete="new-password" required="required"
                placeholder="Enter a Valid Email">
        </div>
        <div class="input-container">
            <input name="passwor" type="password" class="form-control" autocomplete="new-password" required="required"
                placeholder="Enter a Valid Password">
        </div>

        <div class="input-container">
            <input name="password-repeat" type="password" class="form-control" autocomplete="new-password"
                required="required" placeholder="Enter password Again">
        </div>
        <input type="submit" class="btn btn-success" value="Signup">
    </form>
</div>

<?php
include $tpl . 'footer.inc.php';
?>