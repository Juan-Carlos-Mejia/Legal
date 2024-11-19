<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: ./Iniciar_Sesion.php");
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


<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

// Clave y método de cifrado
$encryption_key = 'LegalCC'; // Debe coincidir con la clave utilizada para el cifrado
$ciphering = "AES-128-CTR";
$options = 0;
$encryption_iv = '1234567891011121'; // Debe coincidir con el IV utilizado para el cifrado

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del registro a editar
$id = $_GET['id'];

// Consultar los datos del registro por ID
$sql = "SELECT * FROM victimas WHERE id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Desencriptar los datos para mostrarlos en el formulario
$apellido = openssl_decrypt($row['apellido'], $ciphering, $encryption_key, $options, $encryption_iv);
$nombre = openssl_decrypt($row['nombre'], $ciphering, $encryption_key, $options, $encryption_iv);
$fecha_nacimiento = $row['fecha_nacimiento'];
$dui = openssl_decrypt($row['dui'], $ciphering, $encryption_key, $options, $encryption_iv);
$departamento = openssl_decrypt($row['departamento'], $ciphering, $encryption_key, $options, $encryption_iv);
$distrito = openssl_decrypt($row['distrito'], $ciphering, $encryption_key, $options, $encryption_iv);
$direccion = openssl_decrypt($row['direccion'], $ciphering, $encryption_key, $options, $encryption_iv);
$madre = openssl_decrypt($row['madre'], $ciphering, $encryption_key, $options, $encryption_iv);
$padre = openssl_decrypt($row['padre'], $ciphering, $encryption_key, $options, $encryption_iv);
$sexo = openssl_decrypt($row['sexo'], $ciphering, $encryption_key, $options, $encryption_iv);







$conn->close();
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT id, referencia FROM casos WHERE estado = 'activo'";  // Cambia si necesitas otro filtro
$result = $conn->query($sql);




?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .form-container {
            background-color: white;
            padding: 20px;
            margin: 30px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        h3 {
            text-align: left;
            color: #374D63;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"], button {
            padding: 10px;
            background-color: #374D63;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #2F455C;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 15px;
            }

            input[type="text"] {
                font-size: 14px;
            }

            input[type="submit"] {
                font-size: 14px;
            }
        }


      

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .progress-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .progress-step {
            width: 100%;
            height: 10px;
            background-color: #e0e0e0;
            position: relative;
            margin: 0 5px;
        }

        .progress-step.active {
            background-color: #007bff;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 95%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus {
           
            border-color: #66afe9;
            outline: none;
            box-shadow: 0px 0px 5px rgba(102, 175, 233, 0.5);
        }

        textarea {
            height: 100px;
            resize: none;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .button-group button[disabled] {
            background-color: #cccccc;
            cursor: not-allowed;
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
            background-color:#374D63;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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



        .btn-navega {
            position: fixed;
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: black;
    background-color: white;
    border: none;
  margin-top: 100px;
 margin-right: 1000px;
 margin-left: 200px;
    border-radius: 5px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.btn-navega:hover {
    background-color: white;
    color: #2c3e50;
    transform: scale(1.05);
}

.btn-navega:active {
    background-color: white;
    color: #2c3e50;
    transform: scale(0.95);
}


.select-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .select-container select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%204%205%22%3E%3Cpath%20fill%3D%22%23007bff%22%20d%3D%22M2%200L0%202h4L2%200zM2%205L0%203h4L2%205z%22/%3E%3C/svg%3E') no-repeat right 10px center;
            background-size: 12px;
        }
        .select-container select:focus {
            outline: none;
            border-color: #007bff;
        }

/* Botón para seleccionar archivo */
input[type="file"] {
    padding: 10px;
    background-color: white;
    color: #2c3e50;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

input[type="file"]::file-selector-button {
    padding: 10px;
    background-color: white;
    color: #2c3e50;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

input[type="file"]::file-selector-button:hover {
    background-color: #e8f0fa;
    transform: scale(1.05);
}

input[type="file"]::file-selector-button:active {
    background-color: #cce4f6;
    transform: scale(0.95);
}






    </style>
</head>
<body>

<nav>
        <ul>
            <li><a href="/Pagina_principal.php">Inicio</a></li>
            <li>
                <a href="/Casos/Buscar_Casos.php">Casos</a>
                <ul>
                    <li><a href="casos/victima.php">Victimas</a></li>
                    <li><a href="casos/imputado.php">Imputados</a></li>
                    
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






    <a class="btn-navega" href="/Casos/victima/tabla_de_victima.php" title="Volver"><i class="fas fa-arrow-left"></i></a></li>





    <div class="form-container">
        

        <h2>Declaración</h2>
        <form method="post" action="subir_declaracion.php" enctype="multipart/form-data">
    <div class="step active">
        <h3>Información Personal</h3>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" value="<?php echo $apellido; ?>" required readonly>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required readonly>
        
        <label for="casos">Casos</label>
        <select id="casos" name="casos" required>
            <option value="">Seleccione</option>

            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['referencia'] . "</option>";
                }
            } else {
                echo "<option value=''>No hay casos disponibles</option>";
            }
            ?>
        </select>

        <label for="seleccionardeclaracion">Seleccionar declaración</label>
        <select id="seleccionardeclaracion" name="seleccionardeclaracion" required onchange="mostrarCampo()">
            <option value="">Seleccione</option>
            <option value="documento">Documento</option>
            <option value="texto">Texto</option>
            <option value="audio">Audio</option>
            <option value="video">Video</option>
        </select>

        <!-- Campo para Documento -->
        <div id="campo-documento" style="display:none;">
            <label for="documento">Documento:</label>
            <input type="file" id="documento" name="documento[]" multiple accept=".png,.jpg,.jpeg,.pdf,.doc,.docx">
        </div>

        <!-- Campo para Audio -->
        <div id="campo-audio" style="display:none;">
            <label for="audio">Audio:</label>
            <input type="file" id="audio" name="audio[]" multiple accept=".mp3,.wav,.ogg">
        </div>

        <!-- Campo para Video -->
        <div id="campo-video" style="display:none;">
            <label for="video">Video:</label>
            <input type="file" id="video" name="video[]" multiple accept=".mp4,.avi,.mov,.mkv">
        </div>

        <!-- Campo para Texto -->
        <div id="campo-texto" style="display:none;">
            <label for="declaracion">Declaración:</label>
            <textarea id="declaracion" name="declaracion"></textarea>
        </div>

        <button type="submit">Enviar</button>
    </div>
</form>

    </div>


</body>

<script>
function mostrarCampo() {
    // Ocultar todos los campos al inicio
    document.getElementById("campo-documento").style.display = "none";
    document.getElementById("campo-audio").style.display = "none";
    document.getElementById("campo-video").style.display = "none";
    document.getElementById("campo-texto").style.display = "none";

    // Obtener la opción seleccionada
    var seleccion = document.getElementById("seleccionardeclaracion").value;

    // Mostrar el campo correspondiente a la selección
    if (seleccion === "documento") {
        document.getElementById("campo-documento").style.display = "block";
    } else if (seleccion === "audio") {
        document.getElementById("campo-audio").style.display = "block";
    } else if (seleccion === "video") {
        document.getElementById("campo-video").style.display = "block";
    } else if (seleccion === "texto") {
        document.getElementById("campo-texto").style.display = "block";
    }
}


</script>
</html>