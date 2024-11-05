<?php
include_once 'conexion.php';

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula_identidad = $_POST['cedula_identidad'];
$celular = $_POST['celular'];
$puesto = $_POST['puesto'];

// Insertar datos en la tabla personal
$query = "INSERT INTO personal (nombre, apellido, numero_cedula_identidad, puesto) VALUES ('$nombre', '$apellido', '$cedula_identidad', '$puesto')";
if ($conn->query($query) === TRUE) {
    // Redirigir a la página de personal después de registrar con éxito
    header("Location: personal.php");
} else {
    echo "Error al registrar el personal: " . $conn->error;
}

$conn->close();
?>
