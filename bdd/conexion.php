<?php

$servername = 'localhost';
$dbname = 'icare';
$username = 'root';
$password = '';

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);//con esto hacemos la conexión
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully"; 
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}


?>