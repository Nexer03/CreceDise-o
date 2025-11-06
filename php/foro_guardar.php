<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

require __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'msg' => 'Método no permitido']);
  exit;
}

$autor     = trim($_POST['autor'] ?? '');
$contenido = trim($_POST['contenido'] ?? '');

if ($autor === '' || $contenido === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'msg' => 'Campos obligatorios']);
  exit;
}
if (mb_strlen($autor) < 2 || mb_strlen($autor) > 80) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'msg' => 'Autor inválido']);
  exit;
}
if (mb_strlen($contenido) < 3 || mb_strlen($contenido) > 2000) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'msg' => 'Contenido inválido']);
  exit;
}

$sql = "INSERT INTO foro_mensajes (autor, contenido) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'msg' => 'Error al preparar sentencia']);
  exit;
}
$stmt->bind_param("ss", $autor, $contenido);

if (!$stmt->execute()) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'msg' => 'Error al guardar']);
  exit;
}

$id = $stmt->insert_id;
$stmt->close();


$autorSafe     = htmlspecialchars($autor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$contenidoSafe = htmlspecialchars($contenido, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

echo json_encode([
  'ok'        => true,
  'id'        => $id,
  'autor'     => $autorSafe,
  'contenido' => $contenidoSafe,
  'creado_en' => date('c')
]);
