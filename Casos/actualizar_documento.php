<?php
// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['documento']['name'])) {
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

    // Obtener la referencia del caso
    $referencia = $_POST['referencia'];

    // Consultar si ya existe un documento para este caso
    $sql_check = "SELECT ubicacion_archivo FROM documentos WHERE caso_referencia = '$referencia' AND archivado = 0";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // Si existe un documento, eliminar el archivo del sistema y el registro de la base de datos
        $row = $result->fetch_assoc();
        $archivo_existente = $row['ubicacion_archivo'];

        if (file_exists($archivo_existente)) {
            unlink($archivo_existente);  // Eliminar el archivo del sistema
        }

        // Eliminar el registro del documento anterior
        $sql_delete = "DELETE FROM documentos WHERE caso_referencia = '$referencia' AND archivado = 0";
        $conn->query($sql_delete);
    }

    // Directorio donde se guardará el nuevo documento
    $targetDir = "documentos/";

    // Nombre del archivo de documento
    $fileName = basename($_FILES['documento']['name']);
    $targetFilePath = $targetDir . $fileName;

    // Guardar el documento en el directorio
    if (move_uploaded_file($_FILES["documento"]["tmp_name"], $targetFilePath)) {
        // Obtener el tipo de archivo
        $fileType = $_FILES['documento']['type'];

        // Insertar el nuevo documento en la tabla documentos
        $sql_insert = "INSERT INTO documentos (caso_referencia, nombre_archivo, tipo_archivo, ubicacion_archivo, archivado) 
                       VALUES ('$referencia', '$fileName', '$fileType', '$targetFilePath', 0)";

        if ($conn->query($sql_insert) === TRUE) {
            header("Location: /Casos/editar_caso.php?referencia=".$referencia); 
        } else {
            echo "Error al registrar el documento: " . $conn->error;
        }
    } else {
        echo "Error al cargar el archivo de documento.";
    }
    



    $conn->close();
} else {
    echo "No se recibieron datos del formulario o no se seleccionó un archivo.";
}
?>



