<?php
$servername = "localhost";
$username = "u121569097_admincrecedise";
$password = "Crecediseno1213.";
$database = "u121569097_crecediseno";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} 
mysqli_set_charset($conn, "utf8mb4");
?>
