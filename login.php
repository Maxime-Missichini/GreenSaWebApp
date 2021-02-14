<!doctype html>
<?php include('server.php') ?>
<html class="no-js" lang="">

<link rel = "stylesheet" href = "css/registration.css"/>

<div class="container">
  <div class="header">
    <h2 class="center">Log in</h2>
  </div>

  <form action="login.php" method="post" class="center">
    <div>
      <label for="username">Username</label>
      <input type="text" name="username" required>
    </div>

    <div>
      <label for="password">Password</label>
      <input type="text" name="password" required>
    </div>

    <button type="submit" name="log_usr"> Log in </button>
    <p>Not a user ? <a href="registration.php"><b>Register</b></a></p>
  </form>
</div>
