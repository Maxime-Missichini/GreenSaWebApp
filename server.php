<?php
if (!isset($_SESSION)) {
  session_start();
}

//init variables
$username = "";
$password = "";
$password1 = "";
$password2 = "";
$errors = array();

//connect

$db = mysqli_connect('localhost','root','','demo') or die('Could not connect to the database');

//REGISTER IF THE USER IF NOT LOGGED
if (!isset($_POST['log_usr'])) {
  if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
  }
  if (isset($_POST['password'])) {
    $password1 = mysqli_real_escape_string($db, $_POST['password']);
  }
  if (isset($_POST['confpassword'])) {
    $password2 = mysqli_real_escape_string($db, $_POST['confpassword']);
  }

//Validation, check errors

  if (empty($username)) {
    array_push($errors, "Username required");
  }
  if (empty($password1) or empty($password2)) {
    array_push($errors, "Both password required");
  }
  if ($password1 != $password2) {
    array_push($errors, "Passwords are different");
  }

//Check the db to know if the username exist

  $user_check_query = "SELECT * FROM user WHERE username = '$username'";

  $results = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($results);

  if ($user) {
    if ($user['username'] === $username) {
      array_push($errors, "Username already exist");
    }
  }

//Register the user

  if (count($errors) == 0) {

    $password = md5($password1); //encrypt
    $query = "INSERT INTO user (username,password) VALUES ('$username','$password')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "You are now logged in";

    header('Location: index.php'); //redirect

  }

}

//LOGIN

if(isset($_POST['log_usr'])){
  //Espace string to avoid SQL injections
  if (isset($_POST['username'])){$username = mysqli_real_escape_string($db,$_POST['username']);}
  if (isset($_POST['password'])){$password = mysqli_real_escape_string($db,$_POST['password']);}

  if(empty($username)){array_push($errors,"Username required");}
  if(empty($password)){array_push($errors,"Password required");}

  if(count($errors) == 0){

    //Hash the password for more security
    $password = md5($password);
    $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db,$query);

    //If we obtain results we can log the user
    if(mysqli_num_rows($results)){
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are logged in";
      header('Location: index.php');

    }else{
      array_push($errors,"Wrong username/password");
    }
  }
}




