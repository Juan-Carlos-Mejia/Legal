<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root"; // tu usuario de MySQL
$password = ""; // tu contraseña de MySQL
$dbname = "legalcc"; // nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Datos para la encriptación
$encryption_key = 'LegalCC';  // Clave de cifrado
$ciphering = "AES-128-CTR";   // Algoritmo de cifrado
$options = 0;                 // Opciones para openssl
$encryption_iv = '1234567891011121';  // Vector de inicialización (IV)


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $casos = $_POST['casos'];
    $seleccionardeclaracion = $_POST['seleccionardeclaracion'];
    
    // Encriptar los datos personales
    $apellido_encriptado = openssl_encrypt($apellido, $ciphering, $encryption_key, $options, $encryption_iv);
    $nombre_encriptado = openssl_encrypt($nombre, $ciphering, $encryption_key, $options, $encryption_iv);

    // Variables para almacenar los archivos o texto
    $documento_encriptado = $audio_encriptado = $video_encriptado = $declaracion_encriptada = null;

    // Manejar el archivo según el tipo de declaración seleccionado
    if ($seleccionardeclaracion == 'documento' && isset($_FILES['documento'])) {
        $documento_nombres = [];
        foreach ($_FILES['documento']['name'] as $nombre_archivo) {
            // Procesar cada archivo, guardarlo y luego encriptar el nombre
            $documento_nombres[] = openssl_encrypt($nombre_archivo, $ciphering, $encryption_key, $options, $encryption_iv);
        }
        $documento_encriptado = json_encode($documento_nombres);
    } elseif ($seleccionardeclaracion == 'audio' && isset($_FILES['audio'])) {
        $audio_nombres = [];
        foreach ($_FILES['audio']['name'] as $nombre_archivo) {
            $audio_nombres[] = openssl_encrypt($nombre_archivo, $ciphering, $encryption_key, $options, $encryption_iv);
        }
        $audio_encriptado = json_encode($audio_nombres);
    } elseif ($seleccionardeclaracion == 'video' && isset($_FILES['video'])) {
        $video_nombres = [];
        foreach ($_FILES['video']['name'] as $nombre_archivo) {
            $video_nombres[] = openssl_encrypt($nombre_archivo, $ciphering, $encryption_key, $options, $encryption_iv);
        }
        $video_encriptado = json_encode($video_nombres);
    } elseif ($seleccionardeclaracion == 'texto' && !empty($_POST['declaracion'])) {
        $declaracion = $_POST['declaracion'];
        $declaracion_encriptada = openssl_encrypt($declaracion, $ciphering, $encryption_key, $options, $encryption_iv);
    }

    // Preparar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO declaraciones (apellido, nombre, caso_id, tipo_declaracion, documento, audio, video, texto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssss", $apellido_encriptado, $nombre_encriptado, $casos, $seleccionardeclaracion, $documento_encriptado, $audio_encriptado, $video_encriptado, $declaracion_encriptada);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        header("Location: /Casos/victima/tabla_de_victima.php?mezage=exito");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
}

$conn->close();
?>
