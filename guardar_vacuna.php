<?php
include_once 'conexion.php';

// Obtener datos del formulario
$id_infante = $_POST['id_infante'];
$id_vacuna = $_POST['id_vacuna'];
$fecha_administracion = $_POST['fecha_administracion'];
$id_personal = $_POST['id_personal'];
$fecha_proxima_dosis = isset($_POST['fecha_proxima_dosis']) ? $_POST['fecha_proxima_dosis'] : NULL; // Permitir valor NULL
$estado = 'Pendiente'; // Puedes cambiar el estado según sea necesario

// Verificar si el `id_personal` existe en la tabla `personal`
$check_personal = $conn->query("SELECT id FROM personal WHERE id = '$id_personal'");
if ($check_personal->num_rows === 0) {
    die("Error: El id_personal proporcionado no existe en la tabla `personal`.");
}

// Verificar si el `id_vacuna` existe en la tabla `vacunas`
$check_vacuna = $conn->query("SELECT id FROM vacunas WHERE id = '$id_vacuna'");
if ($check_vacuna->num_rows === 0) {
    die("Error: El id_vacuna proporcionado no existe en la tabla `vacunas`.");
}

// Verificar si el `id_nino` existe en la tabla `niños`
$check_nino = $conn->query("SELECT id FROM niños WHERE id = '$id_infante'");
if ($check_nino->num_rows === 0) {
    die("Error: El id_infante proporcionado no existe en la tabla `niños`.");
}

// Insertar los datos en la tabla `vacunaciones`
$query = "INSERT INTO vacunaciones (id_nino, id_vacuna, fecha_administracion, id_personal, fecha_proxima_dosis, estado) 
          VALUES ('$id_infante', '$id_vacuna', '$fecha_administracion', '$id_personal', " . ($fecha_proxima_dosis ? "'$fecha_proxima_dosis'" : "NULL") . ", '$estado')";

if ($conn->query($query) === TRUE) {
    echo "Vacuna registrada exitosamente.";
    header("Location: informacion_infante.php?id=$id_infante");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>
