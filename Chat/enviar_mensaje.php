<?php
session_start();

// Conectar a la base de datos
$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "legalcc";  

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$remitente_id = $_POST['remitente_id'];
$destinatario_id = $_POST['destinatario_id'];
$mensaje = $_POST['mensaje'];

// Insertar el mensaje en la base de datos
$sql = "INSERT INTO mensajes (remitente_id, destinatario_id, mensaje) 
        VALUES ('$remitente_id', '$destinatario_id', '$mensaje')";

if ($conn->query($sql) === TRUE) {
    echo "Mensaje enviado correctamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
