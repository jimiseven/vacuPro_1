<?php
include_once 'conexion.php';
include_once 'proteger.php';

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
                  WHERE vacunaciones.id_nino = ?
                  ORDER BY vacunaciones.numero_dosis";
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
                <h1 class="mt-4 mb-4">Datos del Infante</h1>

                <!-- Información del infante -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Datos del Infante</h4>
                        <p><strong>Nombre:</strong>
                            <?php echo htmlspecialchars($infante['nombre'] . ' ' . $infante['apellido_paterno'] . ' ' . $infante['apellido_materno']); ?>
                        </p>
                        <p><strong>Fecha de Nacimiento:</strong>
                            <?php echo date("d M Y", strtotime($infante['fecha_nacimiento'])); ?></p>
                        <p><strong>Meses:</strong> <?php echo $edad_meses; ?></p>
                        <p><strong>Cédula de Identidad:</strong>
                            <?php echo htmlspecialchars($infante['numero_cedula_identidad']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Datos del Responsable</h4>
                        <p><strong>Nombre:</strong>
                            <?php echo htmlspecialchars($infante['nombre_responsable'] . ' ' . $infante['apellido_paterno_tutor'] . ' ' . $infante['apellido_materno_tutor']); ?>
                        </p>
                        <p><strong>Relación:</strong> <?php echo htmlspecialchars($infante['relacion']); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($infante['telefono_tutor']); ?></p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="mt-4 d-flex gap-2">
                    <a href="calendario_infante.php?id=<?php echo $id_infante; ?>"
                        class="btn btn-outline-secondary">Calendario</a>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#modalRegistrarVacuna">Registrar Vacuna</button>
                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                        data-bs-target="#modalEditarInfante">Editar Datos</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                        data-bs-target="#modalEliminarInfante">Eliminar Infante</button>
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

                            // Verificar si no hay registros
                            if (empty($vacunas)) {
                                echo "<tr><td colspan='5' class='text-center'>No hay vacunas registradas.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Botón Atrás -->
                <div class="d-flex justify-content-end mt-3">
                    <a href="infantes.php" class="btn btn-secondary">Atrás</a>
                </div>

                <!-- Modal para registrar vacunación -->
                <div class="modal fade" id="modalRegistrarVacuna" tabindex="-1"
                    aria-labelledby="modalRegistrarVacunaLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="guardar_vacunacion.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalRegistrarVacunaLabel">Registrar Vacuna</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_nino" value="<?php echo $id_infante; ?>">
                                    <div class="mb-3">
                                        <label for="tipo_id" class="form-label">Vacuna</label>
                                        <select class="form-select" id="tipo_id" name="tipo_id" required>
                                            <option value="" selected disabled>Seleccione una vacuna</option>
                                            <?php
                                            $query_vacunas = "SELECT id, tipo FROM vacuna_tipo";
                                            $result_vacunas = $conn->query($query_vacunas);
                                            while ($row_vacuna = $result_vacunas->fetch_assoc()) {
                                                echo "<option value='{$row_vacuna['id']}'>{$row_vacuna['tipo']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="id_personal" class="form-label">Personal Responsable</label>
                                        <select class="form-select" id="id_personal" name="id_personal" required>
                                            <option value="" selected disabled>Seleccione el personal</option>
                                            <?php
                                            $query_personal = "SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM personal";
                                            $result_personal = $conn->query($query_personal);
                                            while ($row_personal = $result_personal->fetch_assoc()) {
                                                echo "<option value='{$row_personal['id']}'>{$row_personal['nombre_completo']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_administracion" class="form-label">Fecha de
                                            Administración</label>
                                        <input type="date" class="form-control" id="fecha_administracion"
                                            name="fecha_administracion" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Registrar Vacuna</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal para editar datos del infante -->
                <div class="modal fade" id="modalEditarInfante" tabindex="-1" aria-labelledby="modalEditarInfanteLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="editar_infante.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditarInfanteLabel">Editar Datos del Infante</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                                                value="<?php echo htmlspecialchars($infante['nombre']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                            <input type="text" class="form-control" id="apellido_paterno"
                                                name="apellido_paterno"
                                                value="<?php echo htmlspecialchars($infante['apellido_paterno']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                            <input type="text" class="form-control" id="apellido_materno"
                                                name="apellido_materno"
                                                value="<?php echo htmlspecialchars($infante['apellido_materno']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" id="fecha_nacimiento"
                                                name="fecha_nacimiento"
                                                value="<?php echo htmlspecialchars($infante['fecha_nacimiento']); ?>"
                                                readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="numero_cedula_identidad" class="form-label">Cédula de
                                                Identidad</label>
                                            <input type="text" class="form-control" id="numero_cedula_identidad"
                                                name="numero_cedula_identidad"
                                                value="<?php echo htmlspecialchars($infante['numero_cedula_identidad']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="numero_acta_nacimiento" class="form-label">Número de Acta de
                                                Nacimiento</label>
                                            <input type="text" class="form-control" id="numero_acta_nacimiento"
                                                name="numero_acta_nacimiento"
                                                value="<?php echo htmlspecialchars($infante['numero_acta_nacimiento'] ?? ''); ?>"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Datos del tutor -->
                                    <h5 class="mb-3">Datos del Tutor</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre_responsable" class="form-label">Nombre del Tutor</label>
                                            <input type="text" class="form-control" id="nombre_responsable"
                                                name="nombre_responsable"
                                                value="<?php echo htmlspecialchars($infante['nombre_responsable']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apellido_paterno_tutor" class="form-label">Apellido Paterno del
                                                Tutor</label>
                                            <input type="text" class="form-control" id="apellido_paterno_tutor"
                                                name="apellido_paterno_tutor"
                                                value="<?php echo htmlspecialchars($infante['apellido_paterno_tutor']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apellido_materno_tutor" class="form-label">Apellido Materno del
                                                Tutor</label>
                                            <input type="text" class="form-control" id="apellido_materno_tutor"
                                                name="apellido_materno_tutor"
                                                value="<?php echo htmlspecialchars($infante['apellido_materno_tutor']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="numero_cedula_tutor" class="form-label">Cédula de Identidad del
                                                Tutor</label>
                                            <input type="text" class="form-control" id="numero_cedula_tutor"
                                                name="numero_cedula_tutor"
                                                value="<?php echo htmlspecialchars($infante['numero_cedula_tutor']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="telefono_tutor" class="form-label">Teléfono del Tutor</label>
                                            <input type="text" class="form-control" id="telefono_tutor"
                                                name="telefono_tutor"
                                                value="<?php echo htmlspecialchars($infante['telefono_tutor']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="relacion" class="form-label">Relación con el Infante</label>
                                            <input type="text" class="form-control" id="relacion" name="relacion"
                                                value="<?php echo htmlspecialchars($infante['relacion']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Modal para eliminar infante -->
                <div class="modal fade" id="modalEliminarInfante" tabindex="-1"
                    aria-labelledby="modalEliminarInfanteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="modalEliminarInfanteLabel">Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Estás seguro de que deseas eliminar al infante
                                    <strong><?php echo htmlspecialchars($infante['nombre']); ?></strong> y todos sus
                                    datos relacionados? Esta acción no se puede deshacer.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <form action="eliminar_infante.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $id_infante; ?>">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>