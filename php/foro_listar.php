<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

require __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$limit  = max(1, min(50, intval($_GET['limit']  ?? 20)));
$offset = max(0,           intval($_GET['offset'] ?? 0));

$sql = "SELECT id, autor, contenido, creado_en
        FROM foro_mensajes
        ORDER BY creado_en DESC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'msg' => 'Error al preparar sentencia']);
  exit;
}
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
  $row['autor']     = htmlspecialchars($row['autor'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  $row['contenido'] = htmlspecialchars($row['contenido'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  $items[] = $row;
}
$stmt->close();

echo json_encode(['ok' => true, 'items' => $items]);
