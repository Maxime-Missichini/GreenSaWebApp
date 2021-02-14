<?php

include('server.php');

if(!isset($_SESSION['username'])){
  $_SESSION['msg'] = "You must login to view this page";
  header("Location: login.php");
}

if(isset($_GET['logout'])){
  session_destroy();
  unset($_SESSION['username']);
  header("Location: login.php");
}

?>


<!doctype html>

<html class="no-js" lang="">
<head>
  <title>Home page</title>
</head>

<body>
<h1>This is the homepage</h1>
<?php
if(isset($_SESSION['success'])) :
  ?>
  <div>
    <h3>
      <?php
      echo $_SESSION['success'];
      unset($_SESSION['success']);
      ?>
    </h3>
  </div>
<?php endif ?>

<?php if (isset($_SESSION['username'])) : ?>
  <h3>
    Welcome <strong> <?php echo $_SESSION['username']; ?> </strong>
  </h3>
  <button><a href="index.php?logout='1'">logout</a></button>
<?php endif ?>
</body>
</html>
