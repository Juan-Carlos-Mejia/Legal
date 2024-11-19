<?php
// Iniciar sesión (si aún no se ha iniciado)
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Si el usuario no ha iniciado sesión, redirigirlo al formulario de inicio de sesión
    header("Location: Upss.php");
    exit();
}

// Obtener el ID de usuario de la sesión
$user_id = $_SESSION['user_id'];

// Conexión a la base de datos (ajusta estos valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los detalles del usuario de la base de datos
$sql = "SELECT nombre, apellido, telefono, tipo, correo FROM usuarios WHERE id = $user_id";
$result = $conn->query($sql);

// Si se envió el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los nuevos valores del formulario
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_apellido = $_POST['apellido'];
    $nuevo_telefono = $_POST['telefono'];

    // Actualizar los datos del usuario en la base de datos
    $update_sql = "UPDATE usuarios SET nombre = '$nuevo_nombre', apellido = '$nuevo_apellido', telefono = '$nuevo_telefono' WHERE id = $user_id";
    if ($conn->query($update_sql) === TRUE) {
        // Actualización exitosa, redirigir para evitar envíos repetidos del formulario
        header("Location: /Formularios/Perfilguardado.php");
        exit();
    } else {
        echo "Error al actualizar los datos: " . $conn->error;
    }
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .user-card {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        .user-details {
            margin-top: 20px;
        }
        .user-details p {
            margin-bottom: 10px;
        }
        input[type="text"] {
            width: calc(100% - 10px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido</h1>
        <div class="user-card">
            <?php if ($result->num_rows > 0): ?>
                <?php $row = $result->fetch_assoc(); ?>
                <div class="user-details">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <p><strong>Nombre:</strong> <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>"></p>
                        <p><strong>Apellido:</strong> <input type="text" name="apellido" value="<?php echo $row['apellido']; ?>"></p>
                        <p><strong>Correo Electrónico:</strong> <?php echo $row['correo']; ?></p>
                        <p><strong>Teléfono:</strong> <input type="text" name="telefono" value="<?php echo $row['telefono']; ?>"></p>
                        <input type="submit" value="Actualizar">
                    </form>
                </div>
            <?php else: ?>
                <p>Error: No se encontraron detalles del usuario.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
