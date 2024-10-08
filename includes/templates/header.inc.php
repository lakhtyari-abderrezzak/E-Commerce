<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link href="<?php echo $css ?>tagsinput.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css">
  <link rel="stylesheet" href="<?php echo $css ?>frontend.css">
  <title>
    <?php getTitle() ?>
  </title>
</head>

<body>
  <div class="upper-bar">
    <div class="container">
      <?php
      if(isset($_SESSION['user'])){?>
        <img src="./profile.jpg" alt="" class="my-image img-thumbnail img-circle">
      <div class="btn-group">
        <span class="btn btn-default btn-dropdown-toggle" data-toggle="dropdown">
          <?php echo $sessionUser ?>
          <span class="caret"></span>
        </span>
        <ul class="dropdown-menu">
          <li><a href="profile.php">Profile</a></li>
          <li><a href="newad.php">New Item</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <div class="pull-right">
        <?php 
          $status = checkUserStatus($_SESSION['user']);
          if($status == 1){
            echo '<p class="unactive" title="No Ads Will Show For Users From This Profile "><i class="fa-solid fa-user-slash"></i> Profile Not Active</p>';
          }
        ?>
      </div>
      <?php
      } else {
        ?>
        <span class="pull-right">
          <a href="login.php">Login/Signup</a>
        </span>
      <?php } ?>
    </div>
  </div>
  <nav class="navbar navbar-inverse">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav"
          aria-expanded="false">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Home Page</a>
      </div>
      <div class="collapse navbar-collapse" id="app-nav">
        <ul class="nav navbar-nav navbar-right">
          <?php
          // Show Categories That Are Visible 
          $cats = getAllFromAnyTable("*", "categories","WHERE parent = 0 AND visibility = 1 AND Allow_Ads = 1","ID","ASC");
          foreach ($cats as $cat) {
            echo '<li>
                    <a href="Categories.php?pageid=' . $cat['ID'] . '">'
                    . $cat['Name'] . 
                    '</a>
                  </li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
</body>