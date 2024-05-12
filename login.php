<?php
session_start();
$pageTitle = "Login";

if (isset($_SESSION['user'])) {
    header('Location: index.php');
}
include 'init.php';

// checking if user is coming from http post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
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
    } else {
        $formErrors = [];

        if (isset($_POST['username'])) {
            $filteredUser = html_entity_decode($_POST['username']);
            
            if(strlen($filteredUser) < 4){
                $formErrors[] = "User Must be More Then 4 Characters";
            }
        }
        if (isset($_POST['email'])) {
            $filteredEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            
            if(filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true){
                $formErrors[] = "Enter A Valid Email";
            }
        }
        if (isset($_POST['password']) && isset($_POST['password2'])){
            $password1 = sha1($_POST['password']);
            $password2 = sha1($_POST['password2']);
            if($password1 !== $password2){
                $formErrors[] = "Passwords Doesn't match ";
            }
        }
        if(empty($formErrors)){
            $count = checkUser("Username", "users", $_POST['username']);
            if($count > 0 ){
                $formErrors[] = "This User Name <b> " . $_POST['username'] . " </b> Already Exits";
            }else{
                $stmt = $conn->prepare("INSERT INTO 
                                        users(Username,FullName, Email, `Password`, RegStatus, `Date`)
                                        VALUES(?,?,?,?,0,now());  ");
                $stmt->execute(array(
                    $_POST['username'], $_POST['fullname'], $_POST['email'], sha1($_POST['password'])
                ));
                
                $successMessage = "";
            }
        }
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
                placeholder="Enter a Valid Password">
        </div>

        <input type="submit" class="btn btn-primary" name="login" value="Login">
    </form>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="signup">
        <div class="input-container">
            <input pattern=".{4,}" title="User Must be More then 4 Characters"  name="username" type="text" class="form-control" autocomplete="off"
                placeholder="Enter Your User Name" required>
        </div>
 
        <div class="input-container">
            <input name="fullname" type="text" class="form-control" autocomplete="off" placeholder="Enter Your Full Name"
                required="required">
        </div>
        <div class="input-container">
            <input name="email" type="email" class="form-control" autocomplete="new-password"
                placeholder="Enter a Valid Email" required>
        </div>
        <div class="input-container">
            <input name="password" type="password" class="form-control" autocomplete="new-password"
                placeholder="Enter a Valid Password" required minlength="4">
        </div>

        <div class="input-container">
            <input name="password2" type="password" class="form-control" autocomplete="new-password"
                placeholder="Enter password Again" minlength="4">
        </div>
        <input type="submit" class="btn btn-success" name="singup" value="Signup">
        
    </form>
    <?php 
    if(!empty($formErrors)){
        foreach($formErrors as $error){
                    echo "<p class='text-center alert alert-danger mt-4'>" . $error . "</p>";
            }
    }
    if (isset($successMessage)){
        echo "<p class='text-center alert alert-success mt-4'> <b>" 
        . $_POST['fullname'] . 
        "</b> Is Successfully Registered</p>";
    }
    ?>
</div>

<?php
include $tpl . 'footer.inc.php';
?>