<?php
include_once 'conexion.php';

// Consulta para obtener la información de vacunaciones
$query = "SELECT 
            niños.nombre AS infante,
            vacuna_tipo.tipo AS vacuna,
            vacunaciones.fecha_administracion AS fecha_vacunacion
          FROM vacunaciones
          JOIN niños ON vacunaciones.id_nino = niños.id
          JOIN vacuna_tipo ON vacunaciones.tipo_id = vacuna_tipo.id
          ORDER BY fecha_vacunacion DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VAC-PRO</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Archivo CSS personalizado para estilos adicionales -->
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar bg-dark">
                <div class="position-sticky">
                    <div class="sidebar-header p-3 text-center">
                        <h3 class="text-white"><strong>VAC-PRO</strong></h3>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">
                                <i class="bi bi-arrow-right-circle me-2"></i> Vacunas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="infantes.php">
                                <i class="bi bi-people me-2"></i> Infantes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="personal.php">
                                <i class="bi bi-person-badge me-2"></i> Personal
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Vacunas</h1>
                    <a href="registro_vacunacion.php" class="btn btn-outline-secondary">Registrar</a>
                </div>

                <!-- Tabla de vacunaciones -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Infante</th>
                                <th>Vacuna</th>
                                <th>Fecha vacunación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['infante']; ?></td>
                                    <td><?php echo $row['vacuna']; ?></td>
                                    <td><?php echo date("d - M", strtotime($row['fecha_vacunacion'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-outline-secondary mt-3">Imprimir</button>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
