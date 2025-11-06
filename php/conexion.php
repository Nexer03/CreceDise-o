<?php
$servername = "127.0.0.1";
$username = "root";    
$password = "121318";        
$database = "CreceDiseño"; 


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexión exitosa";
}
?>
