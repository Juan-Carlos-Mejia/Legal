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

// Obtener los mensajes entre el usuario logueado y el usuario seleccionado
$remitente_id = $_SESSION['user_id'];
$destinatario_id = $_GET['usuario_id'];

$sql = "SELECT remitente_id, mensaje, fecha_envio FROM mensajes 
        WHERE (remitente_id = '$remitente_id' AND destinatario_id = '$destinatario_id') 
        OR (remitente_id = '$destinatario_id' AND destinatario_id = '$remitente_id')
        ORDER BY fecha_envio ASC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['remitente_id'] == $remitente_id) {
            echo '<div class="message sent">Tú: ' . $row['mensaje'] . '</div>';
        } else {
            echo '<div class="message received">Ellos: ' . $row['mensaje'] . '</div>';
        }
    }
} else {
    echo "No hay mensajes.";
}

$conn->close();
?>
