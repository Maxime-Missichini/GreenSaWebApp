<?php
//Return info to javascrpt by ajax and PHP Data Objects(PDO)
$dsn = 'mysql:host=127.0.0.1;port=3306;charset=utf8;dbname=demo';

$pdo = new PDO($dsn,'root','');

$nameGolf = $_POST['nameGolf'];
$sql = "select * from trougolf where idGolf='$nameGolf'";
$res = $pdo->query($sql);
$row=$res->fetchALL(PDO::FETCH_ASSOC);

echo json_encode($row);
?>