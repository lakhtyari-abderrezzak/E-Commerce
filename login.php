<?php
include 'init.php';
?>

<div class="container login-page">
    <h1 class="text-center"><span data-class="login" class="selected">Login</span> | <span data-class="signup">Signup</span></h1>
    <form action="" class="login">
        <div class="input-container">
        <input name="user" type="text" class="form-control" 
        autocomplete="off" placeholder="Enter Your User Name" required="required">
        </div>
        <input name="password" type="password" class="form-control" 
        autocomplete="new-password" placeholder="Enter a Valid Password">
        <input type="submit" class="btn btn-primary" value="Login">
    </form>
    <form action="" class="signup">
        <input name="username" type="text" class="form-control" autocomplete="off" placeholder="Enter Your User Name">
        <input name="email" type="email" class="form-control" autocomplete="new-password" placeholder="Enter a Valid Email">
        <input name="passwor" type="password" class="form-control" autocomplete="new-password" placeholder="Enter a Valid Password">
        <input name="password-repeat" type="password" class="form-control" autocomplete="new-password" placeholder="Enter password Again">
        <input type="submit" class="btn btn-success" value="Signup">
    </form>
</div>

<?php
include $tpl . 'footer.inc.php';
?>