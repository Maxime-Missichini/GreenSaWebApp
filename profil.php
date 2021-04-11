<!doctype html>
<!--Changement de mot de passe-->
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
<link rel = "stylesheet" href = "./css/profil.css"/>

<body>
<header>
	<title>GreenSa - Modification du profil</title>
</header>

<!-- Navigation -->
<div class="header">
    <label class="text_header">Edit Your Profile</label>
    <button class="logout"><a href="index.php?logout='1'">Log out</a></button>
</div>

<!--Affichage le nom d'utilisateur dans le cas "Nom d'utilisation"-->
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

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
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
</head>

<body>
<div class="main_container">
 <form method="post" action="./update.php" align="center" onsubmit="return checkForm()" class="main_form">
     Nom d'utilisation:&nbsp&nbsp&nbsp<input type="text" name="username" value="<?php echo $username ?>" readonly="readonly"><br><br>

     <label>Nouvel mot de passe:</label>&nbsp&nbsp&nbsp&nbsp<input type="password" name="edit_password" id="edit_password"><br>
	<span id="user_password" class="error">*</span><br><br>

	<label>Confirmation de mot de passe:</label><input type="password" name="upassword" id="upassword"><br>
	<span id="uupassword" class="error">*</span><br><br><br>

     <input class="btn" type="submit" name="submit" value="Enregistrer la modification">
  </form>
</div>
</body>
</html>
