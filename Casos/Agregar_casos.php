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

// Función para generar la referencia automática
function generarReferencia() {
    $fechaHora = date('Y-m-d-H-i-s');
    $existencia = rand(1000, 9999); // Número de existencia aleatorio
    return $fechaHora . "-" . $existencia;
}

// Procesamiento del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $referencia = generarReferencia();
    $victima = $_POST['victima'];
    $imputado = $_POST['imputado'];
    $tipoDelito = $_POST['tipo_delito'];

    // Insertar caso en la base de datos
    $sql = "INSERT INTO casos (referencia, victima, imputado, tipo_delito) VALUES ('$referencia', '$victima', '$imputado', '$tipoDelito')";

    if ($conn->query($sql) === TRUE) {
        header("Location: /Casos/casoguardado.php"); 

        // Procesar y guardar archivos de evidencia
        if(isset($_FILES['evidencia'])){
            $errors= array();
            foreach($_FILES['evidencia']['tmp_name'] as $key => $tmp_name ){
                $file_name = $_FILES['evidencia']['name'][$key];
                $file_size =$_FILES['evidencia']['size'][$key];
                $file_tmp =$_FILES['evidencia']['tmp_name'][$key];
                $file_type=$_FILES['evidencia']['type'][$key];
                if($file_size > 10000000000000){
                    $errors[]='El tamaño del archivo debe ser menor a 2 MB';
                }      
                $desired_dir="uploads"; // Directorio donde se guardarán los archivos
                if(empty($errors)==true){
                    if(is_dir($desired_dir)==false){
                        mkdir("$desired_dir", 0700); // Crea el directorio si no existe
                    }
                    if(is_dir("$desired_dir/".$file_name)==false){
                        move_uploaded_file($file_tmp,"$desired_dir/".$file_name); // Mueve el archivo a la ubicación deseada
                    }else{
                        $new_dir="$desired_dir/".$file_name.time();
                        rename($file_tmp,$new_dir);                
                    }
                    // Insertar información de la evidencia en la base de datos
                    $sql_evidencia = "INSERT INTO evidencias (caso_referencia, nombre_archivo, tipo_archivo, ubicacion_archivo) VALUES ('$referencia', '$file_name', '$file_type', '$desired_dir/$file_name')";
                    if ($conn->query($sql_evidencia) === TRUE) {
                        echo "Evidencia subida correctamente.";
                    } else {
                        echo "Error al subir la evidencia: " . $conn->error;
                    }
                }else{
                    print_r($errors);
                }
            }
        }
        
        
        // Guardar documento PDF o DOC
        if(isset($_FILES['documento'])){
            $file_name = $_FILES['documento']['name'];
            $file_size = $_FILES['documento']['size'];
            $file_tmp = $_FILES['documento']['tmp_name'];
            $file_type = $_FILES['documento']['type'];
            $desired_dir = "documentos"; // Directorio donde se guardarán los documentos

            // 
            if ($file_type == 'application/pdf' || $file_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ) {
                if (move_uploaded_file($file_tmp, "$desired_dir/" . $file_name)) {
                    // Insertar información del documento en la base de datos
                    $sql_documento = "INSERT INTO documentos (caso_referencia, nombre_archivo, tipo_archivo, ubicacion_archivo) VALUES ('$referencia', '$file_name', '$file_type', '$desired_dir/$file_name')";
                    if ($conn->query($sql_documento) === TRUE) {
                        header("Location: /Casos/casoguardado.php"); 
                        exit();
                    } else {
                        echo "Error al subir el documento: " . $conn->error;
                    }
                } else {
                    echo "Error al subir el documento.";
                }
            } else {
                $error_message = "Tipo de archivo no admitido.";
                header("Location: /Casos/casoguardado.php?error=" . urlencode($error_message));
                exit();
               
            }
        }
        
    } else {
        echo "Error al agregar el caso: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Caso</title>
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

    .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            font-family: Bahnschrift;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* Agregamos scroll vertical */
            max-height: 600px; /* Establecemos una altura máxima */
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 30px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="file"], button {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .add-evidence {
            display: flex;
            align-items: center;
        }

        .add-evidence button {
            margin-left: 10px;
            flex-shrink: 0;
        }


        .submit-btn {
            background-color: #007bff; /* Green */
            border: none;
            color: white;
            font-family: Bahnschrift;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0056b3; /* Darker Green on Hover */
            font-family: Bahnschrift;
        }
        .cancel-btn {
            background-color: #fff; /* Green */
            border: none;
            color: black;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            font-family: Bahnschrift;
        }

        .cancel-btn:hover {
            background-color: #0056b3; /* Darker Green on Hover */
            color: #fff;
            font-family: Bahnschrift;
        }

        #botonArribaIzquierda {

            position: relative;
            display: inline-block;
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            color: black;
            text-decoration: none;
            border-radius: 5px;
            font-family: Bahnschrift;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
            margin-left: 1080px;
            margin-top: 80px;
        }

        #botonArribaIzquierda:hover {
            background-color: #0056b3;
            color: #fff;
            font-family: Bahnschrift;
        }

        #botonArribaIzquierda .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 5px 0;

    /* Posicionamiento */
    position: absolute;
    z-index: 1;
    bottom: 125%; /* Cambia esto según la posición deseada */
    left: 50%;
    margin-left: -60px;

    /* Flecha */
    opacity: 0;
    transition: opacity 0.3s;
}

#botonArribaIzquierda:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

.active {
            background-color:#374D63;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

        /* Oculta el input de archivo real */
.evidence-btn {
    position: absolute;
    left: -9999px; /* Lo mueve fuera de la vista */
}

/* Estilo del label que actúa como botón */
.custom-label {
     /* Fondo blanco */
    border: none;
    color: black; /* Color del texto */
    padding: 10px 20px; /* Espaciado del botón */
    text-align: center; /* Centrar el texto */
    text-decoration: none; /* Sin decoración de texto */
    display: inline-block; /* Mostrar como bloque en línea */
    font-size: 14px; /* Tamaño del texto */
    margin: 4px 2px; /* Margen alrededor del botón */
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
    border-radius: 5px; /* Bordes redondeados */
    transition: background-color 0.3s ease; /* Transición suave al cambiar de fondo */
    font-family: Bahnschrift; /* Fuente personalizada */
}

/* Estilo al pasar el mouse sobre el label */
.custom-label:hover {
    background-color: #0056b3; /* Fondo más oscuro al pasar el ratón */
    color: #fff; /* Color de texto blanco al pasar el ratón */
}




/*_____________________________________________________*/


.button-container {
    display: flex;
    gap: 10px; /* Espacio entre los botones */
    
}

.submit-btn, .cancel-btn {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-family: Arial, sans-serif;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
    font-family: Bahnschrift;
}

.submit-btn {
    background-color: white;
    color: black;
}

.submit-btn:hover {
    background-color: #0056b3;
    color: white;
}

.cancel-btn {
    background-color: white;
    color:black;
}

.cancel-btn:hover {
    background-color: #0056b3;
    color: white;
}

.submit-btn i, .cancel-btn i {
    margin-right: 8px; /* Espacio entre el ícono y el texto */
    font-size: 20px; /* Tamaño del ícono */
}





    </style>

</head>
<body>
<nav>
        <ul>
            <li><a href="/Pagina_principal.php" >Inicio</a></li>




            <li>
                <a href="/Casos/Buscar_Casos.php" class="active">Casos</a>
                <ul>
                    <li><a href="/casos/victima/tabla_de_victima.php">Victimas</a></li>
                    <li><a href="/casos/imputados/tabladeimputados.php">Imputados</a></li>
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




  <a id="botonArribaIzquierda" href="/Casos/Buscar_Casos.php">
    <i class="fas fa-table"></i>
    <span class="tooltiptext">Tabla de casos</span>
</a>




<div class="container">
    <h2>Agregar Caso</h2>
    <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="victima">Víctima:</label>
        <input type="text" id="victima" name="victima" required>

        <label for="imputado">Imputado:</label>
        <input type="text" id="imputado" name="imputado">

        <div class="select-container">
            <label for="tipo_delito">Tipo de Delito:</label>
            <select id="tipo_delito" name="tipo_delito">
                <option value="robo">Robo</option>
                <option value="asalto">Asalto</option>
                <option value="fraude">Fraude</option>
                <option value="vandalismo">Vandalismo</option>
                <option value="homicidio">Homicidio</option>
            </select>
        </div>
        <div id="evidencia-container">
    <form>
        <br>
        
        <label for="evidencia">Evidencia:</label>
        <br>
        <label for="evidencia" class="custom-label">
    <i class="fas fa-file-upload"></i> Seleccionar
</label>
<input type="file" id="evidencia" name="evidencia[]" multiple accept=".png,.jpg,.jpeg" class="evidence-btn" onchange="updateFileNames()">
<span id="evidencia-nombres"></span> <!-- Span para mostrar los nombres de los archivos seleccionados -->

        <br><br>

        <label for="documento">Documento:</label>
        <br>
        <label for="documento" class="custom-label">
    <i class="fas fa-file-upload"></i> Seleccionar
</label>
<input type="file" id="documento" name="documento" class="evidence-btn" onchange="updateFileName()">
<span id="documento-nombre"></span> <!-- Span para mostrar el nombre del archivo seleccionado -->

        <br>

        <div class="button-container">
    <button type="submit" class="submit-btn">
        <i class="fas fa-plus"></i> Agregar Caso
    </button>
    <button type="reset" class="cancel-btn">
        <i class="fas fa-times"></i> Cancelar
    </button>
</div>

    </form>
</div>

<script>
    document.getElementById('evidencia').addEventListener('change', function() {
        var nombresArchivos = [];
        for (var i = 0; i < this.files.length; i++) {
            nombresArchivos.push(this.files[i].name);
        }
        document.getElementById('evidencia-nombres').textContent = nombresArchivos.join(', ');
    });

    document.getElementById('documento').addEventListener('change', function() {
        var nombreArchivo = this.files[0] ? this.files[0].name : '';
        document.getElementById('documento-nombre').textContent = nombreArchivo;
    });

    function agregarCampoEvidencia() {
        var container = document.getElementById("evidencia-container");
        var input = document.createElement("input");
        input.type = "file";
        input.name = "evidencia[]";
        input.className = "evidence-btn";
        container.appendChild(document.createElement("br"));
        container.appendChild(input);
    }

    document.querySelector('a[href="?logout"]').addEventListener('click', function(event) {
        if (!confirm('¿Estás seguro de que deseas cerrar sesión?')) {
            event.preventDefault(); // Cancelar el evento de clic si el usuario no confirma
        }
    });
</script>

</body>
</html>