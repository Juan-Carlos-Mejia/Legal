<?php
// Conexión a la base de datos (ajusta estos valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    header("Location: ErrorConectar.php");
    exit();
}
 
// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$tipo = $_POST['tipo'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$termino = "Si";

// Encriptar la contraseña
$contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

// Insertar los datos en la base de datos
$sql = "INSERT INTO usuarios (nombre, apellido, telefono, tipo, correo, contrasena, terminos) 
        VALUES ('$nombre', '$apellido', '$telefono', '$tipo', '$correo', '$contrasena_encriptada' , '$termino')";

if ($conn->query($sql) === TRUE) {
    header("Location: RegistroGuardado.php");
    exit();
} else {
    header("Location: Regsitro_Usuario.php"); 
        exit();;
}

// Cerrar conexión
$conn->close();
?>