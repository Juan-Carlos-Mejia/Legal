<?php
// Incluir la conexión a la base de datos
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
    // Obtener los valores del formulario
    $id = $_POST['id'];
    $usuario_id = $_POST['usuario_id'];
    $juez_id = $_POST['juez_id'];
    $razon = $_POST['razon'];
    $fecha_sugerida = $_POST['fecha_sugerida'];
    $estado = $_POST['estado'];
    $caso_id = $_POST['caso_id'];
    $respuesta = $_POST['respuesta'];

    // Preparar la consulta SQL para actualizar los datos
    $sql = "UPDATE solicitudes SET usuario_id=?, juez_id=?, razon=?, fecha_sugerida=?, estado=?, respuesta=?, caso_id=? WHERE id=?";

    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("iissssii", $usuario_id, $juez_id, $razon, $fecha_sugerida, $estado, $respuesta, $caso_id, $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a una página de confirmación o mostrar un mensaje de éxito
            header("Location: ver_solicitudes.php?mensaje=exito");
        } else {
            echo "Error al actualizar la solicitud: " . $conn->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Método no permitido.";
}

?>
