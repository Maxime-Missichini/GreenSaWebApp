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

<html class="no-js" lang="" xmlns="">
<body>
<header>
<link rel = "stylesheet" href = "./css/index.css"/>
</header>

<!-- Navigation -->
<div class="header">

  <div class="logo_section">
    <img class="logo_image" src="./img/Logo1.png" alt="GreenSa logo"/>
    <?php if (isset($_SESSION['username'])) : ?>
		<label class="text_header"> Welcome!  <?php echo $_SESSION['username']; ?></label>
    <?php endif ?>
    <!-- The drop down user menu -->
    <div class="dropdown">
      <div class="dropbtn">
        <img id="user_image" class="user_image" src="./img/user.png" alt="User logo"/>
        <div class="dropdown-content">
          <a href="profil.php">Modifier profil</a>
          <a href="index.php?logout='1'">Déconnexion</a>
        </div>
      </div>
    </div>
  </div>



</div>


<!-- The section of "Vous souhaitez" -->
<div class="frame">
	<h2 id="sous-titre"> Vous souhaitez : </h2>

	<div class="wrapper">
	    <!-- The onclick function will be used to page jump -->
		<div class="consultation" onclick="window.location.href= 'golf.php'">
			<img src="./img/consultation.PNG" alt=""/>
			<h3>Consulter votre golf </h3>
		</div>

		<div class="modification" onclick="window.location.href= 'golf.php'">
			<img src="./img/modification.PNG" alt=""/>
		<h3> Modifier votre golf </h3>
		</div>

	</div>
</div>

</html>
