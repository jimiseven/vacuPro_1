<?php
include_once 'conexion.php';

if (isset($_GET['id'])) {
    $id_infante = $_GET['id'];
} else {
    echo "Error: ID del infante no definido.";
    exit;
}

// Consulta para obtener los datos del infante
$query_infante = "SELECT nombre, apellido_paterno, apellido_materno, fecha_nacimiento, numero_cedula_identidad 
                  FROM niños WHERE id = '$id_infante'";
$result_infante = $conn->query($query_infante);
$infante = $result_infante->fetch_assoc();

// Calcular la edad en meses
function calcularEdadEnMeses($fechaNacimiento) {
    $fechaActual = new DateTime();
    $fechaNac = new DateTime($fechaNacimiento);
    $diferencia = $fechaActual->diff($fechaNac);
    return ($diferencia->y * 12) + $diferencia->m;
}

// Consulta para obtener el calendario de vacunación
$query_calendario = "SELECT calendario_vacunacion.numero_dosis, calendario_vacunacion.edad_recomendada_meses, vacunas.nombre AS vacuna 
                     FROM calendario_vacunacion
                     JOIN vacunas ON calendario_vacunacion.id_vacuna = vacunas.id
                     ORDER BY vacunas.nombre, calendario_vacunacion.numero_dosis";
$result_calendario = $conn->query($query_calendario);

// Calcular las fechas sugeridas para las vacunas
$fechas_sugeridas = [];
while ($row = $result_calendario->fetch_assoc()) {
    $fecha_nacimiento = new DateTime($infante['fecha_nacimiento']);
    $fecha_sugerida = clone $fecha_nacimiento;
    $fecha_sugerida->add(new DateInterval('P' . ($row['edad_recomendada_meses'] * 30) . 'D')); // Aproximadamente 30 días por mes

    $fechas_sugeridas[] = [
        'vacuna' => $row['vacuna'],
        'dosis' => $row['numero_dosis'],
        'fecha_sugerida' => $fecha_sugerida->format('d - M'),
        'estado' => 'Pendiente',  // Este es un estado predeterminado
        'fecha_administracion' => '-'  // Se muestra un guion si no hay fecha de administración
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario del Infante</title>
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
                        <h3 class="text-white"><strong>VAC-PRO</strong></h3>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.html">
                                <i class="bi bi-arrow-right-circle me-2"></i> Vacunas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="infantes.php">
                                <i class="bi bi-people me-2"></i> Infantes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-person-badge me-2"></i> Personal
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h1 class="mt-4 mb-4">Calendario del infante</h1>
                <div class="row">
                    <!-- Datos del infante -->
                    <div class="col-md-6">
                        <h4>Datos infante</h4>
                        <p><strong>Nombre :</strong> <?php echo $infante['nombre'] . ' ' . $infante['apellido_paterno'] . ' ' . $infante['apellido_materno']; ?></p>
                        <p><strong>Fecha Nac :</strong> <?php echo date("d M Y", strtotime($infante['fecha_nacimiento'])); ?></p>
                        <p><strong>Meses :</strong> <?php echo calcularEdadEnMeses($infante['fecha_nacimiento']); ?></p>
                        <p><strong>CI :</strong> <?php echo $infante['numero_cedula_identidad']; ?></p>
                    </div>
                </div>

                <!-- Tabla de calendario de vacunación -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha Sugerida</th>
                                <th>Vacuna</th>
                                <th>Estado</th>
                                <th>Fecha de Administración</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fechas_sugeridas as $fecha): ?>
                                <tr>
                                    <td><?php echo $fecha['fecha_sugerida']; ?></td>
                                    <td><?php echo $fecha['vacuna'] . ' - Dosis ' . $fecha['dosis']; ?></td>
                                    <td><?php echo $fecha['estado']; ?></td>
                                    <td><?php echo $fecha['fecha_administracion']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Botón de regreso -->
                <div class="mt-3">
                    <a href="infantes.php" class="btn btn-secondary">Atrás</a>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
