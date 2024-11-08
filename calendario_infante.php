<?php
include_once 'conexion.php';

// Configurar la fecha en español
setlocale(LC_TIME, 'es_ES.UTF-8');

// Obtener el ID del infante desde la URL
$id_infante = $_GET['id'] ?? null;
if (!$id_infante) {
    echo "ID del infante no especificado.";
    exit;
}

// Consulta para obtener los datos del infante
$query_infante = "SELECT nombre, apellido_paterno, apellido_materno, fecha_nacimiento FROM niños WHERE id = ?";
$stmt = $conn->prepare($query_infante);
$stmt->bind_param("i", $id_infante);
$stmt->execute();
$result_infante = $stmt->get_result();
$infante = $result_infante->fetch_assoc();

// Calcular la edad en meses
function calcularEdadEnMeses($fechaNacimiento)
{
    $fechaActual = new DateTime();
    $fechaNac = new DateTime($fechaNacimiento);
    $diferencia = $fechaActual->diff($fechaNac);
    return ($diferencia->y * 12) + $diferencia->m;
}

// Edad del infante en meses
$edad_meses = calcularEdadEnMeses($infante['fecha_nacimiento']);

// Consulta para obtener el calendario de vacunación según el tipo de vacuna y dosis requeridas
$query_calendario = "
    SELECT 
        vacuna_tipo.id AS tipo_id,
        vacuna_tipo.tipo AS vacuna,
        calendario.numero_dosis,
        vacunaciones.fecha_administracion,
        DATE_ADD(?, INTERVAL ((calendario.numero_dosis - 1) * 2) MONTH) AS fecha_sugerida,
        IF(vacunaciones.fecha_administracion IS NULL, 'Pendiente', 'Administrada') AS estado
    FROM vacuna_tipo
    JOIN (SELECT 1 AS numero_dosis UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) AS calendario
        ON calendario.numero_dosis <= vacuna_tipo.dosis_requeridas
    LEFT JOIN vacunaciones 
        ON vacuna_tipo.id = vacunaciones.tipo_id 
        AND vacunaciones.id_nino = ? 
        AND vacunaciones.numero_dosis = calendario.numero_dosis
    ORDER BY fecha_sugerida ASC";

$stmt_calendario = $conn->prepare($query_calendario);
$stmt_calendario->bind_param("si", $infante['fecha_nacimiento'], $id_infante);
$stmt_calendario->execute();
$result_calendario = $stmt_calendario->get_result();

// Obtener lista de personal para seleccionar el administrador de la vacuna
$query_personal = "SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM personal";
$result_personal = $conn->query($query_personal);

// Obtener lista de tipos de vacunas
$query_tipos_vacunas = "SELECT id, tipo FROM vacuna_tipo";
$result_tipos_vacunas = $conn->query($query_tipos_vacunas);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Vacunas del Infante</title>
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
                    <h1 class="h2 text-primary">CALENDARIO DE VACUNAS DEL INFANTE</h1>
                </div>

                <!-- Datos del infante -->
                <div class="mb-4">
                    <h5><strong>Nombre Completo:</strong> <?php echo $infante['nombre'] . ' ' . $infante['apellido_paterno'] . ' ' . $infante['apellido_materno']; ?></h5>
                    <h5><strong>Edad (Meses):</strong> <?php echo $edad_meses; ?></h5>
                </div>

                <!-- Tabla de calendario de vacunación -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha Sugerida</th>
                                <th>Tarea</th>
                                <th>Estado</th>
                                <th>Fecha de Administración</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_calendario->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo strftime("%d de %B", strtotime($row['fecha_sugerida'])); ?></td>
                                    <td><?php echo 'Dosis ' . $row['numero_dosis'] . ' - ' . $row['vacuna']; ?></td>
                                    <td><?php echo $row['estado']; ?></td>
                                    <td><?php echo $row['fecha_administracion'] ? strftime("%d de %B", strtotime($row['fecha_administracion'])) : '-'; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Botón para registrar vacuna con modal -->
                <div class="mt-3">
                    <a href="informacion_infante.php?id=<?php echo $id_infante; ?>" class="btn btn-success">Atrás</a>
                </div>

            </main>
        </div>
    </div>



    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>