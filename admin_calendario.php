<?php
include_once 'conexion.php';

// Obtener todas las vacunas
$query_vacunas = "SELECT * FROM vacunas";
$result_vacunas = $conn->query($query_vacunas);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_vacuna = $_POST['id_vacuna'];
    $numero_dosis = $_POST['numero_dosis'];
    $edad_recomendada_meses = $_POST['edad_recomendada_meses'];

    // Insertar o actualizar el calendario de vacunación
    $query_calendario = "INSERT INTO calendario_vacunacion (id_vacuna, numero_dosis, edad_recomendada_meses)
                         VALUES ('$id_vacuna', '$numero_dosis', '$edad_recomendada_meses')
                         ON DUPLICATE KEY UPDATE edad_recomendada_meses='$edad_recomendada_meses'";
    $conn->query($query_calendario);
    echo "<div class='alert alert-success'>Calendario actualizado con éxito</div>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Calendario de Vacunación</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Administrar Calendario de Vacunación</h1>
        <form method="POST" action="admin_calendario.php">
            <div class="mb-3">
                <label for="id_vacuna" class="form-label">Vacuna</label>
                <select class="form-select" id="id_vacuna" name="id_vacuna" required>
                    <?php while ($row = $result_vacunas->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="numero_dosis" class="form-label">Número de Dosis</label>
                <input type="number" class="form-control" id="numero_dosis" name="numero_dosis" required>
            </div>
            <div class="mb-3">
                <label for="edad_recomendada_meses" class="form-label">Edad Recomendada (meses)</label>
                <input type="number" class="form-control" id="edad_recomendada_meses" name="edad_recomendada_meses" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
