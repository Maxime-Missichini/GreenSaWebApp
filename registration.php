<!doctype html>
<html class="no-js" lang="">
<meta charset="utf-8">
<?php include('server.php') ?>

<link rel = "stylesheet"
      type = "text/css"
      href = "css/registration.css" />

<div class="container">
    <div class="header">
      <h2 class="center">Register</h2>
    </div>

    <form action="registration.php" method="post" class="center">
      <div>
        <label for="username">Username</label>
        <input type="text" name="username" required>
      </div>

      <div>
        <label for="password">Password</label>
        <input type="text" name="password" required>
      </div>

      <div>
        <label for="confpassword">Confirm password</label>
        <input type="text" name="confpassword" required>
      </div>

      <button type="submit" name="reg_usr">Register</button>
      <p> Already a user ?
        <a href="login.php">
          <b>Log in</b>
        </a>
      </p>
    </form>


</div>

<!-- Recommended -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
