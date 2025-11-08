<?php
date_default_timezone_set('America/Mexico_City');

$servername = "localhost";
$username = "u121569097_admincrecedise";
$password = "AdminCrece1213.";
$database = "u121569097_crecediseno";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}else
{
    // echo "Conexión exitosa a la base de datos.";
}
mysqli_set_charset($conn, "utf8mb4");
?>
