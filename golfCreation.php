<script>

  // Function to create the cookie
  function createCookie(name, value, minutes) {
    var expires;

    if (minutes) {
      var date = new Date();
      date.setTime(date.getTime() + (minutes * 60 * 1000));
      expires = "; expires=" + date.toGMTString();
    } else {
      expires = "";
    }
    //Cookie creation, we modified the path to have the same as the whole code
    document.cookie = escape(name) + "=" +
      escape(value) + expires + "; path=/demo";
  }
</script>

<?php

include('server.php');

//Connect to the SQL database as root
$db = mysqli_connect('localhost', 'root', '', 'demo') or die('Could not connect to the database');

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

//If we want to reset cookies and sessionStorage, to create a new golf for example
if(isset($_GET['destroy'])){
  ?>
<script>
  sessionStorage.clear();
</script>
  <?php
  $trou = 1;
  //Set time to a previous date to instantly clear the cookie, don't forget the path to be homogenous
  setcookie('grnname',"",time()-3600,'/demo');
  setcookie('grnnb',"",time()-3600,'/demo');
  setcookie('grntrou',"",time()-3600,'/demo');
}

//If the cookie exist and if we're not greater than the total number of holes (9 or 18) we increment
if (isset($_COOKIE['grnnb'])) {
  if ($_COOKIE['grntrou'] < $_COOKIE['grnnb']) {
    setcookie('grntrou', $_COOKIE['grntrou'] + 1);
  }
}

?>

<!-- //////////////////////////////////// HTML BEGIN, NO MORE HEADERS NOW ////////////////////////////////////////// -->

<!DOCTYPE html>
<html lang="">
<!-- HEAD -->
<head>
  <title>GreenSa - Golf Creation</title>
  <link rel="stylesheet" href="css/golfCreationMap.css"/>
  <!-- link for the map -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<!-- BODY -->
<body>
<div class="header">
  <label class="text_header">Golf Creation</label>
  <!-- ? after the page send information after the = to the GET method -->
  <button class="logout"><a href="index.php?logout='1'">Log out</a></button>
  <button class="backGolf"><a href="golf.php">Back to my golfs</a></button>
  <script>
    <!-- If we already filed the golf name, we can now generate XML file with the golf inside -->
    if(sessionStorage.getItem("clicked") === "1") {
      document.write('<form method="post" action="golfCreation.php"> <button class="createXML" type="submit" name="submit_xml">Create XML</button> </form>');
    }
  </script>
</div>

<div class="form-popup" id="nameForm">
  <!-- Form for the golf name and the number of holes (9 or 18) -->
  <form action="golfCreation.php" class="form-container" method="post">
    <h1>Golf name</h1>

    <label for="golfname"><b>Golf name</b></label>
    <input type="text" placeholder="Enter Name" name="golfname" id="golfname" required>
    <!-- type number to prevent string inputs -->
    <input type="number" placeholder="Number of holes" name="nbtrou" id="nbtrou" required>
    <button type="submit" name="submit_name">Submit</button>
  </form>
</div>

<!-- Section to save the golf name before going to the next step, golf creation -->
<script>
  // This function  triggered by the submit from the golf name form
  function logSubmit(event) {
    <!-- We check if the number of holes is correct -->
    if (document.getElementById("nbtrou").value === '9' || document.getElementById("nbtrou").value === '18') {
      createCookie("grnname", document.getElementById("golfname").value, "15");
      createCookie("grnnb", document.getElementById("nbtrou").value, "15")
      createCookie("grntrou", 0, "15")
    }
    //else we stop the redirection and create a popup
    else{
      event.preventDefault();
      alert('Veuillez rentrer un nombre de trous valide');
    }

  }
  document.getElementById("nameForm").addEventListener('submit', logSubmit);

  //If the golf name form is not filled, we show it
  if(sessionStorage.getItem("clicked") !== "1") {
    document.getElementById("nameForm").style.display = "block";
  }
</script>

<!-- /////////////////////////////////////////////// MAIN CONTAINER //////////////////////////////////////////////// -->
<div class="main_container" id="main_container">
  <div id="map"></div>
  <div class="info_container">
    <form action="golfCreation.php" method="post" autocomplete="off">
      <label>Par :
        <!-- type number to prevent string inputs -->
        <input type="number" name="par" id="par" required>
      </label>
      <button type="submit" name="submit_trou">Submit</button>
    </form>
    <button class="destroySession"><a href="golfCreation.php?destroy='1'">Reset</a></button>
  </div>

  <!-- Map script, here we use some leaflet functions -->
  <script>
    let marker;
    //We parse to have the correct type automatically
    let lat = JSON.parse(sessionStorage.getItem("lat"));
    let lng = JSON.parse(sessionStorage.getItem("lng"));
    let zoom = JSON.parse(sessionStorage.getItem("zoom"));
    let coordinates;
    let map;

    //Default view : 0,0 with basic zoom
    if (!lat && !lng) {
      map = L.map('map').setView([0,0], 1);
    }

    //We memorize coordinates because when we redirect we lose the current view and we don't want to reset the view
    if (lat && lng){
      map = L.map('map').setView([lat, lng], zoom);
    }

    L.tileLayer("https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}.jpg?key=fS5G4OHCW6VodEmO0UEM", {
      attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https:/'+
        '/www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
    }).addTo(map);

    // Manage the markers and memorize their coordinates (and the zoom too)
    map.on('click',function(e){

      if (typeof marker != "undefined"){
        map.removeLayer(marker);
      }

      marker = new L.Marker(e.latlng);
      map.addLayer(marker);
      marker.bindPopup('Im a marker');
      coordinates = marker.getLatLng();
      zoom = map.getZoom();
      sessionStorage.setItem("zoom",zoom);
      sessionStorage.setItem("lat", coordinates.lat);
      sessionStorage.setItem("lng", coordinates.lng);
      createCookie("grnlat", coordinates.lat, "1");
      console.log(coordinates.lat);
      createCookie("grnlng", coordinates.lng, "1");
      console.log(coordinates.lng);

    })
    </script>

<?php
  //SUBMIT GOLF NAME
  if(isset($_POST['submit_name'])){

    $golfname = $_COOKIE['grnname'];
    $results = mysqli_query($db,"SELECT * FROM golf WHERE idGolf='$golfname'");

    //If the golf don't exist we can proceed to show the map and close the golf name form
    if(mysqli_num_rows($results) === 0){
    ?>

    <script>
      sessionStorage.setItem("clicked",'1');
      document.getElementById("nameForm").style.display = "none";
      document.getElementById("main_container").style.display = "block";
    </script>

    <?php
    }else{
      echo "<label>This golf already exist in your database</label>";
    }
  }

  //SUBMIT GOLF HOLE
  if (isset($_POST['submit_trou'])){
    //Don't add to database if we are already at the max hole number
    if ($_COOKIE['grntrou'] <= 9){
      $latitude = 0;
      $longitude = 0;
      $golfname = $_COOKIE['grnname'];
      $searchname = "SELECT * FROM golf WHERE idGolf='$golfname'";
      $results = mysqli_query($db, $searchname);

      //If the golf does not exist we add it to the database (user related)
      if(mysqli_num_rows($results) === 0){
        $nbTrou = $_COOKIE['grnnb'];
        $query = "INSERT INTO golf (idGolf,nbTrou) VALUES ('$golfname','$nbTrou')";
        mysqli_query($db,$query);
      }

      $trou = $_COOKIE['grntrou'];

      if (isset($_COOKIE['grnlat']) && isset($_COOKIE['grnlng'])) {
        $latitude = $_COOKIE['grnlat'];
        $longitude = $_COOKIE['grnlng'];
      }

      $par = $_POST['par'];
      //Insert all the values
      $query = "INSERT INTO trougolf (idGolf,trou,par,lat,lng) VALUES ('$golfname','$trou','$par','$latitude','$longitude')";
      mysqli_query($db, $query) or print "erreur";
    }else{
      //Currently we just print in the console
      echo "<script>console.log('Finished');</script>";
    }
  }

  //XML GENERATOR
  if (isset($_POST['submit_xml'])){

    $xml = new SimpleXMLElement('<xml/>');
    $nbTrou = $_COOKIE['grntrou'];
    $nom = $_COOKIE['grnname'];
    $golfCourse = $xml->addChild('GolfCourse');
    $name = $golfCourse->addChild('Name',$nom);
    $nbTrous = $golfCourse->addChild('NbTrous',$nbTrou);
    $nomgolf = $golfCourse->addChild('NomGolf',$name);
    $coordinates = $golfCourse->addChild('Coordinates');

    //Take all the data from the database
    for ($i=1;$i<=$nbTrou;$i++){
      $trou = $coordinates->addChild('Trou');

      $parquery = mysqli_query($db, "SELECT par FROM trougolf WHERE idGolf='$nom' AND trou='$i'");
      $pars = mysqli_fetch_row($parquery);

      $latsquery = mysqli_query($db, "SELECT lat FROM trougolf WHERE idGolf='$nom' AND trou='$i'");
      $lats = mysqli_fetch_row($latsquery);

      $lngquery = mysqli_query($db, "SELECT lng FROM trougolf WHERE idGolf='$nom' AND trou='$i'");
      $lngs = mysqli_fetch_row($lngquery);

      //Debug
      $test = strval($pars[0]);
      echo "<script> console.log('$test'); </script>";

      $par = $trou->addChild('par',strval($pars[0]));
      $lat = $trou->addChild('lat',strval($lats[0]));
      $lng = $trou->addChild('lng',strval($lngs[0]));

    }
    $filename = 'golf.xml';
    $xml->saveXML($filename);

    //Show a link to the XML file
    echo "<label><a href='golf.xml'>Here is your file</a></label>";
  }
?>


</div>

<script>
  //If the golf name form is not completed, we don't show the map, it's here because we need main_container to be defined
  if(sessionStorage.getItem("clicked") !== "1") {
    document.getElementById("main_container").style.display = "none";
  }
</script>
</body>

</html>

