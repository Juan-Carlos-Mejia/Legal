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
    <title>Detalles del Caso</title>
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













    #botonArribaIzquierda {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: white;
            color: black;
            text-decoration: none;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
            margin-left: 1150px;
            margin-top: 400px;
        }

        #botonArribaIzquierda:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .edit-btn {
          position: fixed;
            top: 400px;
            left: 20px;
            padding: 10px 20px;
            background-color: white;
            color: black;
            text-decoration: none;
            
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
            margin-left: 1150px;
            margin-top: 80px;
}

.edit-btn:hover {
    background-color: #0056b3;
    color: white;
}







.card {
    border: 1px solid #ccc;
    border-radius: 10px; /* Aumenté el radio del borde para hacerlo más suave */
    padding: 20px;
    margin: 20px auto;
    max-width: 500px; /* Ajusté el ancho máximo para adaptarlo a pantallas más grandes */
    background-color: #ffffff; /* Cambié el color de fondo para que sea más nítido */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Hice la sombra más pronunciada */
}

/* Estilos para el título */
.card h3 {
    color: #333;
    font-size: 1.5em; /* Aumenté el tamaño del título para hacerlo más prominente */
    text-align: center;
    margin-bottom: 15px; /* Agregué un margen inferior para separarlo del contenido */
}

/* Estilos para los párrafos */
.card p {
    color: #666;
    font-size: 1.1em; /* Aumenté ligeramente el tamaño de la fuente */
    line-height: 1.4; /* Ajusté el espaciado entre líneas para mejorar la legibilidad */
    margin-bottom: 15px;
}

/* Estilos específicos para los elementos fuertes dentro de los párrafos */
.card p strong {
    color: #000;
    font-weight: bold; /* Agregué negrita para resaltar los elementos fuertes */
}


.card2 {
    border: 1px solid #ccc;
    border-radius: 10px; /* Aumenté el radio del borde para hacerlo más suave */
    padding: 20px;
    margin: 20px auto;
    max-width: 800px; /* Ajusté el ancho máximo para adaptarlo a pantallas más grandes */
    background-color: #ffffff; /* Cambié el color de fondo para que sea más nítido */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Hice la sombra más pronunciada */
}

/* Estilos para el título */
.card2 h3 {
    color: #333;
    font-size: 1.5em; /* Aumenté el tamaño del título para hacerlo más prominente */
    text-align: center;
    margin-bottom: 15px; /* Agregué un margen inferior para separarlo del contenido */
}

/* Estilos para los párrafos */
.card2 p {
    color: #666;
    font-size: 1.1em; /* Aumenté ligeramente el tamaño de la fuente */
    line-height: 1.4; /* Ajusté el espaciado entre líneas para mejorar la legibilidad */
    margin-bottom: 15px;
}

/* Estilos específicos para los elementos fuertes dentro de los párrafos */
.card2 p strong {
    color: #000;
    font-weight: bold; /* Agregué negrita para resaltar los elementos fuertes */
}
button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

button:hover {
  background-color: #0056b3;
}



.modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            padding-top: 100px; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.9); 
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
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
                    <li><a href="">Victimas</a></li>
                    <li><a href="">Imputados</a></li>
                    
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











  <div id="myModal" class="modal">
    <span class="close">&times;</span>
    <video class="modal-content" id="video01" controls>
        <source src='ruta_del_video.mp4' type='video/mp4'>
        Your browser does not support the video tag.
    </video>
</div>

<a id="botonArribaIzquierda" href="/Casos/Buscar_Casos.php">
    <i class="fas fa-history"></i>
</a>


    <div class="container">
        <?php
        // Verificar si se proporcionó una referencia de caso válida en la URL
        if (isset($_GET['referencia'])) {
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

            // Obtener la referencia del caso desde la URL
            $referencia = $_GET['referencia'];


            echo "<a href='editar_caso.php?referencia=$referencia' class='edit-btn'>
        <i class='fas fa-edit'></i>
      </a>";



            // Consulta para obtener los detalles del caso
            $sql = "SELECT * FROM casos WHERE referencia = '$referencia'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Mostrar los detalles del caso
                echo "<center>";
                echo "<div class='card'>";
                echo "<h3>Detalles del Caso</h3>";
                echo "<p><strong>Generalidades</strong>";
                echo "<p><strong>Referencia:</strong> " . $row["referencia"] . "</p>";
                echo "<p><strong>Víctima:</strong> " . $row["victima"] . "</p>";
                echo "<p><strong>Imputado:</strong> " . $row["imputado"] . "</p>";
                echo "<p><strong>Tipo de Delito:</strong> " . $row["tipo_delito"] . "</p>";
                echo "</center>";

                // Consulta para obtener la evidencia asociada al caso
                $sql_evidencia = "SELECT * FROM evidencias WHERE caso_referencia = '$referencia'";
                $result_evidencia = $conn->query($sql_evidencia);
                
                if ($result_evidencia->num_rows > 0) {
                    // Inicializar contadores
                    $imagenes = [];
                    $videos = [];
                    $audios = [];
                
                    // Agrupar evidencias por tipo
                    while($row_evidencia = $result_evidencia->fetch_assoc()) {
                        $extension = pathinfo($row_evidencia["nombre_archivo"], PATHINFO_EXTENSION);
                
                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $imagenes[] = $row_evidencia;
                        } elseif (in_array($extension, ['mp4', 'webm', 'ogg'])) {
                            $videos[] = $row_evidencia;
                        } elseif (in_array($extension, ['mp3', 'ogg', 'wav'])) {
                            $audios[] = $row_evidencia;
                        }
                    }
                
                    // Mostrar evidencias agrupadas
                    echo "<div class='card2'>";
                    echo "<h3>Evidencias Registradas</h3>";
                
                
                    // Mostrar imágenes
                    if (count($imagenes) > 0) {
                        
                       
                        echo "<h3> Imágenes (" . count($imagenes) . ")</h3>";
                        foreach ($imagenes as $img) {
                            echo "<img src='" . $img["ubicacion_archivo"] . "' alt='Evidencia' width='200' height='200'>";
                          
                        }
                        echo "</div>";
                    }
                
                    // Mostrar videos
                    if (count($videos) > 0) {
                        
                     
                        echo "<h3> Videos (" . count($videos) . ")</h3>";
                        foreach ($videos as $video) {
                            echo "<video controls width='200' height='200'>";
                            echo "<source src='" . $video["ubicacion_archivo"] . "' type='video/" . pathinfo($video["nombre_archivo"], PATHINFO_EXTENSION) . "'>";
                            echo "Tu navegador no soporta el video.";
                            echo "</video>";
                           
                        }
                        echo "</div>";
                    }
                
                    // Mostrar audios
                    if (count($audios) > 0) {
                 
                        echo "<center>";
                        echo "<h3> Audios (" . count($audios) . ")</h3>";
                        foreach ($audios as $audio) {
                            echo "<audio controls>";
                            echo "<source src='" . $audio["ubicacion_archivo"] . "' type='audio/" . pathinfo($audio["nombre_archivo"], PATHINFO_EXTENSION) . "'>";
                            echo "Tu navegador no soporta el audio.";
                            echo "</audio>";
                           
                        }
                        echo "</div>";
                    }
                
                    echo "</div>";
                } else {
                    echo "<p>No hay evidencia asociada a este caso.</p>";
                }
                echo "</div class='card2'>";
                echo "</div class='card2'>";
                echo "<div class='card2'>";
                // Consulta para obtener el documento asociado al caso
                $sql_documento = "SELECT * FROM documentos WHERE caso_referencia = '$referencia'";
                $result_documento = $conn->query($sql_documento);
                
                if ($result_documento->num_rows > 0) {
                    echo "<div class='documento'>";
                    echo "<h3>Documento</h3>";
                    while ($row_documento = $result_documento->fetch_assoc()) {
                        // Obtener la ubicación del archivo
                        $ubicacionArchivo = $row_documento["ubicacion_archivo"];
                        $extension = pathinfo($ubicacionArchivo, PATHINFO_EXTENSION);
                
                        // Codificar URL para asegurar compatibilidad
                        $ubicacionArchivoCodificada = urlencode($ubicacionArchivo);
                
                        // Mostrar el documento en un iframe según su extensión
                        if ($extension === 'pdf') {
                            echo "<iframe src='$ubicacionArchivo' width='100%' height='600px'></iframe>";
                        } elseif ($extension === 'doc' || $extension === 'docx') {
                            echo "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=$ubicacionArchivo' width='100%' height='600px'></iframe>";
                            echo "<a href='$ubicacionArchivo' download><button>Descargar y abrir en Word</button></a>";
                        } else {
                            echo "<p>Formato de archivo no compatible: $ubicacionArchivo</p>";
                        }
                        
                    }
                    echo "</div>";
                } else {
                    echo "<p>No hay documento asociado a este caso.</p>";
                }
                
                echo "</>"; // Cierre de la tarjeta de detalles del caso
            } else {
                echo "<p>No se encontraron detalles para la referencia de caso proporcionada.</p>";
            }

            $conn->close();
        } else {
            echo "<p>No se proporcionó una referencia de caso válida.</p>";
        }

        
        
        ?>
    </div>

    <script>


var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>
</body>
</html>
