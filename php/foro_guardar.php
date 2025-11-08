<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido']);
    exit;
}


if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'Usuario no autenticado']);
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']);
$contenido  = trim($_POST['contenido'] ?? '');

if ($contenido === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'El contenido no puede estar vacío']);
    exit;
}
if (mb_strlen($contenido) < 3 || mb_strlen($contenido) > 2000) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Contenido inválido (3–2000 caracteres)']);
    exit;
}


$sql = "INSERT INTO foro_mensajes (usuario_id, contenido) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Error al preparar sentencia']);
    exit;
}

$stmt->bind_param("is", $usuario_id, $contenido);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Error al guardar mensaje']);
    exit;
}

$id = $stmt->insert_id;
$stmt->close();


$sqlUser = "SELECT nombre FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sqlUser);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nombre);
$stmt->fetch();
$stmt->close();


echo json_encode([
    'ok'             => true,
    'id'             => $id,
    'usuario_id'     => $usuario_id,
    'nombre_usuario' => htmlspecialchars($nombre, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    'contenido'      => htmlspecialchars($contenido, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    'creado_en'      => date('c')
]);
