<!doctype html>
<?php include('server.php') ?>
<html class="no-js" lang="fr">

<link rel = "stylesheet" href = "css/regandlog.css"/>

<head>
  <title>Green'Sa - Connexion</title>
  <link rel="icon" href="./img/Logo1.png">
</head>

<body>
  <header>
    <div class="logo">
      <img src="./img/Logo1.png" alt="Logo"/>
      <a>Green'Sa</a>
    </div>
    <div class="text_header">
      <label class="header_text">Bienvenue</label>
    </div>
  </header>

  <div class="main_container">

    <!-- Form for the login part -->
    <form action="login.php" method="post" class="credentials">

      <div class="user_container">
        <label class="label_cred" for="username">Nom d'utilisateur</label>
        <input class="inputs" type="text" name="username" required>
      </div>

      <div class="password_container">
        <label class = "label_cred" for="password">Mot de passe</label>
        <input class="inputs" type="password" name="password" required>
      </div>

      <div class="log_button">
        <button type="submit" name="log_usr" >Se connecter</button>
      </div>

      <div class="register">
        Pas encore inscrit ? <a href="registration.php"><b>S'enregistrer</b></a>
      </div>

    </form>

  </div>

</body>
