<?php
include_once 'conexion.php';
include_once 'proteger.php';

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$cedula_identidad = $_POST['cedula_identidad'];
$acta_nacimiento = $_POST['acta_nacimiento'];

$nombre_responsable = $_POST['nombre_responsable'];
$apellido_paterno_responsable = $_POST['apellido_paterno_responsable'];
$apellido_materno_responsable = $_POST['apellido_materno_responsable'];
$cedula_identidad_responsable = $_POST['cedula_identidad_responsable'];
$celular_responsable = $_POST['celular_responsable'];
$relacion = $_POST['relacion'];

// Insertar datos en la tabla niños
$sql_nino = "INSERT INTO niños (nombre, apellido_paterno, apellido_materno, fecha_nacimiento, numero_cedula_identidad, numero_acta_nacimiento, nombre_responsable, apellido_paterno_tutor, apellido_materno_tutor, numero_cedula_tutor, telefono_tutor, relacion)
             VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$fecha_nacimiento', '$cedula_identidad', '$acta_nacimiento', '$nombre_responsable', '$apellido_paterno_responsable', '$apellido_materno_responsable', '$cedula_identidad_responsable', '$celular_responsable', '$relacion')";

$registro_exitoso = false;
if ($conn->query($sql_nino) === TRUE) {
    $registro_exitoso = true;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Infante</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php if ($registro_exitoso): ?>
    <!-- Modal de éxito -->
    <div class="modal fade" id="registroExitosoModal" tabindex="-1" aria-labelledby="registroExitosoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroExitosoModalLabel">Registro Exitoso</h5>
                </div>
                <div class="modal-body">
                    El infante ha sido registrado correctamente.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="cerrarModal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript para mostrar el modal y redirigir -->
    <script>
        // Mostrar el modal al cargar la página
        document.addEventListener("DOMContentLoaded", function() {
            var registroModal = new bootstrap.Modal(document.getElementById('registroExitosoModal'));
            registroModal.show();
            
            // Redirigir al listado de infantes después de 3 segundos
            setTimeout(function() {
                window.location.href = 'infantes.php';
            }, 3000);

            // También cerrar y redirigir si se hace clic en "Aceptar"
            document.getElementById('cerrarModal').addEventListener('click', function() {
                window.location.href = 'infantes.php';
            });
        });
    </script>
<?php else: ?>
    <!-- Mensaje de error si el registro falla -->
    <div class="alert alert-danger" role="alert">
        Hubo un error al registrar el infante. Por favor, inténtelo de nuevo.
    </div>
    <script>
        // Redirigir al formulario de registro después de 3 segundos en caso de error
        setTimeout(function() {
            window.location.href = 'registro_infante.php';
        }, 3000);
    </script>
<?php endif; ?>

</body>
</html>
