<?php
include_once 'conexion.php';
include_once 'proteger.php';

// Habilitar informes de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_infante = $_POST['id'];

    try {
        // Iniciar transacción
        $conn->begin_transaction();

        // Eliminar los datos relacionados del infante (vacunas, etc.)
        $query_vacunas = "DELETE FROM vacunaciones WHERE id_nino = ?";
        $stmt_vacunas = $conn->prepare($query_vacunas);
        $stmt_vacunas->bind_param("i", $id_infante);
        $stmt_vacunas->execute();

        // Eliminar el infante
        $query_infante = "DELETE FROM niños WHERE id = ?";
        $stmt_infante = $conn->prepare($query_infante);
        $stmt_infante->bind_param("i", $id_infante);
        $stmt_infante->execute();

        // Confirmar transacción
        $conn->commit();

        // Redirigir con mensaje de éxito
        header("Location: infantes.php?success=1");
        exit;
    } catch (Exception $e) {
        // En caso de error, revertir transacción
        $conn->rollback();

        // Registrar el error y redirigir con mensaje de error
        error_log("Error al eliminar infante: " . $e->getMessage());
        header("Location: informacion_infante.php?id=$id_infante&error=1");
        exit;
    }
} else {
    // Si el método no es POST, redirigir con error
    header("Location: infantes.php?error=1");
    exit;
}
?>
