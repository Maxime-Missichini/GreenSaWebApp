<!doctype html>
<html class="no-js" lang="">
<meta charset="utf-8">

<?php include('server.php') ?>

<link rel = "stylesheet" href = "css/regandlog.css"/>

<head>
  <title>Green'Sa - Inscription</title>
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

   <!-- Register form -->
    <div class="main_container">
      <form action="registration.php" method="post" class="credentials">

        <div class="user_container">
          <label class="label_cred" for="username">Nom d'utilisateur</label>
          <input class="inputs" type="text" name="username" required>
        </div>

        <div class="password_container">
          <label class="label_cred" for="password">Mot de passe</label>
          <input class="inputs" type="password" name="password" required>
        </div>

        <div class="password_container">
          <label class="label_cred" for="confpassword">Confirmation</label>
          <input class="inputs" type="password" name="confpassword" required>
        </div>

        <div class="log_button">
         <button type="submit" name="reg_usr">S'enregistrer</button>
        </div>

        <div class="register"> Déjà inscrit ?
          <a href="login.php">
            <b>Se connecter</b>
          </a>
        </div>
      </form>
    </div>

</body>

</html>
