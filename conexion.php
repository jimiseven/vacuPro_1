<?php
$host = "localhost";
$dbname = "vacupro_1";
$username = "root";
$password = "";

// Crear una nueva conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    // echo "Conexión exitosa a la base de datos vacupro_1";
}
?>
