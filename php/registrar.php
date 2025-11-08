<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $nombre = trim($_POST['nombre']);
    $correo = $_POST["correo"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $ciudad = $_POST["ciudad"] ?? '';

    // Validar que no estén vacíos
    if (!empty($correo) && !empty($telefono) && !empty($ciudad)) {
        $sql = "INSERT INTO usuarios (nombre, correo, telefono, ciudad) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $correo, $telefono, $ciudad);

     
        if ($stmt->execute()) {
            echo " Registro exitoso.";
        } else {
            echo "Error al registrar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo " Todos los campos son obligatorios.";
    }
}

$conn->close();
?>
