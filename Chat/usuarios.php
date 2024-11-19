<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
session_start();
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, nombre, apellido FROM usuarios WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $nombreCompleto = $row["nombre"] . " " . $row["apellido"];
        echo '<div class="user" onclick="selectUser(' . $row["id"] . ', \'' . addslashes($nombreCompleto) . '\')">';
        echo $nombreCompleto;
        echo '</div>';
    }
} else {
    echo "No hay usuarios disponibles.";
}

$stmt->close();
$conn->close();
?>
