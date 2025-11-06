<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexion.php';

$autor     = trim($_POST['autor'] ?? '');
$contenido = trim($_POST['contenido'] ?? '');
$mensajeId = intval($_POST['mensaje_id'] ?? 0);

if ($mensajeId <= 0 || mb_strlen($autor) < 2 || mb_strlen($contenido) < 3) {
  echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
  exit;
}

$stmt = $conn->prepare("INSERT INTO foro_respuestas (mensaje_id, autor, contenido) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $mensajeId, $autor, $contenido);

if ($stmt->execute()) {
  $id = $stmt->insert_id;
  $row = [
    'ok'        => true,
    'id'        => $id,
    'mensaje_id'=> $mensajeId,
    'autor'     => $autor,
    'contenido' => $contenido,
    'creado_en' => date('Y-m-d H:i:s')
  ];
  echo json_encode($row);
} else {
  echo json_encode(['ok' => false, 'error' => 'No se pudo guardar']);
}
$stmt->close();
$conn->close();
