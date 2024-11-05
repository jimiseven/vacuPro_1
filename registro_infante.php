<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Infante</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Archivo CSS personalizado, si necesitas más estilos -->
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
                <h1 class="mt-4 mb-4">Registro de infante</h1>
                <form action="guardar_infante.php" method="POST">
                    <div class="row">
                        <!-- Datos del niño -->
                        <div class="col-md-6">
                            <h4>Datos niño</h4>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno">
                            </div>
                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                            </div>
                            <div class="mb-3">
                                <label for="cedula_identidad" class="form-label">Carnet de Identidad</label>
                                <input type="text" class="form-control" id="cedula_identidad" name="cedula_identidad">
                            </div>
                            <div class="mb-3">
                                <label for="acta_nacimiento" class="form-label">Acta de Nacimiento</label>
                                <input type="text" class="form-control" id="acta_nacimiento" name="acta_nacimiento">
                            </div>
                        </div>

                        <!-- Datos del responsable -->
                        <div class="col-md-6">
                            <h4>Datos responsable</h4>
                            <div class="mb-3">
                                <label for="nombre_responsable" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombre_responsable" name="nombre_responsable" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido_paterno_responsable" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellido_paterno_responsable" name="apellido_paterno_responsable" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido_materno_responsable" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellido_materno_responsable" name="apellido_materno_responsable">
                            </div>
                            <div class="mb-3">
                                <label for="cedula_identidad_responsable" class="form-label">Carnet de Identidad</label>
                                <input type="text" class="form-control" id="cedula_identidad_responsable" name="cedula_identidad_responsable">
                            </div>
                            <div class="mb-3">
                                <label for="celular_responsable" class="form-label">Celular</label>
                                <input type="text" class="form-control" id="celular_responsable" name="celular_responsable">
                            </div>
                            <div class="mb-3">
                                <label for="relacion" class="form-label">Relación con el Infante</label>
                                <select class="form-select" id="relacion" name="relacion" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="Padre">Padre</option>
                                    <option value="Madre">Madre</option>
                                    <option value="Tutor">Tutor</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Botones de acción -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                        <a href="infantes.php" class="btn btn-secondary">Atrás</a>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
