<?php
// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['evidencia']['name'])) {
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

    // Directorio donde se guardarán las evidencias
    $targetDir = "uploads/";

    // Recorrer cada archivo de evidencia
    foreach($_FILES['evidencia']['name'] as $key=>$val){
        $fileName = basename($_FILES['evidencia']['name'][$key]);
        $targetFilePath = $targetDir . $fileName;

        // Guardar la evidencia en el directorio
        if(move_uploaded_file($_FILES["evidencia"]["tmp_name"][$key], $targetFilePath)){
            // Insertar la evidencia en la base de datos
            $sql = "INSERT INTO evidencias (caso_referencia, nombre_archivo, ubicacion_archivo) VALUES ('$referencia', '$fileName', '$targetFilePath')";
            if ($conn->query($sql) !== TRUE) {
                echo "Error al insertar evidencia: " . $conn->error;
            } else {
                header("Location: editar_caso.php?referencia=$referencia");
                exit();
            }
        } else {
            header("Location: editar_caso.php?referencia=$referencia");
            echo "Error al cargar el archivo de evidencia.";
        }
    }

    $conn->close();
} else {
    
    echo "No se recibieron datos del formulario o no se seleccionaron archivos.";
}
?>
