<!DOCTYPE html>
<html lang="fr">
<meta charset="utf-8">
<!--Changement de mot de passe-->
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

<link rel = "stylesheet" href = "./css/profil.css"/>

<head>
  <title>Green'Sa - Modification du profil</title>
  <link rel="icon" href="./img/Logo1.png">
  <link rel = "stylesheet" href = "./css/profil.css"/>
</head>

<body>

<!-- Navigation -->
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
      <label class="header_text">Création de golf</label>
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

<!--Affichage le nom d'utilisateur dans le cas "Nom d'utilisateur"-->
<?php
	require("./bd.php");
	$username = $_SESSION["username"];

	//Connect with the database
	$connexion_bd = new bd();
    $co = $connexion_bd->connection() or die("Error of connection");

	//run the request and get the info
	$sql = "SELECT * FROM user WHERE username='$username';";
	$res = mysqli_query($co,$sql)or die("erreur requete");
	$row=mysqli_fetch_assoc($res);

	//stop the connection
	$connexion_bd->disconnection();

?>


<script type="text/javascript">
		//check the new password
		function checkPassword(){
			var password = document.getElementById("edit_password");
			var passwordLength = document.getElementById("edit_password").value.length;
			var content = password.value;
			var spanNode = document.getElementById("user_password");
			if (passwordLength > 13)
			{
				spanNode.innerHTML = "Le mot de passe ne dépasse pas 12 caractères".fontcolor("red");
				return false;
			}
			if (content != "") {
				spanNode.innerHTML = "Saisi".fontcolor("green");
				return true;
			}else{
				spanNode.innerHTML = "Le mot de passe est vide".fontcolor("red");
				return false;
			}
		}
		//check another time
		function checkUpassword(){
			var password = document.getElementById("edit_password").value;
			var upassword = document.getElementById("upassword").value;
			var spanNode = document.getElementById("uupassword");
			if (upassword != password) {
				spanNode.innerHTML = "Mot de passe différent, veuillez reéssayer".fontcolor("red");
				return false;
			}
			if (upassword != "") {
				spanNode.innerHTML = "Saisi".fontcolor("green");
				return true;
			}else{
				spanNode.innerHTML = "Veuillez saisir le mot de passe".fontcolor("red");
				return false;
			}
		}
		//Check all of them before send the form
		function checkForm(){
			var add_password = checkPassword();
			var upassword = checkUpassword();
			if (add_password && upassword){
				return true;
			}
			else{
				return false;
			}
		}
</script>

  <div class="main_container">
   <form method="post" action="./update.php" align="center" onsubmit="return checkForm()" class="main_form">
       Nom d'utilisateur:<input type="text" name="username" value="<?php echo $username ?>" readonly="readonly"><br><br>

       <label>Nouveau mot de passe:</label><span id="user_password" class="error">*</span><input type="password" name="edit_password" id="edit_password"><br>
    <br><br>

    <label>Confirmation du mot de passe:</label><span id="uupassword" class="error">*</span><input type="password" name="upassword" id="upassword"><br>
    <br><br><br>

       <input class="btn" type="submit" name="submit" value="Enregistrer la modification">
    </form>
  </div>

</body>
</html>
