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

$sql = "SELECT nombre, apellido, telefono, tipo, correo FROM usuarios WHERE id = $user_id";
$result = $conn->query($sql);

// Cerrar conexión
$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Audiencias Registradas</title>

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

        .active  {
            background-color:#374D63;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
           
        }
        ul li a:hover {
            background-color:#374D63;
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











    .content {
    margin-top: 50px; /* Espacio superior para separar del contenido anterior */
    font-family: Bahnschrift;
}

.table-container {
    margin: 0 auto;
    max-width: 800px;
    height: 400px;
    overflow-y: auto;
    font-family: Bahnschrift;
}

.table-container2 {
    margin: 0 auto;
    max-width: 800px;
    font-family: Bahnschrift;
}

.custom-table {
    display: flex;
    flex-direction: column;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    font-family: Bahnschrift;
}

.table-header,
.table-row {
    display: flex;
    font-family: Bahnschrift;
}

.table-cell {
    flex: 1;
    padding: 12px;
    border-bottom: 1px solid #ddd;
    font-family: Bahnschrift;
}

.table-header {
    font-weight: bold;
    background-color: #f2f2f2;
    font-family: Bahnschrift;
}

.no-data {
    padding: 12px;
    text-align: center;
    font-family: Bahnschrift;
}
/* Estilo para el campo de entrada de texto */



    /* Estilos para el campo de búsqueda */
    #searchInput {
        padding: 10px;
        width: 700px; /* Ancho aumentado a 400px */
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s ease;
        margin-top: 100px; /* Añadir margen superior */
        margin-block-end: 50px;
        font-family: Bahnschrift;
    }

    #searchInput:focus {
        outline: none;
        border-color: #66afe9;
        font-family: Bahnschrift;
    }

    /* Estilos para el placeholder del campo de búsqueda */
    ::placeholder {
        color: #aaa;
        font-family: Bahnschrift;
    }

    #botonArribaIzquierda {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: 2px solid #007bff;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
            margin-left: 1000px;
            margin-top: 50px;
            font-family: Bahnschrift;
        }

        #botonArribaIzquierda:hover {
            background-color: #0056b3;
            color: #fff;
            font-family: Bahnschrift;
        }

        /* Estilos para el botón */
        .edit-button {
  display: inline-block;
  background-color: white; /* Color de fondo del botón */
  color: black; /* Color del texto del botón */
  padding: 10px 20px; /* Espaciado interno del botón */
  text-decoration: none; /* Eliminar subrayado del enlace */
  border: none; /* Quitar borde del botón */
  border-radius: 5px; /* Borde redondeado */
  cursor: pointer; /* Cambiar cursor al pasar sobre el botón */
  font-family: Bahnschrift;
}

/* Estilos para cuando el cursor pasa sobre el botón */
.edit-button:hover {
  background-color: #0056b3;
  color: white; /* Cambiar color de fondo cuando el cursor pasa sobre el botón */
  font-family: Bahnschrift;
}

.solicitar-button {
  display: inline-block;
  background-color: white; /* Color de fondo del botón */
  color: black; /* Color del texto del botón */
  padding: 10px 20px; /* Espaciado interno del botón */
  text-decoration: none; /* Eliminar subrayado del enlace */
  border: none; /* Quitar borde del botón */
  border-radius: 5px; /* Borde redondeado */
  cursor: pointer; /* Cambiar cursor al pasar sobre el botón */
  font-family: Bahnschrift;
}

/* Estilos para cuando el cursor pasa sobre el botón */
.solicitar-button:hover {
  background-color: #0056b3;
  color: white; /* Cambiar color de fondo cuando el cursor pasa sobre el botón */
  font-family: Bahnschrift;
}


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
    font-family: Bahnschrift;
}

.modal.show {
    display: flex;
    opacity: 1;
    font-family: Bahnschrift;
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
    font-family: Bahnschrift;
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
    font-family: Bahnschrift;
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
    font-family: Bahnschrift;
}

/* Estilos para el hover y focus */
button[type="submit"]:hover,
button[type="submit"]:focus {
    background-color: #0056b3; /* Color de fondo al pasar el cursor */
    outline: none; /* Sin contorno */
    transform: translateY(-2px); /* Efecto de elevación */
    font-family: Bahnschrift;
}

/* Estilos para el active */
button[type="submit"]:active {
    background-color: #004085; /* Color de fondo al hacer clic */
    transform: translateY(0); /* Restablecer elevación */
    font-family: Bahnschrift;
}



#deleteRequestLink {
    display: inline-block;
    padding: 10px 20px;
    background-color: white;
    color: black;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    font-family: Bahnschrift;
}

#deleteRequestLink:hover {
    background-color: #0056b3;
    color: white;
    font-family: Bahnschrift;
}



.hidden {
  opacity: 0;
  pointer-events: none;
}

.notification-icon {
    margin-top: 35px;
    margin-left: 1000px;
    top: 0;
    right: 0;
    /* Ajusta el color según sea necesario */
    color: white;
    border-radius: 50%;
    padding: 5px;
    font-size: 20px; /* Ajusta el tamaño del ícono según sea necesario */
    transform: translate(50%, -50%);
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
            <li><a href="/Audiencias/Buscar_Audiencias.php" class="active">Audiencias</a></li>
            <li><a href="/apps.php">Aplicaciones</a></li>
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





  <?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $conn->real_escape_string($_POST['nombre_usuario']);
    $reason = $conn->real_escape_string($_POST['reason']);
    $fecha_hora = $conn->real_escape_string($_POST['fecha_hora']);

    $sql = "INSERT INTO solicitudes (nombre_usuario, reason, fecha_hora) VALUES ('$nombre_usuario', '$reason', '$fecha_hora')";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "Solicitud enviada correctamente.";
    } else {
        $mensaje = "Error al enviar la solicitud: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>


    
  <?php if ($result->num_rows > 0): ?>
    <?php $row = $result->fetch_assoc(); ?>




  
     <?php else: ?>
        <p class="error-message">Error: No se encontraron detalles del usuario.</p>
    <?php endif; ?>
 


<div class="table-container2">
<input type="text" id="searchInput" placeholder="Buscar...">
</div>
<div class="table-container">

  <div class="custom-table">
        <div class="table-header">
            <div class="table-cell">Título</div>
            <div class="table-cell">Referencia</div>
            <div class="table-cell">Modalidad</div>
            <div class="table-cell">Fecha</div>
            <div class="table-cell">Hora</div>
            <div class="table-cell">Imputado</div>
            <div class="table-cell">Víctima</div>
            <div class="table-cell">Delito</div>
            <div class="table-cell">Acciones</div>
        </div>
        <?php
    // Conexión a la base de datos MySQL
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "legalcc";

    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }
// Eliminar registro si se ha enviado el ID
if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "DELETE FROM audiencias WHERE id=$id";
  if ($conn->query($sql) === TRUE) {
    header("Location: Buscar_Audiencia.php");
  } else {
      echo "Error al eliminar el registro: " . $conn->error;
  }
}

    // Consulta para obtener todas las audiencias
    // Consulta para obtener todas las audiencias ordenadas por fecha de forma descendente
$sql = "SELECT * FROM audiencias ORDER BY fecha DESC";

    $result = $conn->query($sql);

    // Mostrar datos de cada audiencia
    if ($result->num_rows > 0) {
        echo "<div class='table'>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='table-row'>";
            echo "<div class='table-cell'>" . $row["titulo"] . "</div>";
            echo "<div class='table-cell'>" . $row["caso"] . "</div>";
            echo "<div class='table-cell'>" . $row["modalidad"] . "</div>";
            echo "<div class='table-cell'>" . $row["fecha"] . "</div>";
            echo "<div class='table-cell'>" . $row["hora"] . "</div>";
            echo "<div class='table-cell'>" . $row["imputado"] . "</div>";
            echo "<div class='table-cell'>" . $row["victima"] . "</div>";
            echo "<div class='table-cell'>" . $row["delito"] . "</div>";
            echo "<div class='table-cell'>";
            echo "<center><a class='edit-button' href='ver_audiencia.php?id=" . $row["id"] . "'><i class='fa fa-eye'></i></a></center>";
    
            if ($tipo_usuario === 'juez') {
                echo "<center><a class='edit-button' href='#' onclick='eliminar(" . $row["id"] . ")'><i class='fa fa-trash'></i></a></center>";
            }
    
            if ($tipo_usuario === 'fiscal' || $tipo_usuario === 'abogado') {
                echo "<center><a class='edit-button' href='solicitud_audiencia.php?id=" . $row["id"] . "' title='Enviar una solicitud'><i class='fa fa-paper-plane'></i></a></center>";

            }
    
            echo "</div>";
            echo "</div>"; // Cierra el div de la fila
        }
        echo "</div>"; // Cierra el div de la tabla
    } else {
        echo "<center>No hay audiencias registradas.</center>";
    }
    
    // Cerrar la conexión
    $conn->close();
    ?>
</table>
    </div>
</div>








<script>

window.onload = function() {
            // Obtener los parámetros de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const mensaje = urlParams.get('mensaje');

            // Mostrar el mensaje de éxito si el parámetro 'mensaje' es 'exito'
            if (mensaje === 'exito') {
                alert('Solicitud enviada con éxito');
                // Opcional: Redirigir a otra página después de mostrar el mensaje
                window.location.href = 'buscar_audiencias.php';
            }
        }
      
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


function solicitar(id) {
    document.getElementById('popup').style.display = 'block';
  }

  function enviarSolicitud() {
    var descripcion = document.getElementById('descripcion').value;
    var fecha_hora = document.getElementById('fecha_hora').value;
    
    // Aquí puedes enviar los datos al servidor para su procesamiento
    // Por ejemplo, podrías usar AJAX para enviar una solicitud al servidor

    // Luego de enviar la solicitud, puedes ocultar el popup
    document.getElementById('popup').style.display = 'none';

    // También podrías realizar alguna otra acción, como mostrar un mensaje de éxito al usuario
    alert("Solicitud enviada correctamente");
  }
    // Función para filtrar las filas de la tabla
    function filterTable() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector(".custom-table");
        tr = table.querySelectorAll(".table-row");

        // Iterar sobre todas las filas y ocultar aquellas que no coincidan con la búsqueda
        for (i = 0; i < tr.length; i++) {
            td = tr[i].querySelectorAll(".table-cell");
            for (j = 0; j < td.length; j++) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break; // Mostrar la fila si alguna celda coincide con la búsqueda
                } else {
                    tr[i].style.display = "none"; // Ocultar la fila si no hay coincidencia
                }
            }
        }
    }

    // Evento de escucha para el cambio en el campo de búsqueda
    document.getElementById("searchInput").addEventListener("input", filterTable);

        function eliminar(id) {
            if (confirm("¿Estás seguro de que quieres eliminar este registro?")) {
                // Enviar una petición AJAX para eliminar el registro
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Recargar la página después de eliminar el registro
                        window.location.reload();
                    }
                };
                xhr.open("GET", "?id=" + id, true);
                xhr.send();
            }
        }


        document.querySelector('a[href="?logout"]').addEventListener('click', function(event) {
    if (!confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        event.preventDefault(); // Cancelar el evento de clic si el usuario no confirma
    }
});

function verMas() {
        // Hacer scroll hacia abajo para mostrar más elementos
        window.scrollBy(0, window.innerHeight);
    }








    window.addEventListener('load', function() {
            setTimeout(function() {
                alert('¡Solicitud Enviada!');
            }, 500); // Tiempo de retraso en milisegundos (500ms = 0.5s)
        });
    </script>

</body>
</html>


