<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexion.php';

// Verifica sesión del usuario
if (!isset($_SESSION['usuario_id'])) {
  echo json_encode(['ok' => false, 'error' => 'Usuario no autenticado']);
  exit;
}

$usuario_id = intval($_SESSION['usuario_id']);
$mensajeId  = intval($_POST['mensaje_id'] ?? 0);
$contenido  = trim($_POST['contenido'] ?? '');

if ($mensajeId <= 0 || mb_strlen($contenido) < 3) {
  echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
  exit;
}


$stmt = $conn->prepare("
  INSERT INTO foro_respuestas (mensaje_id, usuario_id, contenido)
  VALUES (?, ?, ?)
");
$stmt->bind_param("iis", $mensajeId, $usuario_id, $contenido);

if ($stmt->execute()) {
  $id = $stmt->insert_id;

  // Obtén el nombre del usuario
  $queryUser = $conn->prepare("SELECT nombre FROM usuarios WHERE id = ?");
  $queryUser->bind_param("i", $usuario_id);
  $queryUser->execute();
  $queryUser->bind_result($nombre_usuario);
  $queryUser->fetch();
  $queryUser->close();

  echo json_encode([
    'ok'             => true,
    'id'             => $id,
    'mensaje_id'     => $mensajeId,
    'nombre_usuario' => htmlspecialchars($nombre_usuario, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    'contenido'      => htmlspecialchars($contenido, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    'creado_en'      => date('Y-m-d H:i:s')
  ]);
} else {
  echo json_encode(['ok' => false, 'error' => 'No se pudo guardar']);
}

$stmt->close();
$conn->close();
