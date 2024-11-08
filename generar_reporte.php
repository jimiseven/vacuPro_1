<?php
require 'vendor/autoload.php'; // Incluir Composer
use Dompdf\Dompdf;


// Incluir la conexión a la base de datos
include_once 'conexion.php';

// Obtener los filtros aplicados
$filtro_vacuna = $_GET['vacuna'] ?? '';
$filtro_meses = $_GET['meses'] ?? '';

// Construir la consulta base
$query = "SELECT 
            niños.nombre AS infante,
            vacuna_tipo.tipo AS vacuna,
            vacunaciones.fecha_administracion AS fecha_vacunacion
          FROM vacunaciones
          JOIN niños ON vacunaciones.id_nino = niños.id
          JOIN vacuna_tipo ON vacunaciones.tipo_id = vacuna_tipo.id";

// Agregar condiciones según los filtros
$condiciones = [];
$parametros = [];

if ($filtro_vacuna) {
    $condiciones[] = "vacuna_tipo.tipo = ?";
    $parametros[] = $filtro_vacuna;
}

if ($filtro_meses) {
    $condiciones[] = "MONTH(vacunaciones.fecha_administracion) = ?";
    $parametros[] = $filtro_meses;
}

if (!empty($condiciones)) {
    $query .= " WHERE " . implode(" AND ", $condiciones);
}

$query .= " ORDER BY fecha_vacunacion DESC";
$stmt = $conn->prepare($query);

if (!empty($parametros)) {
    $tipos_parametros = str_repeat("s", count($parametros));
    $stmt->bind_param($tipos_parametros, ...$parametros);
}

$stmt->execute();
$result = $stmt->get_result();

// Generar el contenido del PDF
$html = '<h1 style="text-align: center; color: #0056b3;">Reporte de Vacunas</h1>';
$html .= '<p style="text-align: center;">Generado el: ' . date('d-m-Y H:i:s') . '</p>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Infante</th>
                    <th>Vacuna</th>
                    <th>Fecha Vacunación</th>
                </tr>
            </thead>
            <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['infante']) . '</td>
                <td>' . htmlspecialchars($row['vacuna']) . '</td>
                <td>' . date("d - M", strtotime($row['fecha_vacunacion'])) . '</td>
              </tr>';
}

if ($result->num_rows === 0) {
    $html .= '<tr><td colspan="3" style="text-align: center;">No hay registros para mostrar.</td></tr>';
}

$html .= '</tbody></table>';

// Crear una instancia de Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Configurar el tamaño y la orientación del papel
$dompdf->setPaper('A4', 'portrait');

// Renderizar el HTML como PDF
$dompdf->render();

// Enviar el PDF al navegador para descargar
$dompdf->stream("reporte_vacunas.pdf", ["Attachment" => true]);
