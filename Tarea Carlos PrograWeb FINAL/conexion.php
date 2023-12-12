<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "mydb";

$conexion = new mysqli($servername, $username, $password, $dbname);

if ($conexion->connect_error) {
    die("LA CONEXION HA FALLADO: " . $conexion->connect_error);
}

$sql = "SELECT * FROM Personas";
$result = $conexion->query($sql);
?>