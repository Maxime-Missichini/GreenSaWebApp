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
    <button class="logout"><a href="index.php?logout='1'">Log out</a></button>
  </div>

  <div class="main_container">
    <form action="golfCreation.php" method="post" class="add_golf">
      <label>Create your golf from Google Maps : </label>
      <button type="submit" name="submit_golf_creation">Submit</button>

    </form>
    <form action="golf.php" method="post" enctype="multipart/form-data" class="add_golf">
      <label class="text_browse">Please select the golf (XML file) you want to add :</label>
      <input class="browse" type="file" id="file" name="file">
      <button class="browse" type="submit" name="submit_file">Submit</button>
    </form>

    <?php
    if(!isset($_FILES['file']['error'])){
    }

    if(isset($_POST['submit_file'])){
      $xml = simplexml_load_file($_FILES['file']['tmp_name']) or die("can't load xml");
      $golf_name = $xml->Name;
      $golf_nb_trou = $xml->NbTrous;

      for($i=1;$i<=$golf_nb_trou;$i++){
        $golf_coordinates_lat[$i] = $xml->Coordinates->Trou[$i-1]->lat;
        $golf_coordinates_lng[$i] = $xml->Coordinates->Trou[$i-1]->lng;
        $golf_coordinates_par[$i] = $xml->Coordinates->Trou[$i-1]->par;
      }
      $db = mysqli_connect('localhost','root','','demo') or die('Could not connect to the database');
      $query = mysqli_query($db,"SELECT idGolf FROM golf WHERE idGolf='$golf_name'");
      if(!empty($query)){
        echo "<label class=\"already\">This golf already exist</label>";
      }else{
        ?>

        <script>
          var todelete = document.getElementById('todelete');
          todelete.parentNode.removeChild(todelete);
        </script>

        <?php
        mysqli_query($db,"INSERT into golf (idGolf, nbTrou) VALUES ('$golf_name','$golf_nb_trou')");
        for($i=1;$i<=$golf_nb_trou;$i++) {
          mysqli_query($db, "INSERT into trougolf (idGolf,trou, par) VALUES ('$golf_name','$i','$golf_coordinates_par[$i]')");
        }
        header("Location: golf.php");
      }
    }


    ?>

    <?php

    $db = mysqli_connect('localhost','root','','demo') or die('Could not connect to the database');

    $idGolf_query = mysqli_query($db,"SELECT idGolf FROM golf");
    $idGolfs = mysqli_fetch_all($idGolf_query);

    foreach($idGolfs as $id){
      $nbTrou_query =  mysqli_query($db,"SELECT nbTrou FROM golf WHERE idGolf='$id[0]'");
      $nbTrou = mysqli_fetch_row($nbTrou_query);
      echo "
                        <div class=\"tabGolf\">
                            <label><b>Nom du Golf : </b>$id[0]</label>
                            <label><b>Nombre de trous : </b>$nbTrou[0]</label>
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
      echo "</tr></table></div>";
    }
    ?>

  </div>


</body>
