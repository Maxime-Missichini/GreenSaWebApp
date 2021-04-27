<?php

include('server.php');

//Check if the user is logged, if not, he's redirected
if(!isset($_SESSION['username'])){
  $_SESSION['msg'] = "You must login to view this page";
  header("Location: login.php");
}

//If the user wants to logout, we redirect him and clear session variables
if(isset($_GET['logout'])){
  session_destroy();
  unset($_SESSION['username']);
  header("Location: login.php");
}

?>

<!doctype html>

<html class="no-js" lang="" xmlns="">

<head>
  <title>Green'Sa - Accueil</title>
  <link rel="icon" href="./img/Logo1.png">
  <link rel = "stylesheet" href = "./css/index.css"/>
</head>

<body>

<!-- Navigation -->
<header>

  <div class="logo_section">

    <div class="logo">
      <img src="./img/Logo1.png" alt="Logo"/>
      <a>Green'Sa</a>
    </div>

    <!-- Show username using php if logged -->
    <?php if (isset($_SESSION['username'])) : ?>
    <div class="text_header">
		  <label class="header_text"> Bonjour  <?php echo $_SESSION['username']; ?> !</label>
    </div>
    <?php endif ?>

    <!-- The drop down user menu -->
    <div class="dropbtn">
      <img id="user_image" class="user_image" src="./img/user.png" alt="User logo"/>
      <div class="dropdown-content">
        <a href="profil.php">Modifier profil</a>
        <a href="index.php?logout='1'">Déconnexion</a>
      </div>
    </div>
  </div>

</header>


<!-- The section of "Vous souhaitez" -->

<div class="main_container">

	<h2 id="sous-titre"> Vous souhaitez : </h2>

  <!-- The onclick function will be used to page jump -->
  <div class="consultation" onclick="window.location.href= 'golf.php'">
    <img src="./img/consultation.PNG" alt=""/>
    <h3>Consulter vos golf </h3>
  </div>

  <div class="modification" onclick="window.location.href= 'golf.php'">
    <img src="./img/modification.PNG" alt=""/>
		<h3> Modifier vos golf </h3>
  </div>

</div>

</html>
