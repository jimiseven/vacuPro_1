<?php
require 'vendor/autoload.php'; // Incluir Composer
use Dompdf\Dompdf;

// Incluir la conexión a la base de datos
include_once 'conexion.php';
include_once 'proteger.php';

// Consulta para obtener todas las vacunas administradas, ordenadas por fecha ASC (de la más antigua a la más nueva)
$query_vacunas = "SELECT 
                    vacuna_tipo.tipo AS vacuna,
                    vacunaciones.fecha_administracion AS fecha_vacunacion
                  FROM vacunaciones
                  JOIN vacuna_tipo ON vacunaciones.tipo_id = vacuna_tipo.id
                  ORDER BY vacunaciones.fecha_administracion ASC";
$result_vacunas = $conn->query($query_vacunas);

// Consulta para obtener el total de vacunas y el desglose por tipo
$query_totales = "SELECT 
                    vacuna_tipo.tipo AS vacuna,
                    COUNT(vacunaciones.id) AS total_por_vacuna
                  FROM vacunaciones
                  JOIN vacuna_tipo ON vacunaciones.tipo_id = vacuna_tipo.id
                  GROUP BY vacuna_tipo.tipo";
$result_totales = $conn->query($query_totales);

// Calcular el total de vacunas administradas
$total_vacunas = 0;
$desglose_vacunas = [];
while ($row_total = $result_totales->fetch_assoc()) {
    $desglose_vacunas[] = $row_total;
    $total_vacunas += $row_total['total_por_vacuna'];
}

// Generar el contenido del PDF
$html = '<h1 style="text-align: center; color: #0056b3;">Reporte General de Vacunas</h1>';
$html .= '<p style="text-align: center;">Generado el: ' . date('d-m-Y H:i:s') . '</p>';
$html .= '<p style="text-align: center;">Total de Vacunas Administradas: <strong>' . $total_vacunas . '</strong></p>';

// Desglose por tipo de vacuna
$html .= '<h2 style="margin-top: 20px; color: #0056b3;">Desglose por Vacuna</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Vacuna</th>
                    <th>Total Administradas</th>
                </tr>
            </thead>
            <tbody>';
foreach ($desglose_vacunas as $vacuna) {
    $html .= '<tr>
                <td>' . htmlspecialchars($vacuna['vacuna']) . '</td>
                <td style="text-align: center;">' . $vacuna['total_por_vacuna'] . '</td>
              </tr>';
}
$html .= '</tbody></table>';

// Listado general de todas las vacunas administradas
$html .= '<h2 style="margin-top: 20px; color: #0056b3;">Listado de Vacunas Administradas</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>#</th>
                    <th>Vacuna</th>
                    <th>Fecha Vacunación</th>
                </tr>
            </thead>
            <tbody>';

$numero = 1; // Contador para la numeración
while ($row_vacuna = $result_vacunas->fetch_assoc()) {
    $html .= '<tr>
                <td style="text-align: center;">' . $numero++ . '</td>
                <td>' . htmlspecialchars($row_vacuna['vacuna']) . '</td>
                <td>' . date("d - M - Y", strtotime($row_vacuna['fecha_vacunacion'])) . '</td>
              </tr>';
}

if ($result_vacunas->num_rows === 0) {
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
$dompdf->stream("reporte_general_vacunas.pdf", ["Attachment" => true]);
