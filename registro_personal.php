<?php
include_once 'proteger.php'; 
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Personal</title>
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
                <h1 class="mt-4 mb-4">Registro Personal</h1>

                <form action="guardar_personal.php" method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="cedula_identidad" class="form-label">Carnet de Identidad</label>
                        <input type="text" class="form-control" id="cedula_identidad" name="cedula_identidad" required>
                    </div>
                    <div class="mb-3">
                        <label for="celular" class="form-label">Celular</label>
                        <input type="text" class="form-control" id="celular" name="celular" required>
                    </div>
                    <div class="mb-3">
                        <label for="puesto" class="form-label">Puesto</label>
                        <select class="form-select" id="puesto" name="puesto" required>
                            <option selected disabled>Selecciona un puesto</option>
                            <option value="Medico">Médico</option>
                            <option value="Enfermero">Enfermero</option>
                            <option value="Asistente">Asistente</option>
                            <!-- Agrega más opciones de puesto según lo necesites -->
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="personal.php" class="btn btn-outline-secondary">Atrás</a>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>