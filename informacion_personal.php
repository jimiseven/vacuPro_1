<?php
include_once 'conexion.php';

// Obtener el ID del personal desde la URL
$id_personal = $_GET['id'] ?? null;

if ($id_personal) {
    // Consulta para obtener los datos del personal
    $query = "SELECT nombre, apellido, numero_cedula_identidad, celular, puesto 
              FROM personal WHERE id = '$id_personal'";
    $result = $conn->query($query);
    $personal = $result->fetch_assoc();
} else {
    echo "ID del personal no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Personal</title>
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
                            <a class="nav-link" href="personal.php">
                                <i class="bi bi-person-badge me-2"></i> Personal
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h1 class="mt-4 mb-4">Datos Personal</h1>

                <form action="modificar_personal.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id_personal; ?>">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $personal['nombre']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $personal['apellido']; ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="cedula_identidad" class="form-label">Carnet de Identidad</label>
                            <input type="text" class="form-control" id="cedula_identidad" name="cedula_identidad" value="<?php echo $personal['numero_cedula_identidad']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular" value="<?php echo $personal['celular']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="puesto" class="form-label">Puesto</label>
                            <select class="form-select" id="puesto" name="puesto" required>
                                <option <?php echo $personal['puesto'] == 'Medico' ? 'selected' : ''; ?> value="Medico">Médico</option>
                                <option <?php echo $personal['puesto'] == 'Enfermero' ? 'selected' : ''; ?> value="Enfermero">Enfermero</option>
                                <option <?php echo $personal['puesto'] == 'Asistente' ? 'selected' : ''; ?> value="Asistente">Asistente</option>
                                <!-- Agrega más opciones de puesto si es necesario -->
                            </select>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="personal.php" class="btn btn-outline-secondary">Atrás</a>
                        <button type="submit" class="btn btn-primary">Modificar</button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
