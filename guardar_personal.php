<?php
include_once 'conexion.php';

// Verificar que los datos se reciban correctamente del formulario
if (isset($_POST['nombre'], $_POST['apellido'], $_POST['cedula_identidad'], $_POST['celular'], $_POST['puesto'])) {
    // Asignar los valores del formulario a variables
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $cedula_identidad = $conn->real_escape_string($_POST['cedula_identidad']);
    $celular = $conn->real_escape_string($_POST['celular']);
    $puesto = $conn->real_escape_string($_POST['puesto']);

    // Consulta para insertar los datos en la tabla `personal`
    $query = "INSERT INTO personal (nombre, apellido, numero_cedula_identidad, celular, puesto) VALUES ('$nombre', '$apellido', '$cedula_identidad', '$celular', '$puesto')";

    // Ejecutar la consulta y verificar si fue exitosa
    if ($conn->query($query) === TRUE) {
        // Redirigir al listado de personal si el registro fue exitoso
        header("Location: personal.php");
        exit();
    } else {
        echo "Error al registrar el personal: " . $conn->error;
    }
} else {
    echo "Error: Datos incompletos en el formulario.";
}
?>
