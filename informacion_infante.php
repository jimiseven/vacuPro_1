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
                <h1 class="mt-4 mb-4">Datos</h1>

                <!-- Botón para editar datos -->
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarInfante">
                        Editar Datos del Infante
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarInfante">
                        Eliminar Infante
                    </button>
                </div>

                <!-- Notificaciones -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success mt-3">Datos actualizados correctamente.</div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="alert alert-danger mt-3">Hubo un problema al actualizar los datos.</div>
                <?php endif; ?>

                <div class="row mt-4">
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
            </main>
        </div>
    </div>

    <!-- Modal para editar los datos del infante -->
    <div class="modal fade" id="modalEditarInfante" tabindex="-1" aria-labelledby="modalEditarInfanteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="editar_infante.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarInfanteLabel">Editar Datos del Infante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Campo oculto para enviar el ID del infante -->
                        <input type="hidden" name="id" value="<?php echo $infante['id']; ?>">

                        <!-- Datos del infante -->
                        <h5 class="mb-3">Datos del Infante</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    value="<?php echo isset($infante['nombre']) ? htmlspecialchars($infante['nombre']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                                    value="<?php echo isset($infante['apellido_paterno']) ? htmlspecialchars($infante['apellido_paterno']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno"
                                    value="<?php echo isset($infante['apellido_materno']) ? htmlspecialchars($infante['apellido_materno']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                    value="<?php echo isset($infante['fecha_nacimiento']) ? htmlspecialchars($infante['fecha_nacimiento']) : ''; ?>"
                                    readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="numero_cedula_identidad" class="form-label">Cédula de Identidad</label>
                                <input type="text" class="form-control" id="numero_cedula_identidad" name="numero_cedula_identidad"
                                    value="<?php echo isset($infante['numero_cedula_identidad']) ? htmlspecialchars($infante['numero_cedula_identidad']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="numero_acta_nacimiento" class="form-label">Número de Acta de Nacimiento</label>
                                <input type="text" class="form-control" id="numero_acta_nacimiento" name="numero_acta_nacimiento"
                                    value="<?php echo isset($infante['numero_acta_nacimiento']) ? htmlspecialchars($infante['numero_acta_nacimiento']) : ''; ?>"
                                    required>
                            </div>
                        </div>

                        <!-- Datos del tutor -->
                        <h5 class="mb-3">Datos del Tutor</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_responsable" class="form-label">Nombre del Tutor</label>
                                <input type="text" class="form-control" id="nombre_responsable" name="nombre_responsable"
                                    value="<?php echo isset($infante['nombre_responsable']) ? htmlspecialchars($infante['nombre_responsable']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido_paterno_tutor" class="form-label">Apellido Paterno del Tutor</label>
                                <input type="text" class="form-control" id="apellido_paterno_tutor" name="apellido_paterno_tutor"
                                    value="<?php echo isset($infante['apellido_paterno_tutor']) ? htmlspecialchars($infante['apellido_paterno_tutor']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido_materno_tutor" class="form-label">Apellido Materno del Tutor</label>
                                <input type="text" class="form-control" id="apellido_materno_tutor" name="apellido_materno_tutor"
                                    value="<?php echo isset($infante['apellido_materno_tutor']) ? htmlspecialchars($infante['apellido_materno_tutor']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="numero_cedula_tutor" class="form-label">Cédula de Identidad del Tutor</label>
                                <input type="text" class="form-control" id="numero_cedula_tutor" name="numero_cedula_tutor"
                                    value="<?php echo isset($infante['numero_cedula_tutor']) ? htmlspecialchars($infante['numero_cedula_tutor']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono_tutor" class="form-label">Teléfono del Tutor</label>
                                <input type="text" class="form-control" id="telefono_tutor" name="telefono_tutor"
                                    value="<?php echo isset($infante['telefono_tutor']) ? htmlspecialchars($infante['telefono_tutor']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="relacion" class="form-label">Relación con el Infante</label>
                                <input type="text" class="form-control" id="relacion" name="relacion"
                                    value="<?php echo isset($infante['relacion']) ? htmlspecialchars($infante['relacion']) : ''; ?>"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Modal de confirmación para eliminar al infante -->
    <div class="modal fade" id="modalEliminarInfante" tabindex="-1" aria-labelledby="modalEliminarInfanteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="modalEliminarInfanteLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al infante <strong><?php echo htmlspecialchars($infante['nombre'] . ' ' . $infante['apellido_paterno']); ?></strong> y todos sus datos relacionados? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="eliminar_infante.php" method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $infante['id']; ?>">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>