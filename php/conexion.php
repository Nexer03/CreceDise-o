<?php
date_default_timezone_set('America/Mexico_City');

$servername = "127.0.0.1";
$username = "root";    
$password = "121318";        
$database = "crecediseño"; 


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}else
{
    // echo "Conexión exitosa a la base de datos.";
}
mysqli_set_charset($conn, "utf8mb4");
?>
