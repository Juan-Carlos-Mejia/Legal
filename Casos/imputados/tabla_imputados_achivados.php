<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: Iniciar_Sesion.php");
    exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la información del usuario desde la base de datos
$user_id = $_SESSION['user_id'];
$sql = "SELECT tipo FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tipo_usuario);
$stmt->fetch();
$stmt->close();
$conn->close();








if (isset($_GET['logout'])) {
  // Verificar si se ha confirmado la salida
  if ($_GET['logout'] == 'confirm') {
      session_destroy(); // Destruir todas las variables de sesión
      header("Location: Iniciar_Sesion.php"); // Redirigir al usuario a la página de inicio de sesión
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Desencriptados</title>
    <style>

body {
            font-family: 'Bahnschrift', sans-serif;
            background-color: #e8f0fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        nav {
            background-color: #2c3e50;
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 40px;
        }
        ul li {
            position: relative;
        }

        .active {
          background-color:#374D63;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            font-weight: bold;
            padding: 10px 30px;
            display: block;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 8px;
        }
        ul li a:hover {
            background-color:#374D65;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }
        /* Estilo del submenú "Cerrar sesión" */
        ul li ul {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #2c3e50;
            border-radius: 8px;
            display: none;
            min-width: 180px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        ul li ul li a {
            padding: 10px 15px;
            font-size: 16px;
            color: white;
        }
        ul li:hover ul {
            display: block;
        }
        ul li ul li a:hover {
            background-color:#374D63;
        }
        /* Contenido */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px 20px;
        }
        h1 {
            color: #2c3e50;
            font-size: 48px;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }
        p {
            color: #555;
            font-size: 20px;
            max-width: 600px;
            line-height: 1.6;
        }
        /* Responsive */
        @media (max-width: 768px) {
            ul {
                flex-direction: column;
                align-items: center;
            }
            ul li a {
                padding: 10px 20px;
                font-size: 16px;
            }
        }
















        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<nav>
        <ul>
            <li><a href="/Pagina_principal.php" >Inicio</a></li>




            <li>
                <a href="" class="active">Imputados</a>
                <ul>
                    <li><a href="/casos/Agregar_casos.php">Casos</a></li>
                    <li><a href="/casos/victima/tabla_de_victima.php">Victimas</a></li>
                    <li><a href="/archivados/casos_archivados.php">Archivados</a></li>
                </ul>
            
            
            </li>
            <li><a href="/Audiencias/Buscar_Audiencias.php">Audiencias</a></li>
            <li><a href="apps.php">Aplicaciones</a></li>
            <?php if ($tipo_usuario === 'fiscal' || $tipo_usuario === 'abogado'): ?>  

            <li><a href="/Audiencias/ver_solicitudes.php">Mis Solicitudes</a></li>

            <?php endif; ?>

            <?php if ($tipo_usuario === 'juez'): ?>  

            <li><a href="/Audiencias/ver_solicitudes.php">Solicitudes</a></li>

            <?php endif; ?>
            
            <li>
                <a href="/formularios/Perfil.php">Perfil</a>
                <ul>
                    <li><a href="?logout">Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>











    <h1>Lista de Textos Completos</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>DUI</th>
            <th>Sexo</th>
            <th>Pandilla</th>
            <th>Alias</th>
            <th>Estado</th>
        </tr>
        
        <?php
        // Datos de conexión
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'legalcc';

        // Claves de desencriptación
        $encryption_key = 'LegalCC';
        $ciphering = "AES-128-CTR";
        $encryption_iv = '1234567891011121';

        // Conexión a la base de datos
        $conn = new mysqli($host, $username, $password, $database);
        
        // Comprobar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Consulta para obtener los datos
        $sql = "SELECT * FROM imputados_archivados";
        $result = $conn->query($sql);

        // Función para desencriptar
        function decrypt_data($data, $key, $ciphering, $iv) {
            return openssl_decrypt($data, $ciphering, $key, 0, $iv);
        }

        // Mostrar datos desencriptados
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['codigo'],  "</td>";
                echo "<td>" . decrypt_data($row['apellido'], $encryption_key, $ciphering, $encryption_iv) . "</td>";
                echo "<td>" . decrypt_data($row['nombre'], $encryption_key, $ciphering, $encryption_iv) . "</td>";
                echo "<td>" . decrypt_data($row['dui'], $encryption_key, $ciphering, $encryption_iv) . "</td>";
              
                echo "<td>" . decrypt_data($row['sexo'], $encryption_key, $ciphering, $encryption_iv) . "</td>";
                echo "<td>" . decrypt_data($row['pandilla'], $encryption_key, $ciphering, $encryption_iv) . "</td>";
                echo "<td>" . decrypt_data($row['alias'], $encryption_key, $ciphering, $encryption_iv) . "</td>";
                echo "<td>" . $row['estado'], "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No se encontraron datos</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>
</html>