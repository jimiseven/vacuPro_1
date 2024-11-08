<?php
include_once 'conexion.php';
include_once 'proteger.php';

$id_nino = $_GET['id_nino'];
$tipo_id = $_GET['tipo_id'];

// Consultar el historial de dosis para el infante y tipo de vacuna específico
$query_dosis = "SELECT MAX(numero_dosis) AS ultima_dosis FROM vacunaciones WHERE id_nino = $id_nino AND tipo_id = $tipo_id";
$result_dosis = $conn->query($query_dosis);
$row_dosis = $result_dosis->fetch_assoc();

$siguiente_dosis = isset($row_dosis['ultima_dosis']) ? $row_dosis['ultima_dosis'] + 1 : 1;

echo json_encode(['siguiente_dosis' => $siguiente_dosis]);
