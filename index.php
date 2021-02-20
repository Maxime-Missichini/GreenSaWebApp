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
<body>
<header>
<link rel = "stylesheet" href = "./css/index.css"/>
</header>

<!-- Navigation -->
<ul>
  <li id="titre" style="float:left"><img src="./img/logo1.png" style="vertical-align:middle"/><?php if (isset($_SESSION['username'])) : ?>
		Welcome!  <?php echo $_SESSION['username']; ?> 
	<?php endif ?></li>
	
	<!-- The drop down user menu -->
  <div class="dropdown" style="float:right;">
    <li class="dropbtn"><img src="./img/user.png"/>
    <div class="dropdown-content">
      <a href="#">Modifier profit</a>
      <a href="#">Déconnexion</a>
    </div>
	</li>
  </div>
</ul>


<!-- The section of "Vous souhaitez" -->
<div class="frame">
	<h2 id="sous-titre"> Vous souhaitez : </h2>
	
	<div class="wrapper">	
	    <!-- The onclick function will be used to page jump --> 
		<div class="consultation" onclick="window.location.href= '#';return false">
			<img src="./img/consultation.png"/>
			<h3> <center>Consulter votre golf </center></h3>
		</div>
  
		<div class="modification" onclick="window.location.href= '#';return false">
			<img src="./img/modification.png"/>
		<h3><center> Modifier votre golf </center></h3>
		</div>  
	</div>
</div>

</html>

<!--
<!doctype html>

<html class="no-js" lang="">
<head>
  <title>Home page</title>
</head>

<body>
<h1>This is the homepage</h1>
?php
if(isset($_SESSION['success'])) :
  ?>
  <div>
    <h3>
      ?php
      echo $_SESSION['success'];
      unset($_SESSION['success']);
      ?>
    </h3>
  </div>
?php endif ?>

?php if (isset($_SESSION['username'])) : ?>
  <h3>
    Welcome <strong> <?php echo $_SESSION['username']; ?> </strong>
  </h3>
  <button><a href="index.php?logout='1'">logout</a></button>
?php endif ?>
</body>
</html>-->
