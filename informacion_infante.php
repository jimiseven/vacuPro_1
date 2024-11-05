<?php
include_once 'conexion.php';

// Habilitar informes de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtener el ID del infante desde la URL
$id_infante = $_GET['id'] ?? null;
if (!$id_infante) {
    die("ID del infante no especificado.");
}

// Consulta para obtener los datos del infante y del responsable
$query = "SELECT id, nombre, apellido_paterno, apellido_materno, fecha_nacimiento, numero_cedula_identidad, 
                 nombre_responsable, apellido_paterno_tutor, apellido_materno_tutor, numero_cedula_tutor, telefono_tutor, relacion
          FROM niños WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_infante);
$stmt->execute();
$result = $stmt->get_result();
$infante = $result->fetch_assoc();

if (!$infante) {
    die("No se encontró al infante con el ID especificado.");
}

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

// Consulta para obtener las vacunas administradas al infante y las dosis
$query_vacunas = "SELECT vacuna_tipo.tipo AS vacuna, vacunaciones.fecha_administracion, vacunaciones.numero_dosis 
                  FROM vacunaciones 
                  JOIN vacuna_tipo ON vacunaciones.tipo_id = vacuna_tipo.id
                  WHERE vacunaciones.id_nino = ?";
$stmt_vacunas = $conn->prepare($query_vacunas);
$stmt_vacunas->bind_param("i", $id_infante);
$stmt_vacunas->execute();
$result_vacunas = $stmt_vacunas->get_result();
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
                <h1 class="mt-4 mb-4">Datos</h1>

                <div class="row">
                    <!-- Datos del infante -->
                    <div class="col-md-6">
                        <h4>Datos infante</h4>
                        <p><strong>Nombre :</strong> <?php echo htmlspecialchars($infante['nombre'] . ' ' . $infante['apellido_paterno'] . ' ' . $infante['apellido_materno']); ?></p>
                        <p><strong>Fecha Nac :</strong> <?php echo date("d M Y", strtotime($infante['fecha_nacimiento'])); ?></p>
                        <p><strong>Meses :</strong> <?php echo $edad_meses; ?></p>
                        <p><strong>CI :</strong> <?php echo htmlspecialchars($infante['numero_cedula_identidad']); ?></p>
                    </div>

                    <!-- Datos del responsable -->
                    <div class="col-md-6">
                        <h4>Datos responsable</h4>
                        <p><strong>Nombre :</strong> <?php echo htmlspecialchars($infante['nombre_responsable'] . ' ' . $infante['apellido_paterno_tutor'] . ' ' . $infante['apellido_materno_tutor']); ?></p>
                        <p><strong>Relación :</strong> <?php echo htmlspecialchars($infante['relacion']); ?></p>
                        <p><strong>Celular :</strong> <?php echo htmlspecialchars($infante['telefono_tutor']); ?></p>
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
                            <?php
                            // Organizar las vacunas y sus dosis en un arreglo
                            $vacunas = [];
                            while ($row_vacuna = $result_vacunas->fetch_assoc()) {
                                $vacuna = $row_vacuna['vacuna'];
                                $dosis = $row_vacuna['numero_dosis'];
                                $fecha = $row_vacuna['fecha_administracion'];
                                $vacunas[$vacuna][$dosis] = $fecha;
                            }

                            // Mostrar las vacunas y sus dosis
                            foreach ($vacunas as $vacuna => $dosis) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($vacuna) . "</td>";
                                for ($i = 1; $i <= 4; $i++) {
                                    echo "<td>" . (isset($dosis[$i]) ? date("d - M", strtotime($dosis[$i])) : '-') . "</td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Botones adicionales -->
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalRegistrarVacuna">Registrar Vacuna</button>
                    <a href="calendario_infante.php?id=<?php echo $id_infante; ?>" class="btn btn-outline-secondary">Calendario</a>
                </div>

                <!-- Modal para registrar vacuna -->
                <div class="modal fade" id="modalRegistrarVacuna" tabindex="-1" aria-labelledby="modalRegistrarVacunaLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="guardar_vacunacion.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalRegistrarVacunaLabel">Registrar Vacuna</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_infante" value="<?php echo $id_infante; ?>">
                                    <p><strong>Nombre del Infante:</strong> <?php echo htmlspecialchars($infante['nombre'] . ' ' . $infante['apellido_paterno'] . ' ' . $infante['apellido_materno']); ?></p>
                                    
                                    <!-- Selección de tipo de vacuna -->
                                    <div class="mb-3">
                                        <label for="tipo_id" class="form-label">Tipo de Vacuna</label>
                                        <select class="form-select" id="tipo_id" name="tipo_id" required>
                                            <option value="" disabled selected>Seleccione una vacuna</option>
                                            <?php
                                            $query_vacunas = "SELECT id, tipo FROM vacuna_tipo";
                                            $result_vacunas = $conn->query($query_vacunas);
                                            while ($row_vacuna = $result_vacunas->fetch_assoc()) {
                                                echo "<option value='{$row_vacuna['id']}'>{$row_vacuna['tipo']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Selección del personal que administra -->
                                    <div class="mb-3">
                                        <label for="id_personal" class="form-label">Personal que Administra</label>
                                        <select class="form-select" id="id_personal" name="id_personal" required>
                                            <option value="" disabled selected>Seleccione el personal</option>
                                            <?php
                                            $query_personal = "SELECT id, nombre, apellido FROM personal";
                                            $result_personal = $conn->query($query_personal);
                                            while ($row_personal = $result_personal->fetch_assoc()) {
                                                echo "<option value='{$row_personal['id']}'>{$row_personal['nombre']} {$row_personal['apellido']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Fecha de administración -->
                                    <div class="mb-3">
                                        <label for="fecha_administracion" class="form-label">Fecha de Administración</label>
                                        <input type="date" class="form-control" id="fecha_administracion" name="fecha_administracion" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
