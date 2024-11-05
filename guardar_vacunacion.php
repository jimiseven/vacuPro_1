<?php
include_once 'conexion.php';

// Habilitar informes de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtener los datos del formulario
$id_infante = $_POST['id_infante'] ?? null;
$tipo_id = $_POST['tipo_id'] ?? null;
$id_personal = $_POST['id_personal'] ?? null;
$fecha_administracion = $_POST['fecha_administracion'] ?? null;

// Validar que todos los datos estén presentes
if (!$id_infante || !$tipo_id || !$id_personal || !$fecha_administracion) {
    die("Error: Parámetros incompletos.");
}

try {
    // Verificar el número de dosis actual para el infante y el tipo de vacuna
    $query_dosis_actual = "SELECT MAX(numero_dosis) AS ultima_dosis FROM vacunaciones WHERE id_nino = ? AND tipo_id = ?";
    $stmt_dosis_actual = $conn->prepare($query_dosis_actual);
    $stmt_dosis_actual->bind_param("ii", $id_infante, $tipo_id);
    $stmt_dosis_actual->execute();
    $result_dosis_actual = $stmt_dosis_actual->get_result();
    $dosis_data = $result_dosis_actual->fetch_assoc();
    $numero_dosis = ($dosis_data['ultima_dosis'] ?? 0) + 1;

    // Insertar la nueva vacunación
    $query_insert = "INSERT INTO vacunaciones (id_nino, tipo_id, numero_dosis, fecha_administracion, id_personal) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("iiisi", $id_infante, $tipo_id, $numero_dosis, $fecha_administracion, $id_personal);

    if ($stmt_insert->execute()) {
        // Redirigir de vuelta a la página de información del infante después de guardar la vacunación
        header("Location: informacion_infante.php?id=$id_infante");
        exit();
    } else {
        echo "Error al registrar la vacunación: " . $stmt_insert->error;
    }

} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
