<?php
include_once 'conexion.php';
include_once 'proteger.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_personal = $_POST['id'];

    try {
        // Iniciar transacción
        $conn->begin_transaction();

        // Eliminar el registro del personal
        $query = "DELETE FROM personal WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_personal);
        $stmt->execute();

        // Confirmar transacción
        $conn->commit();

        // Redirigir con mensaje de éxito
        header("Location: personal.php?success=1");
        exit;
    } catch (Exception $e) {
        // Revertir cambios si ocurre un error
        $conn->rollback();

        // Registrar el error
        error_log("Error al eliminar personal: " . $e->getMessage());

        // Redirigir con mensaje de error
        header("Location: informacion_personal.php?id=$id_personal&error=1");
        exit;
    }
} else {
    // Si el método no es POST, redirigir con error
    header("Location: personal.php?error=1");
    exit;
}
?>
