<?php include('server.php');

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

<!DOCTYPE html>
<html lang="">



<head>
  <title>GreenSa - Golf Creation</title>
</head>

<body>
<div class="header">
  <label class="text_header">Golf Creation</label>
  <button class="logout"><a href="index.php?logout='1'">Log out</a></button>
</div>


</body>

</html>

