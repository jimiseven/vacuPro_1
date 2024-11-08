<?php
include_once 'conexion.php';

// Obtener el tipo de vacuna seleccionado en el filtro, si existe
$filtro_vacuna = $_GET['vacuna'] ?? '';

// Modificar la consulta según el filtro de tipo de vacuna
$query = "SELECT 
            niños.nombre AS infante,
            vacuna_tipo.tipo AS vacuna,
            vacunaciones.fecha_administracion AS fecha_vacunacion
          FROM vacunaciones
          JOIN niños ON vacunaciones.id_nino = niños.id
          JOIN vacuna_tipo ON vacunaciones.tipo_id = vacuna_tipo.id";

if ($filtro_vacuna) {
    $query .= " WHERE vacuna_tipo.tipo = ?";
}

$query .= " ORDER BY fecha_vacunacion DESC";
$stmt = $conn->prepare($query);

if ($filtro_vacuna) {
    $stmt->bind_param("s", $filtro_vacuna);
}

$stmt->execute();
$result = $stmt->get_result();

// Obtener todos los tipos de vacunas para el filtro
$query_tipos_vacunas = "SELECT DISTINCT tipo FROM vacuna_tipo";
$result_tipos_vacunas = $conn->query($query_tipos_vacunas);

// Calcular el total de registros según el filtro
$total_vacunas = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VAC-SOFT</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar bg-dark">
                <div class="position-sticky">
                    <div class="sidebar-header p-3 text-center">
                        <h3 class="text-white"><strong>VAC-SOFT</strong></h3>
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

                <!-- Filtro por tipo de vacuna -->
                <form method="get" action="index.php" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="vacuna" class="form-label">Filtrar por tipo de vacuna:</label>
                            <select name="vacuna" id="vacuna" class="form-select">
                                <option value="">Todos</option>
                                <?php while ($row_tipo = $result_tipos_vacunas->fetch_assoc()) : ?>
                                    <option value="<?php echo $row_tipo['tipo']; ?>" <?php if ($row_tipo['tipo'] == $filtro_vacuna) echo 'selected'; ?>>
                                        <?php echo $row_tipo['tipo']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>

                <!-- Mostrar el total de vacunas según el filtro -->
                <h5>Total de vacunas administradas: <?php echo $total_vacunas; ?></h5>

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
