<!--Pour la connexion de bd a chaque fois-->
<?php
class bd {
private $co;
private $host;
private $user;
private $bdd;
private $passwd;
public function __construct() {
$this->host = "localhost";
$this->user = "root";
$this->passwd = "";
$this->bdd = "demo";
}
public function connection() {
$this->co = mysqli_connect($this->host,$this->user,$this->passwd,$this->bdd) or die("Error of connection");
return $this->co ;
}
public function disconnection() {
mysqli_close($this->co);
}
}
?>