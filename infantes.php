<?php
include_once 'conexion.php';
include_once 'proteger.php';

// Verificar la conexión
if (!$conn) {
    die("La conexión no se estableció: " . mysqli_connect_error());
}

// Obtener el término de búsqueda si existe
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Consulta SQL con filtro de búsqueda
$query = "SELECT id, nombre, apellido_paterno, fecha_nacimiento FROM niños";
if (!empty($searchTerm)) {
    $query .= " WHERE nombre LIKE '%$searchTerm%' OR apellido_paterno LIKE '%$searchTerm%'";
}

$result = $conn->query($query);

// Función para calcular la edad en meses
function calcularEdadEnMeses($fechaNacimiento)
{
    $fechaActual = new DateTime();
    $fechaNac = new DateTime($fechaNacimiento);
    $diferencia = $fechaActual->diff($fechaNac);
    return ($diferencia->y * 12) + $diferencia->m;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Infantes</title>
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
                    <h1 class="h2">Infantes</h1>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='registro_infante.php'">Registrar</button>
                </div>

                <!-- Buscador -->
                <form class="input-group mb-3" method="GET" action="infantes.php">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar" aria-label="Buscar" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </form>

                <!-- Tabla de infantes -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Infante</th>
                                <th>Edad (meses)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['nombre'] . ' ' . $row['apellido_paterno']; ?></td>
                                    <td><?php echo calcularEdadEnMeses($row['fecha_nacimiento']); ?></td>
                                    <td><a href="informacion_infante.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Información</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-outline-secondary mt-3">Imprimir</button>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>