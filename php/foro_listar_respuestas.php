<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexion.php';

$mensajeId = intval($_GET['mensaje_id'] ?? 0);
$limit  = max(1, intval($_GET['limit'] ?? 50));   
$offset = max(0, intval($_GET['offset'] ?? 0));

if ($mensajeId <= 0) {
  echo json_encode(['ok' => false, 'items' => []]);
  exit;
}

$sql = "SELECT id, mensaje_id, autor, contenido, creado_en
        FROM foro_respuestas
        WHERE mensaje_id = ?
        ORDER BY creado_en ASC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $mensajeId, $limit, $offset);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($r = $res->fetch_assoc()) {
  $items[] = $r;
}
echo json_encode(['ok' => true, 'items' => $items]);

$stmt->close();
$conn->close();
