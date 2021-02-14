<?php

session_start();

//init variables
$username = "";
$password1 = "";
$password2 = "";
$errors = array();

//connect

$db = mysqli_connect('localhost','root','','demo') or die('Could not connect to the database');

//register

if (isset($_POST['username'])){$username = mysqli_real_escape_string($db,$_POST['username']);}
if (isset($_POST['password'])){$password1 = mysqli_real_escape_string($db,$_POST['password']);}
if (isset($_POST['confpassword'])){$password2 = mysqli_real_escape_string($db,$_POST['confpassword']);}

//validation

if(empty($username)) {array_push($errors,"Username required");}
if(empty($password1) or empty($password2)) {array_push($errors,"Both password required");}
if($password1 != $password2) {array_push($errors,"Passwords are different");}

//check the db

$user_check_query = "SELECT * FROM user WHERE username = '$username'";

$results = mysqli_query($db,$user_check_query);
$user = mysqli_fetch_assoc($results);

if ($user){
  if ($user['username'] === $username){
    array_push($errors,"Username already exist");
  }
}

//register the user

if(count($errors) == 0){

  $password = md5($password1); //encrypt
  $query = "INSERT INTO user (username,password) VALUES ('$username','$password')";
  mysqli_query($db,$query);
  $_SESSION['username'] = $username;
  $_SESSION['success'] = "You are now logged in";

  header('Location: ./index.php'); //redirect

}

?>
