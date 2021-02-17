<!doctype html>

<?php include('server.php');

if(!isset($_SESSION['username'])){
  $_SESSION['msg'] = "You must login to view this page";
  header("Location: login.php");
}

if(isset($_GET['logout'])){
  session_destroy();
  unset($_SESSION['username']);
  header("Location: login.php");
}

?>

<html class="no-js" lang="">

<link rel = "stylesheet" href = "css/golf.css"/>

<head>
  <title>GreenSa - Vos golfs</title>
</head>

<body>

  <div class="header">
    <label class="text_header">Your Golfs</label>
    <button class="logout"><a href="index.php?logout='1'">Log out</button>
  </div>

  <div class="main_container">
    <div class="add_golf">
      <label>Please select the golf (XML file) you want to add</label>
      <label for="browse">Select a file:</label>
      <input type="file" id="browse" name="golf_file">

    </div>

    <?php

    $db = mysqli_connect('localhost','root','','demo') or die('Could not connect to the database');

    $idGolf_query = mysqli_query($db,"SELECT idGolf FROM golf");
    $idGolfs = mysqli_fetch_all($idGolf_query);

    foreach($idGolfs as $id){
      $nbTrou_query =  mysqli_query($db,"SELECT nbTrou FROM golf WHERE idGolf='$id[0]'");
      $nbTrou = mysqli_fetch_row($nbTrou_query);
      echo "
                        <div class=\"tabGolf\">
                            <label><b>Nom du Golf : </b><a>$id[0]</a></label>
                            <label><b>Nombre de trous : </b><a>$nbTrou[0]</a></label>
                            <table>
                                <tr>
                                    <th>Trou</th>
                    ";
      for ($i = 1; $i <= $nbTrou[0]; $i++) {
        echo "<td>$i</td>";
      }
      $par_query = mysqli_query($db,"SELECT par FROM trougolf WHERE idGolf = '$id[0]'");
      $pars = mysqli_fetch_all($par_query);
      echo "
                        </tr>
                        <tr>
                            <th>Par</th>";
      foreach($pars as $par){
        echo"<td>$par[0]</td>";
      }
      echo "</div>";
    }
    ?>

  </div>


</body>
