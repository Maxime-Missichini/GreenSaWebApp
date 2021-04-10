<?php

include('server.php');

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
}
?>


<!DOCTYPE html>
<html lang="">

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
      escape(value) + expires + "; path=/";
  }
</script>

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
  <button class="destroySession"><a href="golfCreation.php?destroy='1'">Destroy session</a></button>
  <button class="backGolf"><a href="golf.php">Back to my golfs</a></button>
</div>

<div class="form-popup" id="nameForm">
  <form action="golfCreation.php" class="form-container" method="post">
    <h1>Golf name</h1>

    <label for="golfname"><b>Password</b></label>
    <input type="text" placeholder="Enter Name" name="golfname" id="golfname" required>
    <button type="submit" name="submit_name">Submit</button>
  </form>
</div>

<script>
  function logSubmit() {
    createCookie("grnname", document.getElementById("golfname").value, "15");
    console.log('cookie cree');
  }
  document.getElementById("nameForm").addEventListener('submit', logSubmit);

  if(sessionStorage.getItem("clicked") !== "1") {
    createCookie("grntrou", 1, "10");
    console.log("eheh");
    document.getElementById("nameForm").style.display = "block";
    document.getElementById("main_container").style.display = "none";
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
  //SUBMIT TROU
  if(isset($_POST['submit_name'])){
    ?>

    <script>
      sessionStorage.setItem("clicked",1);
      console.log("bam");
      document.getElementById("nameForm").style.display = "none";
      document.getElementById("main_container").style.display = "block";

    </script>

    <?php
  }
  if (isset($_POST['submit_trou'])){

      $latitude = 0;
      $longitude = 0;

      $db = mysqli_connect('localhost', 'root', '', 'demo') or die('Could not connect to the database');
      $golfname = $_COOKIE['grnname'];
      $searchname = "SELECT * FROM golf WHERE idGolf='$golfname'";
      $results = mysqli_query($db, $searchname);

      if(mysqli_num_rows($results) === 0){
        $nbTrou = 9;
        $query = "INSERT INTO golf (idGolf,nbTrou) VALUES ('$golfname','$nbTrou')";
        echo "ah";
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
      setcookie('grntrou',$trou+1);
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

