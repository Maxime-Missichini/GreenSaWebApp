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
</div>

<div class="main_container">
  <div id="map"></div>
  <script>

    let map = L.map('map').setView([0,0],1);
    L.tileLayer("https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}.jpg?key=fS5G4OHCW6VodEmO0UEM", {
      attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https:/'+
        '/www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
    }).addTo(map);

    let marker;
    let coordinates;
    map.on('click',function(e){
      coordinates = e.latlng;
      latitude = e.lat;
      longitude = e.lng
      if (typeof marker != "undefined"){
        map.removeLayer(marker);
      }
      marker = new L.Marker(e.latlng);
      map.addLayer(marker);
      marker.bindPopup('Im a marker');
    })

  </script>
  <div class="info_container">
    <label for="par">Par :</label>
    <input type="text" name="par" required>
    <button type="submit" name="submit_trou">Submit</button>
  </div>
<?php
  //SUBMIT TROU
  if (isset($_POST['submit_trou'])){
    $query = "INSERT INTO trougolf (lat,lng) VALUES ('json.encode','')";
  }

?>
  }
</div>
</body>

</html>

