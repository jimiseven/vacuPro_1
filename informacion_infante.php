<?php
include_once 'conexion.php';

// Obtener el ID del infante desde la URL
$id_infante = $_GET['id'];

// Consulta para obtener los datos del infante y del responsable
$query = "SELECT id, nombre, apellido_paterno, apellido_materno, fecha_nacimiento, numero_cedula_identidad, 
                 nombre_responsable, apellido_paterno_tutor, apellido_materno_tutor, numero_cedula_tutor, telefono_tutor, relacion
          FROM niños WHERE id = '$id_infante'";
$result = $conn->query($query);
$infante = $result->fetch_assoc();

// Calcular la edad en meses
function calcularEdadEnMeses($fechaNacimiento) {
    $fechaActual = new DateTime();
    $fechaNac = new DateTime($fechaNacimiento);
    $diferencia = $fechaActual->diff($fechaNac);
    return ($diferencia->y * 12) + $diferencia->m;
}

// Consulta para obtener la información de las vacunas
$query_vacunas = "SELECT vacunas.nombre AS vacuna, vacunaciones.fecha_administracion, vacunaciones.numero_dosis 
                  FROM vacunaciones 
                  JOIN vacunas ON vacunaciones.id_vacuna = vacunas.id 
                  WHERE vacunaciones.id_nino = '$id_infante'";
$result_vacunas = $conn->query($query_vacunas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infante Información</title>
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
                <h1 class="mt-4 mb-4">Datos</h1>

                <div class="row">
                    <!-- Datos del infante -->
                    <div class="col-md-6">
                        <h4>Datos infante</h4>
                        <p><strong>Nombre :</strong> <?php echo $infante['nombre'] . ' ' . $infante['apellido_paterno'] . ' ' . $infante['apellido_materno']; ?></p>
                        <p><strong>Fecha Nac :</strong> <?php echo date("d M Y", strtotime($infante['fecha_nacimiento'])); ?></p>
                        <p><strong>Meses :</strong> <?php echo calcularEdadEnMeses($infante['fecha_nacimiento']); ?></p>
                        <p><strong>CI :</strong> <?php echo $infante['numero_cedula_identidad']; ?></p>
                    </div>

                    <!-- Datos del responsable -->
                    <div class="col-md-6">
                        <h4>Datos responsable</h4>
                        <p><strong>Nombre :</strong> <?php echo $infante['nombre_responsable'] . ' ' . $infante['apellido_paterno_tutor'] . ' ' . $infante['apellido_materno_tutor']; ?></p>
                        <p><strong>Relación :</strong> <?php echo $infante['relacion']; ?></p>
                        <p><strong>Celular :</strong> <?php echo $infante['telefono_tutor']; ?></p>
                    </div>
                </div>

                <!-- Tabla de vacunas -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Vacuna</th>
                                <th>Dosis 1</th>
                                <th>Dosis 2</th>
                                <th>Dosis 3</th>
                                <th>Dosis 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row_vacuna = $result_vacunas->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row_vacuna['vacuna']; ?></td>
                                    <td><?php echo ($row_vacuna['numero_dosis'] == 1) ? date("d - M", strtotime($row_vacuna['fecha_administracion'])) : '-'; ?></td>
                                    <td><?php echo ($row_vacuna['numero_dosis'] == 2) ? date("d - M", strtotime($row_vacuna['fecha_administracion'])) : '-'; ?></td>
                                    <td><?php echo ($row_vacuna['numero_dosis'] == 3) ? date("d - M", strtotime($row_vacuna['fecha_administracion'])) : '-'; ?></td>
                                    <td><?php echo ($row_vacuna['numero_dosis'] == 4) ? date("d - M", strtotime($row_vacuna['fecha_administracion'])) : '-'; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Botones adicionales -->
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-secondary">Registrar Vacuna</button>
                    <a href="calendario_infante.php?id=<?php echo $infante['id']; ?>" class="btn btn-outline-secondary">Calendario</a>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
