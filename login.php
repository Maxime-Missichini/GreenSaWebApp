<!doctype html>
<?php include('server.php') ?>
<html class="no-js" lang="">

<link rel = "stylesheet" href = "css/regandlog.css"/>

<div class="container">
  <div class="header">
    <label class="text_header">Welcome</label>
  </div>

  <form action="login.php" method="post" class="credentials">
  <div class="frame">
    <div class="user">
      <label class="label_cred" for="username">Username </label>
      <input type="text" name="username" required>
    </div>

    <div class="user">
      <label class = "label_cred" for="password">Password </label>
      <input type="password" name="password" required>
    </div>

    <button class ="center" type="submit" name="log_usr" > Log in </button>

    <p>Not a user ? <a href="registration.php"><b>Register</b></a></p>
  </div>
  </form>
</div>
