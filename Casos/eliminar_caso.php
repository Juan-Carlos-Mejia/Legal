<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: Upss.php");
    exit();
}

if (isset($_GET['logout'])) {
  // Verificar si se ha confirmado la salida
  if ($_GET['logout'] == 'confirm') {
      session_destroy(); // Destruir todas las variables de sesión
      header("Location: Cerrardo.php"); // Redirigir al usuario a la página de inicio de sesión
      exit();
  } else {
      // Si no se ha confirmado, redirigir al usuario a esta misma página con un parámetro 'confirm'
      header("Location: {$_SERVER['PHP_SELF']}?logout=confirm");
      exit();
  }
}

// Resto del código aquí (contenido de la página principal)
//___________________________________________HTML Normal_____________________________________________________________________________________
?>

<?php
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

// Función para eliminar un archivo
function eliminarArchivo($ubicacion) {
    if (file_exists($ubicacion)) {
        unlink($ubicacion);
    }
}

// Función para eliminar los documentos asociados al caso
function eliminarDocumentos($referencia, $conn) {
    $sql = "SELECT ubicacion_archivo FROM documentos WHERE caso_referencia='$referencia'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            eliminarArchivo($row["ubicacion_archivo"]);
        }
    }
    // Eliminar los registros de documentos en la base de datos
    $sql = "DELETE FROM documentos WHERE caso_referencia='$referencia'";
    $conn->query($sql);
}

// Función para eliminar las evidencias relacionadas con el caso
function eliminarEvidencias($referencia, $conn) {
    // Eliminar los registros de evidencias en la base de datos
    $sql = "DELETE FROM evidencias WHERE caso_referencia='$referencia'";
    $conn->query($sql);
}

// Obtener la referencia del caso a eliminar
$referencia = $_GET["referencia"];

// Eliminar documentos asociados al caso
eliminarDocumentos($referencia, $conn);

// Eliminar evidencias relacionadas con el caso
eliminarEvidencias($referencia, $conn);

// Eliminar el caso en sí
$sql = "DELETE FROM casos WHERE referencia='$referencia'";
if ($conn->query($sql) === TRUE) {
    header("Location: /Casos/buscar_casos.php");
} else {
    echo "Error al eliminar el caso: " . $conn->error;
}

$conn->close();
?>

