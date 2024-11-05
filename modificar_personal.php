<?php
include_once 'conexion.php';

// Obtener datos del formulario
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula_identidad = $_POST['cedula_identidad'];
$celular = $_POST['celular'];
$puesto = $_POST['puesto'];

// Actualizar los datos en la base de datos
$query = "UPDATE personal 
          SET nombre = '$nombre', apellido = '$apellido', 
              numero_cedula_identidad = '$cedula_identidad', celular = '$celular', puesto = '$puesto' 
          WHERE id = '$id'";

if ($conn->query($query) === TRUE) {
    header("Location: personal.php"); // Redirige a la lista de personal si la actualización es exitosa
} else {
    echo "Error al actualizar los datos: " . $conn->error;
}

$conn->close();
?>
