<?php
session_start();
include("conexion.php");

header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = trim($_POST['nombre'] ?? '');
    $correo   = trim($_POST["correo"] ?? '');
    $telefono = trim($_POST["telefono"] ?? '');
    $ciudad   = trim($_POST["ciudad"] ?? '');

    if ($nombre === '' || $correo === '' || $telefono === '' || $ciudad === '') {
        echo " Todos los campos son obligatorios.";
        exit;
    }

    $sql = "INSERT INTO usuarios (nombre, correo, telefono, ciudad) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo " Error al preparar la sentencia.";
        exit;
    }

    $stmt->bind_param("ssss", $nombre, $correo, $telefono, $ciudad);

    if ($stmt->execute()) {
       
        $_SESSION['usuario_id'] = $stmt->insert_id;
        $_SESSION['nombre'] = $nombre;

        echo " Registro exitoso.";
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Método no permitido.";
}

$conn->close();
?>
