<?php
// Conectar a la base de datos (ajusta los parámetros según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos enviados desde el formulario
$caso_id = $_POST['caso_id'];
$usuario_id = $_POST['usuario_id'];
$juez_id = $_POST['juez_id'];
$razon = $conn->real_escape_string($_POST['razon']);
$fecha = $_POST['fecha'];
$estado = "Pendiente";
$respuesta = "Sin Respuesta";

$sql = "INSERT INTO solicitudes (caso_id ,usuario_id, juez_id, razon, fecha_sugerida, estado, respuesta)
        VALUES ('$caso_id','$usuario_id', '$juez_id', '$razon', '$fecha', '$estado', '$respuesta')";




if ($conn->query($sql) === TRUE) {
    header("Location: buscar_audiencias.php?mensaje=exito");
} else {
    echo "Error al guardar los datos: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>

