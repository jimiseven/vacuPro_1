<?php
include_once 'conexion.php';

// Habilitar informes de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $numero_cedula_identidad = $_POST['numero_cedula_identidad'];
    $numero_acta_nacimiento = $_POST['numero_acta_nacimiento'];

    $nombre_responsable = $_POST['nombre_responsable'];
    $apellido_paterno_tutor = $_POST['apellido_paterno_tutor'];
    $apellido_materno_tutor = $_POST['apellido_materno_tutor'];
    $numero_cedula_tutor = $_POST['numero_cedula_tutor'];
    $telefono_tutor = $_POST['telefono_tutor'];
    $relacion = $_POST['relacion'];

    try {
        // Consulta SQL para actualizar los datos
        $query = "UPDATE niños 
                  SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, fecha_nacimiento = ?, 
                      numero_cedula_identidad = ?, numero_acta_nacimiento = ?, 
                      nombre_responsable = ?, apellido_paterno_tutor = ?, apellido_materno_tutor = ?, 
                      numero_cedula_tutor = ?, telefono_tutor = ?, relacion = ? 
                  WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssssssssssssi",
            $nombre, $apellido_paterno, $apellido_materno, $fecha_nacimiento, $numero_cedula_identidad, $numero_acta_nacimiento,
            $nombre_responsable, $apellido_paterno_tutor, $apellido_materno_tutor, $numero_cedula_tutor, $telefono_tutor, $relacion,
            $id
        );

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir con mensaje de éxito
            header("Location: informacion_infante.php?id=$id&success=1");
            exit;
        } else {
            // Redirigir con mensaje de error si no se ejecuta correctamente
            header("Location: informacion_infante.php?id=$id&error=1");
            exit;
        }
    } catch (Exception $e) {
        // Manejar errores de conexión o ejecución
        error_log("Error al actualizar datos del infante: " . $e->getMessage());
        header("Location: informacion_infante.php?id=$id&error=1");
        exit;
    }
} else {
    // Si el método no es POST, redirigir con error
    header("Location: informacion_infante.php?error=1");
    exit;
}
?>
