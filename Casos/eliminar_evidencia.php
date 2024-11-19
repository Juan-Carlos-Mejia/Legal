<?php
// Verificar si se proporcionó un ID de evidencia válido en la URL
if (isset($_GET['id'])) {
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

    $referencia = $_GET['referencia'];

    $sql = "SELECT * FROM casos WHERE referencia = '$referencia'";
    $result = $conn->query($sql);


    // Obtener el ID de la evidencia desde la URL
    $id_evidencia = $_GET['id'];

    // Consulta para obtener la ubicación del archivo de evidencia
    $sql = "SELECT ubicacion_archivo FROM evidencias WHERE id = '$id_evidencia'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ubicacion_archivo = $row['ubicacion_archivo'];

        // Eliminar la evidencia de la base de datos
        $sql_delete = "DELETE FROM evidencias WHERE id = '$id_evidencia'";
        if ($conn->query($sql_delete) === TRUE) {
            // Eliminar el archivo de evidencia del servidor
            if (unlink($ubicacion_archivo)) {
                // Redirigir al usuario a editar_caso.php
                header("Location: Buscar_casos.php");
                exit();
            } else {
                echo "Error al eliminar el archivo de evidencia del servidor.";
            }
        } else {
            echo "Error al eliminar la evidencia de la base de datos: " . $conn->error;
        }
    } else {
        echo "No se encontró la evidencia en la base de datos.";
    }

    $conn->close();
} else {
    echo "No se proporcionó un ID de evidencia válido.";
}
?>
