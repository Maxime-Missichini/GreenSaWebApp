<!--Utilisé par profil.php, sert à la modification de mdp à coté de la bd-->
<?php
if(!isset($_COOKIE["username"]))
{
	header("location:login.php");
	exit();
}
else
{
	require("./bd.php");
	$name = $_POST['username'];
	$u_password = $_POST['edit_password'];

	//	
	$connexion_bd = new bd();
    $co = $connexion_bd->connection() or die("Error of connection");

	//run the update request
	$sql = "UPDATE user SET password = '$u_password' WHERE username = '$name'";
	$res = mysqli_query($co,$sql)or die("erreur requete"); 
	header("Location:./index.php");
}?>
