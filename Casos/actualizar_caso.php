<?php
// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "legalcc";

    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $referencia = $_POST['referencia'];
    $victima = $_POST['victima'];
    $imputado = $_POST['imputado'];
    $tipo_delito = $_POST['tipo_delito'];

    // Consulta para actualizar los datos del caso
    $sql = "UPDATE casos SET victima='$victima', imputado='$imputado', tipo_delito='$tipo_delito' WHERE referencia='$referencia'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ver_detalle_caso.php?referencia=$referencia");
        exit();
    } else {
        echo "Error al actualizar los datos del caso: " . $conn->error;
    }

    $conn->close();
} else {
    echo "No se recibieron datos del formulario.";
}
?>
