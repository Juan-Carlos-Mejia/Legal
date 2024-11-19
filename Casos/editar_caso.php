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

    // Consulta para obtener los detalles del caso
    $sql = "SELECT * FROM casos WHERE referencia = '$referencia'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Caso</title>
            <style>
             
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .container form {
                    margin-bottom: 20px;
                }
                .card {
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    padding: 20px;
                    margin-bottom: 20px;
                }
                .card h3 {
                    margin-top: 0;
                }
                .evidencia img {
                    max-width: 100%;
                    height: auto;
                    margin-bottom: 10px;
                }
                .documento iframe {
                    width: 100%;
                    height: 500px;
                    border: none;
                }
                input[type="text"], input[type="file"] {
                    width: calc(100% - 20px);
                    padding: 10px;
                    margin-bottom: 10px;
                }
                input[type="submit"], .btn {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .btn {
                    display: inline-block;
                    margin-right: 10px;
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

        #botonArribaIzquierda {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
         
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
            margin-left: 1050px;
            margin-top: 80px;
        }

        #botonArribaIzquierda:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .btn {
  display: inline-block;
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 16px;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-primary {
  background-color: #007bff;
  color: #fff;
  border: none;
}

.btn-primary:hover {
  background-color: #0056b3;
}




/* Estilos para el modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
}

.modal.show {
    display: flex;
    opacity: 1;
}

.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 600px;
    margin: auto;
    animation: slide-down 0.3s ease-in-out;
}

@keyframes slide-down {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.close {
    color: #555;
    float: right;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
}



/* Estilos para el botón de enviar */
button[type="submit"] {
    background-color: #007bff; /* Color de fondo */
    color: #fff; /* Color del texto */
    padding: 10px 20px; /* Relleno interno */
    border: none; /* Sin borde */
    border-radius: 5px; /* Esquinas redondeadas */
    font-size: 16px; /* Tamaño de fuente */
    font-weight: bold; /* Negrita */
    cursor: pointer; /* Cursor de puntero */
    transition: background-color 0.3s ease, transform 0.2s ease; /* Transiciones */
}

/* Estilos para el hover y focus */
button[type="submit"]:hover,
button[type="submit"]:focus {
    background-color: #0056b3; /* Color de fondo al pasar el cursor */
    outline: none; /* Sin contorno */
    transform: translateY(-2px); /* Efecto de elevación */
}

/* Estilos para el active */
button[type="submit"]:active {
    background-color: #004085; /* Color de fondo al hacer clic */
    transform: translateY(0); /* Restablecer elevación */
}



#deleteRequestLink {
    display: inline-block;
    padding: 10px 20px;
    background-color: #dc3545;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

#deleteRequestLink:hover {
    background-color: #c82333;
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


<a id="botonArribaIzquierda" href="/Casos/ver_detalle_caso.php?referencia=<?php echo $row['referencia']; ?>">Cancelar Edicion</a>

        
            <div class="card2">
            <div class="card2">
                <center>
                <label >Generalidades</label><br>
</div class="card2">
           
            <label ></label><br>
            <label ></label><br>
            </center>
                <form action="actualizar_caso.php" method="post">
                    <input type="hidden" name="referencia" value="<?php echo $row['referencia']; ?>">
                    <label for="victima">Víctima:</label><br>
                    <input type="text" id="victima" name="victima" value="<?php echo $row['victima']; ?>"><br>
                    <label for="imputado">Imputado:</label><br>
                    <input type="text" id="imputado" name="imputado" value="<?php echo $row['imputado']; ?>"><br>
                    
                    <div class="select-container">
                    <label for="tipo_delito">Tipo de Delito:</label><br>
                    <select id="tipo_delito" name="tipo_delito">
                        <option value="robo" <?php if($row['tipo_delito'] == 'robo') echo 'selected'; ?>>Robo</option>
                        <option value="asalto" <?php if($row['tipo_delito'] == 'asalto') echo 'selected'; ?>>Asalto</option>
                        <option value="fraude" <?php if($row['tipo_delito'] == 'fraude') echo 'selected'; ?>>Fraude</option>
                        <option value="vandalismo" <?php if($row['tipo_delito'] == 'vandalismo') echo 'selected'; ?>>Vandalismo</option>
                        <option value="homicidio" <?php if($row['tipo_delito'] == 'homicidio') echo 'selected'; ?>>Homicidio</option>
                    </select><br>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          
                    </form>
                <div class="card2">
                    <h3>Agregar Evidencia</h3>
                    <form action="agregar_evidencia.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="referencia" value="<?php echo $row['referencia']; ?>">
                        <input type="file" name="evidencia[]" multiple>
                        <button type="submit" class="btn btn-primary">Agregar Evidencia</button>
                    </form>
                </div>
                <?php
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
    echo "<div class='evidencia'>";
    echo "<h3>Evidencias Registradas</h3>";

    // Mostrar imágenes
    if (count($imagenes) > 0) {
        echo "<div class='card2'>";
        echo "<h3> Imágenes (" . count($imagenes) . ")</h3>";
        foreach ($imagenes as $img) {
            echo "<img src='" . $img["ubicacion_archivo"] . "' alt='Evidencia' width='200' height='200'>";
            echo "<a href='' id='deleteRequestLink' >Solicitud de eliminación</a>";
        }
        echo "</div>";
    }

    // Mostrar videos
    if (count($videos) > 0) {
        echo "<div class='card2'>";
        echo "<h3> Videos (" . count($videos) . ")</h3>";
        foreach ($videos as $video) {
            echo "<video controls width='200' height='200'>";
            echo "<source src='" . $video["ubicacion_archivo"] . "' type='video/" . pathinfo($video["nombre_archivo"], PATHINFO_EXTENSION) . "'>";
            echo "Tu navegador no soporta el video.";
            echo "</video>";
            echo "<a href='' id='deleteRequestLink' >Solicitud de eliminación</a>";
        }
        echo "</div>";
    }

    // Mostrar audios
    if (count($audios) > 0) {
        echo "<div class='card2'>";
        echo "<h3> Audios (" . count($audios) . ")</h3>";
        foreach ($audios as $audio) {
            echo "<audio controls>";
            echo "<source src='" . $audio["ubicacion_archivo"] . "' type='audio/" . pathinfo($audio["nombre_archivo"], PATHINFO_EXTENSION) . "'>";
            echo "Tu navegador no soporta el audio.";
            echo "</audio>";
            echo "<a href='' id='deleteRequestLink' >Solicitud de eliminación</a>";
        }
        echo "</div>";
    }

    echo "</div>";
} else {
    echo "<p>No hay evidencia asociada a este caso.</p>";
}
?>




<div id="deleteRequestModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Solicitud de Eliminación</h2>
            <form id="deleteRequestForm">
                <label for="reason">Razón de la solicitud:</label><br>
                <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br><br>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>




                </div>
                <div class="card2">
    <h3>Documento</h3>
    <form action="actualizar_documento.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="referencia" value="<?php echo $row['referencia']; ?>">
        <input type="file" name="documento" accept=".pdf, .docx">
        <button type="submit" class="btn btn-primary">Agregar Documento</button>
    </form>
</div>

                    <?php

                    echo "<div class='card2'>";

if (isset($referencia)) {
    $sql_documento = "SELECT * FROM documentos WHERE caso_referencia = '$referencia'";
    $result_documento = $conn->query($sql_documento);

    if ($result_documento->num_rows > 0) {
        echo "<div class='documento'>";
      
        while ($row_documento = $result_documento->fetch_assoc()) {
            // Mostrar el documento en un iframe
            $ubicacionArchivo = $row_documento["ubicacion_archivo"];
            $extension = pathinfo($ubicacionArchivo, PATHINFO_EXTENSION);
    
            // Codificar URL para asegurar compatibilidad
            $ubicacionArchivoCodificada = urlencode($ubicacionArchivo);
    
            // Mostrar el documento en un iframe según su extensión
            if ($extension === 'pdf') {
                echo "<iframe src='$ubicacionArchivo' width='100%' height='600px'></iframe>";
            } elseif ($extension === 'doc' || $extension === 'docx') {
                echo "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=$ubicacionArchivo' width='100%' height='600px'></iframe>";
            } else {
                echo "<p>Formato de archivo no compatible: $ubicacionArchivo</p>";
            }
        }
        echo "</div>"; // Cierre del div
    } else {
        echo "<p>No hay documento asociado a este caso.</p>";
    }

  
 
} else {
    echo "<p>No se proporcionó una referencia de caso válida.</p>";
}

?>



                    
                </form>

                </div>
            </div>
<script>
// Obtener el modal
var modal = document.getElementById("deleteRequestModal");

// Obtener el enlace que abre el modal
var link = document.getElementById("deleteRequestLink");

// Obtener el elemento <span> que cierra el modal
var span = document.getElementsByClassName("close")[0];

// Cuando el usuario hace clic en el enlace, abre el modal
link.onclick = function(event) {
    event.preventDefault();
    modal.style.display = "block";
}

// Cuando el usuario hace clic en <span> (x), cierra el modal
span.onclick = function() {
    modal.style.display = "none";
}

// Cuando el usuario hace clic en cualquier lugar fuera del modal, lo cierra
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Manejar el envío del formulario
var form = document.getElementById("deleteRequestForm");
form.onsubmit = function(event) {
    event.preventDefault();
    // Aquí puedes agregar el código para enviar la solicitud
    alert("Solicitud enviada: " + document.getElementById("reason").value);
    
    // Limpiar el formulario
    form.reset();
    
    // Cerrar el modal
    modal.style.display = "none";
}



</script>


        </body>
        </html>
        <?php
    } else {
        echo "No se encontraron detalles para la referencia de caso proporcionada.";
    }

    $conn->close();
} else {
    echo "No se proporcionó una referencia de caso válida.";
}
?>


 