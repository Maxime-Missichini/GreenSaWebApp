<!doctype html>

<?php include('server.php');

//Check if the user is logged, if not, he's redirected
if(!isset($_SESSION['username'])){
  $_SESSION['msg'] = "You must login to view this page";
  header("Location: login.php");
}

//If the user wants to logout, we redirect him and clear session variables
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
    <!-- ? after the page send information after the = to the GET method -->
    <button class="logout"><a href="index.php?logout='1'">Log out</a></button>
  </div>

  <div class="main_container">
    <!-- Two forms, one to modify golfs and one to view them (might be useless to have2 -->
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
    //Not used currently
    if(!isset($_FILES['file']['error'])){
    }

    //Submit a XML file to create a golf automatically (in the database)
    //Weird syntax so go check simpleXML
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

      //Check if it already exist, then don't create it
      if(mysqli_num_rows($query) != 0){
        echo "<label class=\"already\">This golf already exist</label>";
      }else{
        ?>

        <script>
          //Delete the "this golf already exist" label
          var todelete = document.getElementById('todelete');
          todelete.parentNode.removeChild(todelete);
        </script>

        <?php

        //Add everything to the database
        mysqli_query($db,"INSERT into golf (idGolf, nbTrou) VALUES ('$golf_name','$golf_nb_trou')");
        for($i=1;$i<=$golf_nb_trou;$i++) {
          mysqli_query($db, "INSERT into trougolf (idGolf,trou, par, lat, lng) VALUES ('$golf_name','$i','$golf_coordinates_par[$i]','$golf_coordinates_lat[$i]','$golf_coordinates_lng[$i]')");
        }
        //Redirect to the same page to show the new golf
        header("Location: golf.php");
      }
    }


    ?>

    <?php

    //Show the golf automatically using the database
    //Weird syntax again, check mysqli doc

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
