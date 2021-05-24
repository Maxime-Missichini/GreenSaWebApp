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
$username=$_SESSION['username'];

?>

<html class="no-js" lang="fr">

<link rel = "stylesheet" href = "css/golf.css"/>

<head>
  <title>Green'Sa - Vos golfs</title>
  <link rel="icon" href="./img/Logo1.png">
  
  <!-- Boostrap css and js for navigation of 3 parts(création consultation analyse)-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
  <!-- graphe of analyse part -->
  <script src="https://cdn.staticfile.org/echarts/4.3.0/echarts.min.js"></script>
</head>

<body>

<header>

  <div class="logo_section">

    <div class="logo">
      <img src="./img/Logo1.png" alt="Logo"/>
      <a>Green'Sa</a>
    </div>

    <div class="previous_btn">
      <a href="index.php">Retour à l'acceuil</a>
    </div>

    <div class="text_header">
      <label class="header_text">Vos golfs</label>
    </div>
    <!-- The drop down user menu -->
    <div class="dropbtn">
      <img id="user_image" class="user_image" src="./img/user.png" alt="User logo"/>
      <div class="dropdown-content">
        <a href="profil.php">Modifier profil</a>
        <a href="index.php?logout='1'">Déconnexion</a>
      </div>
    </div>
  </div>

</header>

<div class="main_container">

	<ul class="nav nav-tabs" id="myTab" role="tablist">
	  <li class="nav-item">
		<a class="nav-link active" id="create-tab" data-toggle="tab" href="#create" role="tab" aria-controls="create" aria-selected="true" style="color: black;">Création</a>
	  </li>
	  
	  <li class="nav-item">
		<a class="nav-link" id="consult-tab" data-toggle="tab" href="#consult" role="tab" aria-controls="consult" aria-selected="false" style="color: black;">Consultation</a>
	  </li>
	  
	  <li class="nav-item">
		<a class="nav-link" id="analyse-tab" data-toggle="tab" href="#analyse" role="tab" aria-controls="analyse" aria-selected="false" style="color: black;">Analyse</a>
	  </li>
	</ul>

   <div class="tab-content" id="nav-myTabContent">
   
   <!--The part of create of golf (copy-paste of previous code)-->
	<div class="tab-pane fade  show active" id="create" role="tabpanel" aria-labelledby="create-tab">
		<!-- Two forms, one to modify golfs and one to view them (might be useless to have2 -->
		<div class="map_submit_container">
		  <form action="golfCreation.php" method="post" class="add_golf">
			<label>Créez votre golf avec un carte : </label>
			<button type="submit" class="creation_btn" name="submit_golf_creation">Créer</button>
		  </form>

		  <form action="golf.php" method="post" enctype="multipart/form-data" class="add_golf">
			<label class="text_browse">Veuillez sélectionner le golf (en fichier XML) que vous voulez ajouter :</label>
			<input class="browse" type="file" id="file" name="file" style="visibility: hidden">
			<button class="browse_btn" style="display:block;width:200px; height:30px;" onclick="document.getElementById('file').click()">Selectionnez un golf</button>
			<button class="browse" style="width:100px;" type="submit" name="submit_file">Importer le golf</button>
		  </form>
		</div>

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
			mysqli_query($db,"INSERT into golf (idGolf, nbTrou,username) VALUES ('$golf_name','$golf_nb_trou','$username')");
			for($i=1;$i<=$golf_nb_trou;$i++) {
			  mysqli_query($db, "INSERT into trougolf (idGolf,trou, par, lat, lng) VALUES ('$golf_name','$i','$golf_coordinates_par[$i]','$golf_coordinates_lat[$i]','$golf_coordinates_lng[$i]')");
			}
			//Redirect to the same page to show the new golf
			header("Location: golf.php");
		  }
		}
		?>
	</div>
  
  <div class="tab-pane fade" id="consult" role="tabpanel" aria-labelledby="consult-tab">
  <!--The part of consult of golf (also copy-paste)-->
	<?php
    //Show the golf automatically using the database
    //Weird syntax again, check mysqli doc

    $db = mysqli_connect('localhost','root','','demo') or die('Could not connect to the database');

    $idGolf_query = mysqli_query($db,"SELECT idGolf FROM golf where username='$username'");
    $idGolfs = mysqli_fetch_all($idGolf_query);

    foreach($idGolfs as $id){
      $nbTrou_query =  mysqli_query($db,"SELECT nbTrou FROM golf WHERE idGolf='$id[0]'");
      $nbTrou = mysqli_fetch_row($nbTrou_query);
      echo "
                        <div class=\"tabGolf\">
                            <label><b>Nom du Golf : </b>$id[0]</label>
                            <label><b>  Nombre de trous : </b>$nbTrou[0]</label>
                            <table id=$id[0]>
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
  
  <div class="tab-pane fade" id="analyse" role="tabpanel" aria-labelledby="analyse-tab">
  <!--Analyse-->
     <!-- once the golf changed, show the graphes-->
	 
<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.js"></script>
<script type="text/javascript">
function changeGolf() 
{		
		var name = $("#menu").val();
		var dataName=[];
		var dataValue=[];
		var myChart = echarts.init(document.getElementById('graphe'));
					var x=document.getElementById("datatable");
		if(name != -1)
		{
			$.ajax({
            url:"getGolf.php",
			type: 'post',
			data:{'nameGolf':name},
            dataType:"json",
            success:function(e)
			{
				for (var i=0;i<e.length ;i++ ){
				dataName.push("trou"+e[i].trou);
				dataValue.push(e[i].par);}
				 myChart.setOption({
				   tooltip: {},
				   legend: {
						data: ['pars']
				   },
				   xAxis: {
						data: dataName
				   },
				   yAxis: {},
				   series: [{
						name: '',
						type: 'bar',
						data: dataValue,
						color:'#DCDCDC',
				   }]
				});
			} 		  
			}); 		
        }
}
 
$(function(){
	 $("select[name='menu']").change(function(){
		 changeGolf();
	})
})
</script>
  
  <!--for the dropdown menu -->
  <select name="menu" id="menu" οnChange="changeGolf()">
  <?php 
  echo "<option value='-1' selected>---------------</option>"; 
  
  $result = mysqli_query($db,"SELECT idGolf FROM golf where username='$username';");  
  if(mysqli_num_rows($result) == 0){ 
    echo "Veuillez ajouter un golf";
  } 
  else { 
    while($row = mysqli_fetch_assoc($result)){ 
		 $idGolf = $row["idGolf"]; 
		 echo "<option value='$idGolf'>$idGolf</option>"; 
	}  
   } 
   ?>
   </select>

<div id="graphe" style="width: 600px;height:400px; margin: 0 auto"></div>

  </div>

</div>
  
  <video autoplay muted loop id="background_video">
	<source src="./media/golf_main_video.mp4" type="video/mp4">
  </video>
	
</div>

</body>
