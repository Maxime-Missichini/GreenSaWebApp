<!doctype html>
<html class="no-js" lang="">
<meta charset="utf-8">

<?php include('server.php') ?>

<link rel = "stylesheet" href = "css/regandlog.css"/>

<div class="container">
    <div class="header">
      <label class="text_header">Register</label>
    </div>

 <!-- Register form -->
    <form action="registration.php" method="post" class="credentials">

      <div class="user">
        <label class="label_cred" for="username">Username</label>
        <input type="text" name="username" required>
      </div>

      <div class="user">
        <label class="label_cred" for="password">Password</label>
        <input type="password" name="password" required>
      </div>

      <div class="user">
        <label class="label_cred" for="confpassword">Confirm password</label>
        <input type="password" name="confpassword" required>
      </div>

      <button type="submit" name="reg_usr">Register</button>
      <p> Already a user ?
        <a href="login.php">
          <b>Log in</b>
        </a>
      </p>
    </form>


</div>

</html>
