<?php
include_once 'conexion.php';

// Verificar si el ID del personal está presente en la URL
$id_personal = $_GET['id'] ?? null;
if (!$id_personal) {
    die("ID del personal no especificado.");
}

// Consulta para obtener los datos del personal
$query = "SELECT id, nombre, apellido, puesto, celular FROM personal WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_personal);
$stmt->execute();
$result = $stmt->get_result();
$personal = $result->fetch_assoc();

if (!$personal) {
    die("No se encontró al personal con el ID especificado.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Personal</title>
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
                            <a class="nav-link" href="index.html">
                                <i class="bi bi-arrow-right-circle me-2"></i> Vacunas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="infantes.php">
                                <i class="bi bi-people me-2"></i> Infantes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="personal.php">
                                <i class="bi bi-person-badge me-2"></i> Personal
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h1 class="mt-4 mb-4">Información del Personal</h1>

                <!-- Información del personal -->
                <div class="mb-4">
                    <p><strong>Nombre:</strong>
                        <?php echo htmlspecialchars($personal['nombre'] . ' ' . $personal['apellido']); ?></p>
                    <p><strong>Puesto:</strong> <?php echo htmlspecialchars($personal['puesto']); ?></p>
                    <p><strong>Celular:</strong> <?php echo htmlspecialchars($personal['celular']); ?></p>
                </div>

                <!-- Botones -->
                <div class="d-flex gap-2">
                    <!-- Botón para eliminar -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#modalEliminarPersonal">
                        Eliminar
                    </button>

                    <!-- Botón para modificar -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalModificarPersonal">
                        Modificar
                    </button>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar al personal -->
    <div class="modal fade" id="modalEliminarPersonal" tabindex="-1" aria-labelledby="modalEliminarPersonalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="modalEliminarPersonalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al personal
                        <strong><?php echo htmlspecialchars($personal['nombre'] . ' ' . $personal['apellido']); ?></strong>?
                        Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="eliminar_personal.php" method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $personal['id']; ?>">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para modificar los datos del personal -->
    <div class="modal fade" id="modalModificarPersonal" tabindex="-1" aria-labelledby="modalModificarPersonalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="modificar_personal.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalModificarPersonalLabel">Modificar Datos del Personal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?php echo $personal['id']; ?>">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                value="<?php echo htmlspecialchars($personal['nombre']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido"
                                value="<?php echo htmlspecialchars($personal['apellido']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="puesto" class="form-label">Puesto</label>
                            <select class="form-select" id="puesto" name="puesto" required>
                                <option value="" disabled>Selecciona un puesto</option>
                                <option value="Médico"
                                    <?php echo $personal['puesto'] === 'Médico' ? 'selected' : ''; ?>>Médico</option>
                                <option value="Enfermero"
                                    <?php echo $personal['puesto'] === 'Enfermero' ? 'selected' : ''; ?>>Enfermero
                                </option>
                                <option value="Asistente"
                                    <?php echo $personal['puesto'] === 'Asistente' ? 'selected' : ''; ?>>Asistente
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular"
                                value="<?php echo htmlspecialchars($personal['celular']); ?>" required>
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


    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>