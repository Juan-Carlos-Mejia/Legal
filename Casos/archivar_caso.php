<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "legalcc";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha recibido la referencia del caso
if (isset($_GET['referencia'])) {
    $referencia = $_GET['referencia'];
    $download_dir = 'Documentos_Archivados/'; // Directorio para guardar los archivos descargados
    if (!is_dir($download_dir)) {
        mkdir($download_dir, 0777, true); // Crear el directorio si no existe
    }

    // Obtener los datos del caso desde la tabla 'casos'
    $sql = "SELECT * FROM casos WHERE referencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $referencia);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Calcular la fecha de expiración (12 meses después de la fecha de creación)
        $fecha_creacion = $row['fecha_creacion'];
        $fecha_expiracion = date('Y-m-d', strtotime($fecha_creacion . ' +12 months'));

        // Insertar el caso en la tabla 'casos_archivados'
        $sql_archivar = "INSERT INTO casos_archivados (referencia, victima, imputado, tipo_delito, archivos_documento, fecha_creacion, fecha_expiracion) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)
                         ON DUPLICATE KEY UPDATE 
                         victima = VALUES(victima),
                         imputado = VALUES(imputado),
                         tipo_delito = VALUES(tipo_delito),
                         archivos_documento = VALUES(archivos_documento),
                         fecha_creacion = VALUES(fecha_creacion),
                         fecha_expiracion = VALUES(fecha_expiracion)";
        $stmt_archivar = $conn->prepare($sql_archivar);
        $stmt_archivar->bind_param("sssssss", $row['referencia'], $row['victima'], $row['imputado'], $row['tipo_delito'], $row['archivos_documento'], $fecha_creacion, $fecha_expiracion);
        $stmt_archivar->execute();

        // Descargar documentos
        $sql_documentos = "SELECT nombre_archivo FROM documentos WHERE caso_referencia = ?";
        $stmt_documentos = $conn->prepare($sql_documentos);
        $stmt_documentos->bind_param("s", $referencia);
        $stmt_documentos->execute();
        $result_documentos = $stmt_documentos->get_result();

        while ($doc = $result_documentos->fetch_assoc()) {
            $archivo = 'documentos/' . $doc['nombre_archivo'];
            if (file_exists($archivo)) {
                copy($archivo, $download_dir . $doc['nombre_archivo']);
            }
        }

        // Descargar evidencias
        $sql_evidencias = "SELECT nombre_archivo FROM evidencias WHERE caso_referencia = ?";
        $stmt_evidencias = $conn->prepare($sql_evidencias);
        $stmt_evidencias->bind_param("s", $referencia);
        $stmt_evidencias->execute();
        $result_evidencias = $stmt_evidencias->get_result();

        while ($evi = $result_evidencias->fetch_assoc()) {
            $archivo = 'uploads/' . $evi['nombre_archivo'];
            if (file_exists($archivo)) {
                copy($archivo, $download_dir . $evi['nombre_archivo']);
            }
        }

        // Eliminar documentos
        $sql_eliminar_documentos = "DELETE FROM documentos WHERE caso_referencia = ?";
        $stmt_eliminar_documentos = $conn->prepare($sql_eliminar_documentos);
        $stmt_eliminar_documentos->bind_param("s", $referencia);
        $stmt_eliminar_documentos->execute();

        // Eliminar evidencias
        $sql_eliminar_evidencias = "DELETE FROM evidencias WHERE caso_referencia = ?";
        $stmt_eliminar_evidencias = $conn->prepare($sql_eliminar_evidencias);
        $stmt_eliminar_evidencias->bind_param("s", $referencia);
        $stmt_eliminar_evidencias->execute();

        // Eliminar el caso de la tabla 'casos'
        $sql_eliminar = "DELETE FROM casos WHERE referencia = ?";
        $stmt_eliminar = $conn->prepare($sql_eliminar);
        $stmt_eliminar->bind_param("s", $referencia);
        $stmt_eliminar->execute();

        // Redirigir de vuelta a la página principal
        header("Location: Buscar_Casos.php");
        exit();
    } else {
        echo "Caso no encontrado.";
    }
}
?>



