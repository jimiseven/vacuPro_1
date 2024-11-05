<?php
include_once 'conexion.php';

// Obtener el id del infante desde la URL
$id_infante = $_GET['id'];

// Obtener los datos del infante
$query_infante = "SELECT nombre, apellido_paterno, apellido_materno, fecha_nacimiento, numero_cedula_identidad FROM niños WHERE id = '$id_infante'";
$result_infante = $conn->query($query_infante);
$infante = $result_infante->fetch_assoc();

// Obtener la lista de vacunas
$query_vacunas = "SELECT id, nombre FROM vacunas";
$result_vacunas = $conn->query($query_vacunas);

// Obtener la lista de personal
$query_personal = "SELECT id, nombre, apellido FROM personal";
$result_personal = $conn->query($query_personal);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Vacuna</title>
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
                            <a class="nav-link active" href="index.html">Vacunas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="infantes.php">Infantes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="personal.php">Personal</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h1 class="mt-4 mb-4">Registro de Vacuna</h1>
                <div class="card p-4">
                    <h5>Datos del Infante</h5>
                    <p><strong>Nombre:</strong> <?php echo $infante['nombre'] . ' ' . $infante['apellido_paterno'] . ' ' . $infante['apellido_materno']; ?></p>
                    <p><strong>Fecha de Nacimiento:</strong> <?php echo date("d M Y", strtotime($infante['fecha_nacimiento'])); ?></p>
                    <p><strong>CI:</strong> <?php echo $infante['numero_cedula_identidad']; ?></p>

                    <form action="guardar_vacuna.php" method="post">
                        <!-- Campo oculto para enviar el id del infante -->
                        <input type="hidden" name="id_infante" value="<?php echo $id_infante; ?>">

                        <div class="mb-3">
                            <label for="id_vacuna" class="form-label">Vacuna</label>
                            <select name="id_vacuna" id="id_vacuna" class="form-select" required>
                                <option selected disabled>Selecciona una vacuna</option>
                                <?php while ($vacuna = $result_vacunas->fetch_assoc()) : ?>
                                    <option value="<?php echo $vacuna['id']; ?>"><?php echo $vacuna['nombre']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_administracion" class="form-label">Fecha de Administración</label>
                            <input type="date" class="form-control" id="fecha_administracion" name="fecha_administracion" required>
                        </div>

                        <div class="mb-3">
                            <label for="id_personal" class="form-label">Personal Responsable</label>
                            <select name="id_personal" id="id_personal" class="form-select" required>
                                <option selected disabled>Selecciona al personal</option>
                                <?php while ($personal = $result_personal->fetch_assoc()) : ?>
                                    <option value="<?php echo $personal['id']; ?>"><?php echo $personal['nombre'] . ' ' . $personal['apellido']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_proxima_dosis" class="form-label">Fecha Próxima Dosis (opcional)</label>
                            <input type="date" class="form-control" id="fecha_proxima_dosis" name="fecha_proxima_dosis">
                        </div>

                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
