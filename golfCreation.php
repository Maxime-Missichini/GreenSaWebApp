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

    document.cookie = escape(name) + "=" +
      escape(value) + expires + "; path=/demo";
  }
</script>

<?php

include('server.php');

$db = mysqli_connect('localhost', 'root', '', 'demo') or die('Could not connect to the database');

if(!isset($_SESSION['username'])){
  $_SESSION['msg'] = "You must login to view this page";
  header("Location: login.php");
}

if(isset($_GET['logout'])){
  session_destroy();
  unset($_SESSION['username']);
  header("Location: login.php");
}

if(isset($_GET['destroy'])){
  ?>
<script>
  sessionStorage.clear();
</script>
  <?php
  $trou = 1;
  setcookie('grnname',"",time()-3600,'/demo');
  setcookie('grnnb',"",time()-3600,'/demo');
  setcookie('grntrou',"",time()-3600,'/demo');
}
if ($_COOKIE['grntrou'] < $_COOKIE['grnnb']) {
  setcookie('grntrou', $_COOKIE['grntrou'] + 1);
}

?>

<!DOCTYPE html>
<html lang="">
<head>
  <title>GreenSa - Golf Creation</title>
  <link rel="stylesheet" href="css/golfCreationMap.css"/>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body>

<div class="header">
  <label class="text_header">Golf Creation</label>
  <button class="logout"><a href="index.php?logout='1'">Log out</a></button>
  <button class="backGolf"><a href="golf.php">Back to my golfs</a></button>
  <script>
    if(sessionStorage.getItem("clicked") === "1") {
      document.write('<form method="post" action="golfCreation.php"> <button class="createXML" type="submit" name="submit_xml">Create XML</button> </form>');
    }
  </script>
</div>

<div class="form-popup" id="nameForm">
  <form action="golfCreation.php" class="form-container" method="post">
    <h1>Golf name</h1>

    <label for="golfname"><b>Golf name</b></label>
    <input type="text" placeholder="Enter Name" name="golfname" id="golfname" required>
    <input type="text" placeholder="Number of holes" name="nbtrou" id="nbtrou" required>
    <button type="submit" name="submit_name">Submit</button>
  </form>
</div>

<script>
  function logSubmit(event) {
    if (document.getElementById("nbtrou").value === '9' || document.getElementById("nbtrou").value === '18') {
      createCookie("grnname", document.getElementById("golfname").value, "15");
      createCookie("grnnb", document.getElementById("nbtrou").value, "15")
      createCookie("grntrou", 0, "15")
    }
    else{
      event.preventDefault();
      alert('Veuillez rentrer un nombre de trous valide');
    }

  }
  document.getElementById("nameForm").addEventListener('submit', logSubmit);

  if(sessionStorage.getItem("clicked") !== "1") {
    document.getElementById("nameForm").style.display = "block";
  }
</script>


<div class="main_container" id="main_container">
  <div id="map"></div>
  <div class="info_container">
    <form action="golfCreation.php" method="post" autocomplete="off">
      <label>Par :
        <input type="text" name="par" id="par" required>
      </label>
      <button type="submit" name="submit_trou">Submit</button>
    </form>
    <button class="destroySession"><a href="golfCreation.php?destroy='1'">Reset</a></button>
  </div>

  <script>
    let marker;
    let lat = JSON.parse(sessionStorage.getItem("lat"));
    let lng = JSON.parse(sessionStorage.getItem("lng"));
    let zoom = JSON.parse(sessionStorage.getItem("zoom"));
    let coordinates;
    let map;

    if (!lat && !lng) {
      map = L.map('map').setView([0,0], 1);
    }

    if (lat && lng){
      map = L.map('map').setView([lat, lng], zoom);
    }

    L.tileLayer("https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}.jpg?key=fS5G4OHCW6VodEmO0UEM", {
      attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https:/'+
        '/www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
    }).addTo(map);


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
    if ($_COOKIE['grntrou'] <= 9){
      $latitude = 0;
      $longitude = 0;

      $golfname = $_COOKIE['grnname'];
      $searchname = "SELECT * FROM golf WHERE idGolf='$golfname'";
      $results = mysqli_query($db, $searchname);

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
      $query = "INSERT INTO trougolf (idGolf,trou,par,lat,lng) VALUES ('$golfname','$trou','$par','$latitude','$longitude')";
      mysqli_query($db, $query) or print "erreur";
    }else{
      echo "<script>console.log('Finished');</script>";
    }
  }

  if (isset($_POST['submit_xml'])){

    $xml = new SimpleXMLElement('<xml/>');
    $nbTrou = $_COOKIE['grntrou'];
    $nom = $_COOKIE['grnname'];
    $golfCourse = $xml->addChild('GolfCourse');
    $name = $golfCourse->addChild('Name',$nom);
    $nbTrous = $golfCourse->addChild('NbTrous',$nbTrou);
    $nomgolf = $golfCourse->addChild('NomGolf',$name);
    $coordinates = $golfCourse->addChild('Coordinates');

    for ($i=1;$i<=$nbTrou;$i++){
      $trou = $coordinates->addChild('Trou');

      $parquery = mysqli_query($db, "SELECT par FROM trougolf WHERE idGolf='$nom' AND trou='$i'");
      $pars = mysqli_fetch_row($parquery);

      $latsquery = mysqli_query($db, "SELECT lat FROM trougolf WHERE idGolf='$nom' AND trou='$i'");
      $lats = mysqli_fetch_row($latsquery);

      $lngquery = mysqli_query($db, "SELECT lng FROM trougolf WHERE idGolf='$nom' AND trou='$i'");
      $lngs = mysqli_fetch_row($lngquery);
      $test = strval($pars[0]);
      echo "<script> console.log('$test'); </script>";
      $par = $trou->addChild('par',strval($pars[0]));
      $lat = $trou->addChild('lat',strval($lats[0]));
      $lng = $trou->addChild('lng',strval($lngs[0]));

    }
    $filename = 'golf.xml';
    $xml->saveXML($filename);
    echo "<label><a href='golf.xml'>Here is your file</a></label>";
  }
?>


</div>

<script>
  if(sessionStorage.getItem("clicked") !== "1") {
    document.getElementById("main_container").style.display = "none";
  }
</script>
</body>

</html>

