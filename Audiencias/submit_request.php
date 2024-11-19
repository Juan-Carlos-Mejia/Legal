<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $razon = $_POST['reason'];
    $fecha_hora = $_POST['fecha_hora'];

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare("INSERT INTO delete_requests (nombre_usuario, razon, fecha_hora) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre_usuario, $razon, $fecha_hora);

    if ($stmt->execute()) {
        $error_message = "Solicitud de reprogramacion enviada";
        header("Location: /Audiencias/Buscar_Audiencias.php?error=" . urlencode($error_message));
        exit();

    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
