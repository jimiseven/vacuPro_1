<?php
include_once 'conexion.php';
include_once 'proteger.php';

// Obtener la lista de infantes
$query_infantes = "SELECT id, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo FROM niños";
$result_infantes = $conn->query($query_infantes);

// Obtener la lista de tipos de vacuna
$query_vacunas = "SELECT id, tipo FROM vacuna_tipo";
$result_vacunas = $conn->query($query_vacunas);

// Obtener la lista de personal responsable
$query_personal = "SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM personal";
$result_personal = $conn->query($query_personal);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Vacunación</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function actualizarDosis() {
            const infanteId = document.getElementById("id_nino").value;
            const vacunaId = document.getElementById("tipo_id").value;

            if (infanteId && vacunaId) {
                fetch(`obtener_dosis.php?id_nino=${infanteId}&tipo_id=${vacunaId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("numero_dosis").value = data.siguiente_dosis;
                    });
            }
        }
    </script>
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
                <h1 class="mt-4 mb-4">Registro de Vacunación</h1>

                <form action="guardar_vacunacion.php" method="post">
                    <div class="mb-3">
                        <label for="id_nino" class="form-label">Infante</label>
                        <select class="form-select" id="id_nino" name="id_nino" onchange="actualizarDosis()" required>
                            <option selected disabled>Seleccione un infante</option>
                            <?php while ($row = $result_infantes->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre_completo']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_id" class="form-label">Tipo de Vacuna</label>
                        <select class="form-select" id="tipo_id" name="tipo_id" onchange="actualizarDosis()" required>
                            <option selected disabled>Seleccione un tipo de vacuna</option>
                            <?php while ($row = $result_vacunas->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['tipo']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="numero_dosis" class="form-label">Número de Dosis</label>
                        <input type="number" class="form-control" id="numero_dosis" name="numero_dosis" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_administracion" class="form-label">Fecha de Administración</label>
                        <input type="date" class="form-control" id="fecha_administracion" name="fecha_administracion" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_personal" class="form-label">Personal Responsable</label>
                        <select class="form-select" id="id_personal" name="id_personal" required>
                            <option selected disabled>Seleccione el personal responsable</option>
                            <?php while ($row = $result_personal->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre_completo']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="index.html" class="btn btn-outline-secondary">Atrás</a>
                        <button type="submit" class="btn btn-primary">Registrar Vacunación</button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>