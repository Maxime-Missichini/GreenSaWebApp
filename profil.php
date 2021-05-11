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

<head>
  <title>Green'Sa - Modification du profil</title>
  <link rel="icon" href="./img/Logo1.png">
  <link rel = "stylesheet" href = "./css/profil.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
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
      <label class="header_text">Modification du profil</label>
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

  <div class="main_container">
	<form class="main_form" method="post" action="./update.php" align="center"  name="data_form" id="data_form">

	<div class="username">
		<label>Nom d'utilisateur:</label><input type="text" name="username" value="<?php echo $username ?>" readonly="readonly">
	</div>
    <br>
	
	<div class="password1">
		<label>Nouveau mot de passe:</label><input type="password" id="signup_password" name="signup_password" class="form-control enb_dsb_fld mv_next" placeholder="Password" required="required">
	</div>
    <br><br>
	
	<div class="password2">
		<label>Confirmation du mot de passe:</label><input type="password" id="signup_confirm_password" name="signup_confirm_password" class="form-control enb_dsb_fld mv_next" placeholder="Retype Password" required="required"><br>
	</div>
	
	<div class="btn">
		<button type="button" id="save_btn" name="save_btn" class="btn btn-success save_btn btn-sm">Enregistrer la modification</button> 
	</div>
</form>
</div>

<!--Jquery validation + boostrap popover-->
<!-- Source: https://stackoverflow.com/questions/59065265/jquery-validation-popover-position-problem-for-select2-dropdown-->

<script type="text/javascript">
$(document).ready(function(){
  my_validate();
})
var data_form = $( "#data_form" );

function my_validate(){
 data_form.validate( {
	rules: { 
      signup_password: { 
       required: true, 
       maxlength: 12 
      }, 
      signup_confirm_password: { 
       required: true, 
       equalTo: "#signup_password" 
      } 
     }, 
	 
    messages:{
        signup_password:{
          required: "Veuillez saisir un mot de passe", 
		  maxlength: "Le mot de passe ne dépasse pas 12 caractères" 
        },
        signup_confirm_password:{
          required: "Veuillez re-saisir le mot de passe", 
		  equalTo: "Mot de passe différent, veuillez reéssayer"
        }
    },
	
    errorClass: "my-error-class",
    showErrors: function(errorMap, errorList) {
        $.each( this.successList , function(index, value) {
            $(value).popover('hide');
        }); 
        $.each( errorList , function(index, value) {
         
          var popoverDta = $(value.element).popover({
                trigger   : 'manual',
                placement : 'top',
                content   : value.message,
                template  : '<div class="popover"><div class="arrow"></div><div class="popover-inner"><div class="popover-content text-danger"><p></p></div></div></div>'
          });                      
          $(value.element).data('bs.popover').options.content = value.message;
              $(value.element).popover('show');
          });
    }        
}); 
}
$(document).on('click',".save_btn",function(){
if(data_form.valid() == true)
{
	document.getElementById("data_form").submit();
}
});
</script>
</body>
</html>
