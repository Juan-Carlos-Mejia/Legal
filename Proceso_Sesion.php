<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    header("Location: ErrorConectar.php");
    exit();
}

// Obtener los datos del formulario
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$ip_usuario = $_SERVER['REMOTE_ADDR']; // Obtener la IP del usuario

// Buscar el usuario en la base de datos
$sql = "SELECT id, correo, contrasena FROM usuarios WHERE correo = '$correo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Usuario encontrado, verificar la contraseña
    $row = $result->fetch_assoc();
    if (password_verify($contrasena, $row['contrasena'])) {
        // Contraseña correcta, inicio de sesión exitoso
        session_start();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_nombre'] = $row['nombre'];
        $_SESSION['user_apellido'] = $row['apellido'];
        
        // Guardar el inicio de sesión exitoso en la tabla historial_sesiones
        $sql_historial = "INSERT INTO historial_sesiones (usuario_id, correo, ip_usuario, exito)
                          VALUES ('" . $row['id'] . "', '$correo', '$ip_usuario', 1)";
        $conn->query($sql_historial);
        
        header("Location: InicioGuardado.php");
        exit();
    } else {
        // Contraseña incorrecta
        $sql_historial = "INSERT INTO historial_sesiones (correo, ip_usuario, exito)
                          VALUES ('$correo', '$ip_usuario', 0)";
        $conn->query($sql_historial);
        
        header("Location: Iniciar_Sesion.php?error=1");
        exit();
    }
} else {
    // Usuario no encontrado
    $sql_historial = "INSERT INTO historial_sesiones (correo, ip_usuario, exito)
                      VALUES ('$correo', '$ip_usuario', 0)";
    $conn->query($sql_historial);
    
    header("Location: Iniciar_Sesion.php?error=1");
    exit();
}

// Cerrar conexión
$conn->close();
?>

