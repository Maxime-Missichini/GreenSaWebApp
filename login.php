<!doctype html>
<?php include('server.php') ?>
<html class="no-js" lang="">

<div class="container">
  <div class="header">
    <h2>Register</h2>
  </div>

  <form action="registration.php" method="post">
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
