<?php
include_once 'conexion.php';
include_once 'proteger.php';
// Obtener el tipo de vacuna y meses seleccionados en los filtros, si existen
$filtro_vacuna = $_GET['vacuna'] ?? '';
$filtro_meses = $_GET['meses'] ?? '';

// Construir la consulta base
$query = "SELECT 
            niños.nombre AS infante,
            vacuna_tipo.tipo AS vacuna,
            vacunaciones.fecha_administracion AS fecha_vacunacion
          FROM vacunaciones
          JOIN niños ON vacunaciones.id_nino = niños.id
          JOIN vacuna_tipo ON vacunaciones.tipo_id = vacuna_tipo.id";

// Agregar condiciones según los filtros
$condiciones = [];
$parametros = [];

if ($filtro_vacuna) {
    $condiciones[] = "vacuna_tipo.tipo = ?";
    $parametros[] = $filtro_vacuna;
}

if ($filtro_meses) {
    $condiciones[] = "MONTH(vacunaciones.fecha_administracion) = ?";
    $parametros[] = $filtro_meses;
}

if (!empty($condiciones)) {
    $query .= " WHERE " . implode(" AND ", $condiciones);
}

$query .= " ORDER BY fecha_vacunacion DESC";
$stmt = $conn->prepare($query);

if (!empty($parametros)) {
    $tipos_parametros = str_repeat("s", count($parametros));
    $stmt->bind_param($tipos_parametros, ...$parametros);
}

$stmt->execute();
$result = $stmt->get_result();

// Obtener todos los tipos de vacunas para el filtro
$query_tipos_vacunas = "SELECT DISTINCT tipo FROM vacuna_tipo";
$result_tipos_vacunas = $conn->query($query_tipos_vacunas);

// Calcular el total de registros según los filtros
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
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar bg-dark d-flex flex-column">
                <div>
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
                <!-- Botón de cerrar sesión -->
                <div class="mt-auto text-center p-3">
                    <a href="logout.php" class="btn btn-danger w-100">Cerrar Sesión</a>
                </div>
            </nav>



            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Vacunas</h1>
                    <a href="registro_vacunacion.php" class="btn btn-outline-secondary">Registrar</a>
                </div>

                <!-- Filtro por tipo de vacuna y meses -->
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
                        <div class="col-md-4">
                            <label for="meses" class="form-label">Filtrar por mes:</label>
                            <select name="meses" id="meses" class="form-select">
                                <option value="">Todos</option>
                                <?php
                                for ($mes = 1; $mes <= 12; $mes++) {
                                    $nombre_mes = strftime('%B', mktime(0, 0, 0, $mes, 1));
                                    echo "<option value='$mes'" . ($mes == $filtro_meses ? ' selected' : '') . ">$nombre_mes</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
                <div class="mt-3">
                    <form method="get" action="generar_reporte.php" target="_blank">
                        <input type="hidden" name="vacuna" value="<?php echo $filtro_vacuna; ?>">
                        <input type="hidden" name="meses" value="<?php echo $filtro_meses; ?>">
                        <button type="submit" class="btn btn-outline-secondary">Descargar Reporte PDF</button>
                    </form>
                </div>

                <!-- Mostrar el total de vacunas según los filtros -->
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
                </div>
            </main>
        </div>
    </div>
    <!-- Botón para descargar el reporte -->



    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>