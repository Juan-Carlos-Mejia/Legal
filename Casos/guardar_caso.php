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
$conexion = new mysqli("localhost", "root", "", "legalcc");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recupera los datos del formulario
$referencia = $_POST['referencia'];
$victima = $_POST['victima'];
$inputado = $_POST['inputado'];
$delito = $_POST['delito'];
$evidencia = $_POST['evidencia'];

// Procesa el archivo subido
$nombreArchivo = $_FILES['documento']['name'];
$rutaTemporal = $_FILES['documento']['tmp_name'];
$rutaDestino = "archivos/" . $nombreArchivo;
move_uploaded_file($rutaTemporal, $rutaDestino);

$evidenciaValidada = false;

if (strpos($evidencia, 'rojo') !== false && strpos($evidencia, 'verde') !== false) {
    $evidenciaValidada = true;
}

// Guarda los datos en la base de datos
$sql = "INSERT INTO casos (referencia, documento, victima, inputado, delito, evidencia)
        VALUES ('$referencia', '$rutaDestino', '$victima', '$inputado', '$delito', '$evidencia')";

if ($conexion->query($sql) === TRUE) {
    header("Location: /casos/"); 
        exit();
} else {
    echo "Error al guardar el caso: " . $conexion->error;
}

// Cierra la conexión
$conexion->close();
?>
