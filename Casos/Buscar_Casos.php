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

// Consulta para obtener los casos con sus documentos
$sql = "SELECT casos.*, GROUP_CONCAT(documentos.nombre_archivo) AS archivos_documento 
        FROM casos 
        LEFT JOIN documentos ON casos.referencia = documentos.caso_referencia 
        GROUP BY casos.id";

$result = $conn->query($sql);
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    
/* DIsñeo de pagina */
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

        .active {
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



        

/* Diseño de la tabla */


    .table-container {
    margin: 0 auto;
    max-width: 800px;
    height: 400px;
    margin-left: 150px;
    margin-top: 10px;
    overflow-y: automa 0;
    margin-top: 35px;
    margin-top: 120px;
  
}

.table-container2 {
    margin: 0 auto;
    max-width: 800px;
}

.custom-table {
    display: flex;
    flex-direction: column;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.table-header,
.table-row {
    display: flex;
    
}

.table-cell {
    flex: 1;
    padding: 12px;
    border-bottom: 1px solid #ddd;
    background-color: #ffffff;
    
}

.table-cell:hover {
    background-color: #f2f2f2;
}



.table-header {
    font-weight: bold;
    background-color: #f2f2f2;
}

.no-data {
    padding: 12px;
    text-align: center;
}



 /* Estilo para el campo de entrada de texto */
#inputBusqueda {
  padding: 10px;
        width: 700px; /* Ancho aumentado a 400px */
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s ease;
        margin-top: 50px; /* Añadir margen superior */
}

/* Estilo cuando el campo de entrada está enfocado */
#inputBusqueda:focus {
  outline: none;
        border-color: #66afe9;
}

/* Placeholder */
#inputBusqueda::placeholder {
  color: #999;
}

/* Estilo para el contenedor del campo de entrada */
.container {
  margin: 0 auto;
    
}

.edit-button {
  display: inline-block;
  background-color: white;
  color: black;
  padding: 10px 20px;
  text-decoration: none;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: Bahnschrift;
  position: relative;
    display: inline-block;
    
}

.edit-button:hover {
  background-color: #0056b3;
  color: white;
  font-family: Bahnschrift;
}



.edit-button:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
    background-color: black; 
  font-family: Bahnschrift;
  color: white;
}


.edit-button .tooltiptext {
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
    bottom: 125%; /* Ajusta según la posición deseada */
    left: 50%;
    margin-left: -60px;

    /* Flecha */
    opacity: 0;
    transition: opacity 0.3s;
}

.edit-button:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}



/*___________Archive___________*/


.archive-button {
  display: inline-block;
  background-color: white;
  color: black;
  padding: 10px 20px;
  text-decoration: none;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: Bahnschrift;
  position: relative;
    display: inline-block;
    
}

.archive-button:hover {
  background-color: #0056b3;
  color: white;
  font-family: Bahnschrift;
}



.archive-button:hover .tooltiptext2 {
    visibility: visible;
    opacity: 1;
    background-color: black; 
  font-family: Bahnschrift;
  color: white;
}


.archive-button .tooltiptext2 {
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
    bottom: 125%; /* Ajusta según la posición deseada */
    left: 50%;
    margin-left: -60px;

    /* Flecha */
    opacity: 0;
    transition: opacity 0.3s;
}

.archive-button:hover .tooltiptext2 {
    visibility: visible;
    opacity: 1;
}


/*_____Agregar Casos_____*/

.archive-button {
  display: inline-block;
  background-color: white;
  color: black;
  padding: 10px 20px;
  text-decoration: none;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: Bahnschrift;
  position: relative;
    display: inline-block;
    
}

.archive-button:hover {
  background-color: #0056b3;
  color: white;
  font-family: Bahnschrift;
}



.archive-button:hover .tooltiptext2 {
    visibility: visible;
    opacity: 1;
    background-color: black; 
  font-family: Bahnschrift;
  color: white;
}


.archive-button .tooltiptext2 {
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
    bottom: 125%; /* Ajusta según la posición deseada */
    left: 50%;
    margin-left: -60px;

    /* Flecha */
    opacity: 0;
    transition: opacity 0.3s;
}

.archive-button:hover .tooltiptext2 {
    visibility: visible;
    opacity: 1;
}

/*_____Agregar Casos_____*/


#botonArribaIzquierda {
    position: fixed;
    top: 400px;
    left: 20px;
    padding: 10px 20px;
    background-color: white;
    color: black;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 60px;
    font-family: Arial, sans-serif;
    font-size: 16px;
    margin-left: 1000px;
    transition: background-color 0.3s, color 0.3s;
    display: inline-block; /* Asegura que el tooltip se posicione correctamente */
}

#botonArribaIzquierda:hover {
    background-color: #0056b3;
    color: #fff;
}

/* Estilos para el tooltip */
#botonArribaIzquierda .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%; /* Ajusta la posición del tooltip */
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 0.3s;
    font-family: Arial, sans-serif;
}

#botonArribaIzquierda:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

/*____Archivo____*/

#botonArribaIzquierda3 {
    position: fixed;
    top: 400px;
    left: 20px;
    padding: 10px 20px;
    background-color: white;
    color: black;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 60px;
    font-family: Arial, sans-serif;
    font-size: 16px;
    margin-left: 1070px;
    transition: background-color 0.3s, color 0.3s;
    display: inline-block; /* Asegura que el tooltip se posicione correctamente */
}

#botonArribaIzquierda3:hover {
    background-color: #0056b3;
    color: #fff;
}

/* Estilos para el tooltip */
#botonArribaIzquierda3 .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%; /* Ajusta la posición del tooltip */
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 0.3s;
    font-family: Arial, sans-serif;
}

#botonArribaIzquierda3:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

/* Contenedor de la lista de filtros */
.filter-container {
    width: 250px; /* Aumenté el ancho para mayor espacio */
    padding: 10px;
    background-color: #ffffff; /* Cambié a blanco para un diseño más limpio */
    border-radius: 8px; /* Bordes redondeados */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Añadí sombra para profundidad */
    position: fixed;
    margin-top: 35px;
    left: 0px;
    height: auto; 
    margin-top: 120px;/* Permitir que la altura se ajuste automáticamente */
}

/* Estilos generales de los filtros */
.filter-group {
    margin-bottom: 30px; /* Espaciado entre grupos de filtros */
}

label {
    font-size: 16px; /* Aumenté el tamaño de la fuente para mejor legibilidad */
    font-weight: bold; /* Negrita para los labels */
    color: #333; /* Color de texto más oscuro para contraste */
}

input[type="text"],
input[type="date"] {
    width: calc(100% - 10px); /* Ajusté para incluir padding */
    padding: 10px; /* Aumenté el padding para un área de clic más grande */
    margin-top: 5px;
    margin-bottom: 15px; /* Aumenté el margen inferior para separar más los inputs */
    border: 1px solid #ccc; /* Añadí un borde */
    border-radius: 4px; /* Bordes redondeados para los inputs */
    box-sizing: border-box; /* Incluye el padding y el borde en el ancho total */
    transition: border-color 0.3s; /* Transición suave para el enfoque */
}

input[type="text"]:focus,
input[type="date"]:focus {
    border-color: #007bff; /* Color de borde al enfocar */
    outline: none; /* Sin contorno al enfocar */
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











<center>
 
  </center>
  <div class="container">
<center>

    </center>
    <div class="filter-container">
    <h3>Filtrar por</h3> 
    <!-- Filtro por referencia -->
    <input type="text" id="inputReferencia" placeholder="Filtrar por referencia" onkeyup="filtrarDatos()"> 

    <!-- Filtro por delito -->
    <div>
        <label><input type="checkbox" id="checkboxDelito" onclick="toggleDelitoOptions()"> Delito</label>
        <div id="delitoOptions" style="display:none; padding-left: 15px;">
            <label><input type="checkbox" class="delitoCheckbox" value="robo" onclick="filtrarDatos()"> Robo</label><br>
            <label><input type="checkbox" class="delitoCheckbox" value="asalto" onclick="filtrarDatos()"> Asalto</label><br>
            <label><input type="checkbox" class="delitoCheckbox" value="fraude" onclick="filtrarDatos()"> Fraude</label><br>
            <label><input type="checkbox" class="delitoCheckbox" value="vandalismo" onclick="filtrarDatos()"> Vandalismo</label><br>
            <label><input type="checkbox" class="delitoCheckbox" value="homicidio" onclick="filtrarDatos()"> Homicidio</label><br>
        </div>
    </div>

    <!-- Filtros por fechas -->
    <label for="inputFechaInicio">Fecha Desde:</label>
    <input type="date" id="inputFechaInicio" onchange="filtrarDatos()">
    <label for="inputFechaFin">Fecha Hasta:</label>
    <input type="date" id="inputFechaFin" onchange="filtrarDatos()">

    <!-- Mensaje de no resultados -->
    <p id="noResults" style="display:none;">No se encontraron resultados.</p>
</div>


<div class="table-container">
    <div class="custom-table" id="casosTabla">
        <div class="table-header">
            <div class="table-cell">Referencia</div>
            <div class="table-cell">Víctima</div>
            <div class="table-cell">Imputado</div>
            <div class="table-cell">Tipo de Delito</div>
            <div class="table-cell">Documentos</div>
            <div class="table-cell">Fecha</div>
            <div class="table-cell">Acciones</div>
        </div>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='table-row'>";
                echo "<div class='table-cell referencia'>" . $row["referencia"] . "</div>";
                echo "<div class='table-cell'>" . $row["victima"] . "</div>";
                echo "<div class='table-cell'>" . $row["imputado"] . "</div>";
                echo "<div class='table-cell tipo-delito'>" . $row["tipo_delito"] . "</div>";
                echo "<div class='table-cell'>";

                if (!empty($row["archivos_documento"])) {
                    $archivos_documento = explode(",", $row["archivos_documento"]);
                    foreach ($archivos_documento as $archivo) {
                        echo "<a href='documentos/" . $archivo . "' target='_blank'>" . $archivo . "</a><br>";
                    }
                } else {
                    echo "Sin documentos";
                }

                echo "</div>";
                echo "<div class='table-cell fecha'>" . $row["fecha_creacion"] . "</div>";
                echo "<div class='table-cell'>";
                echo "<center>
                        <a class='edit-button' href='ver_detalle_caso.php?referencia=" . $row['referencia'] . "'>
                            <i class='fas fa-info-circle'></i>
                            <span class='tooltiptext'>Detalles de Caso</span>
                        </a>
                      </center>";

                echo "<center>";
                echo "<button class='archive-button' onclick=\"archivarCaso('" . $row['referencia'] . "')\">
                        <i class='fas fa-archive'></i>
                        <span class='tooltiptext2'>Archivar caso</span>
                      </button>";
                echo "</center>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='no-data' colspan='6'>No hay casos registrados.</div>";
        }
        ?>
    </div>
</div>

<!-- Modal para confirmar archivado -->
<!-- Modal para confirmar archivado -->
<div id="archiveModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>Se descargarán las evidencias y documentos. ¿Estás seguro de que deseas archivar este caso?</p>
    <button id="confirmArchive" class="modal-button"><i class="fas fa-check"></i></button>
    <button id="cancelArchive" class="modal-button2">X</button>
  </div>
</div>

<!-- Contenedor de notificación -->
<div id="notification" class="notification">
  <p id="notificationMessage"></p>
</div>


<style>
/* Estilos para el modal */
.modal {
  display: none; /* Oculto por defecto */
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: white;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 400px;
  border-radius: 10px;
  font-family: Bahnschrift;
  text-align: center;
}

.modal-content p {
  margin-bottom: 20px;
  font-size: 16px;
}

.modal-button {
  background-color: white;
  color: black;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: Bahnschrift;
  margin: 5px;
}

.modal-button:hover {
  background-color: #003f7f;
  color: white;
  
}


.modal-button2 {
  background-color: #0056b3;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: Bahnschrift;
  margin: 5px;
}

.modal-button2:hover {
  background-color: #003f7f;
}
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

/* Estilos para la notificación */
.notification {
  display: none; /* Oculto por defecto */
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #0056b3;
  color: white;
  padding: 15px;
  border-radius: 5px;
  font-family: Bahnschrift;
  z-index: 1001;
}

.notification.show {
  display: block;
}


</style>




<script>






function toggleDelitoOptions() {
    var delitoOptions = document.getElementById("delitoOptions");
    delitoOptions.style.display = delitoOptions.style.display === "none" ? "block" : "none";
}

function filtrarDatos() {
    var inputReferencia = document.getElementById("inputReferencia").value.toLowerCase();
    var checkboxesDelito = document.querySelectorAll(".delitoCheckbox:checked");
    var delitosSeleccionados = Array.from(checkboxesDelito).map(function(checkbox) {
        return checkbox.value;
    });
    var fechaInicio = document.getElementById("inputFechaInicio").value;
    var fechaFin = document.getElementById("inputFechaFin").value;

    var tableRows = document.querySelectorAll(".custom-table .table-row");
    var noResults = document.getElementById("noResults");
    var hayResultados = false;

    tableRows.forEach(function(row) {
        var referencia = row.querySelector(".referencia").textContent.toLowerCase();
        var tipoDelito = row.querySelector(".tipo-delito").textContent.toLowerCase();
        var fechaCaso = row.querySelector(".fecha").textContent;

        var mostrar = true;

        // Filtro por referencia
        if (inputReferencia && !referencia.includes(inputReferencia)) {
            mostrar = false;
        }

        // Filtro por tipo de delito
        if (delitosSeleccionados.length > 0 && !delitosSeleccionados.includes(tipoDelito)) {
            mostrar = false;
        }

        // Filtro por fechas
        if (fechaInicio && fechaFin) {
            if (fechaCaso < fechaInicio || fechaCaso > fechaFin) {
                mostrar = false;
            }
        }

        if (mostrar) {
            row.style.display = "flex";
            hayResultados = true;
        } else {
            row.style.display = "none";
        }
    });

    noResults.style.display = hayResultados ? "none" : "block";
}












// Función para realizar la búsqueda filtrando solo por las columnas seleccionadas
function buscarCasos() {
    var input, filter, table, rows, cells, i, j, cellValue;
    input = document.getElementById("inputBusqueda");
    filter = input.value.toLowerCase();
    table = document.getElementById("casosTabla");
    rows = table.getElementsByClassName("table-row");

    for (i = 0; i < rows.length; i++) {
        cells = rows[i].getElementsByClassName("table-cell");
        var shouldDisplay = false;

        // Busca en todas las columnas
        for (j = 0; j < cells.length - 1; j++) { // Excluye la última columna (acciones)
            if (cells[j]) {
                cellValue = cells[j].textContent || cells[j].innerText;
                if (cellValue.toLowerCase().indexOf(filter) > -1) {
                    shouldDisplay = true;
                    break;
                }
            }
        }

        if (shouldDisplay) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}

function ordenarCasos(columna) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("casosTabla");
    switching = true;
    dir = "asc"; // Dirección inicial ascendente

    while (switching) {
        switching = false;
        rows = table.getElementsByClassName("table-row");

        for (i = 0; i < rows.length - 1; i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByClassName(columna)[0];
            y = rows[i + 1].getElementsByClassName(columna)[0];

            if (dir == "asc") {
                if (x.innerText.toLowerCase() > y.innerText.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerText.toLowerCase() < y.innerText.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }

        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}

















function archivarCaso(referencia) {
    // Mostrar el modal
    var modal = document.getElementById("archiveModal");
    modal.style.display = "block";

    // Obtener botones del modal
    var confirmBtn = document.getElementById("confirmArchive");
    var cancelBtn = document.getElementById("cancelArchive");
    var closeBtn = document.getElementsByClassName("close")[0];

    // Cuando el usuario confirma el archivado
    confirmBtn.onclick = function() {
        modal.style.display = "none";

        // Mostrar notificación
        showNotification("El caso ha sido archivado exitosamente.");

        // Simular la redirección después de un corto tiempo
        setTimeout(function() {
            window.location.href = "archivar_caso.php?referencia=" + referencia;
        }, 1000); // Cambiar a 0 o eliminar para redirigir inmediatamente
    }

    // Cuando el usuario cancela el archivado o cierra el modal
    cancelBtn.onclick = closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Cerrar el modal si el usuario hace clic fuera de él
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

function showNotification(message) {
    var notification = document.getElementById("notification");
    var notificationMessage = document.getElementById("notificationMessage");
    
    notificationMessage.innerText = message;
    notification.classList.add("show");

    // Ocultar la notificación después de 3 segundos
    setTimeout(function() {
        notification.classList.remove("show");
    }, 3000);
}




function ordenarPorReferencia() {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("casosTabla");
    switching = true;

    // Hacer un bucle hasta que no haya más cambios
    while (switching) {
        switching = false;
        rows = table.getElementsByClassName("table-row");

        // Bucle a través de todas las filas de la tabla (excepto el encabezado)
        for (i = 0; i < (rows.length - 1); i++) {
            shouldSwitch = false;

            // Obtener los dos elementos que se compararán
            x = rows[i].getElementsByClassName("referencia")[0];
            y = rows[i + 1].getElementsByClassName("referencia")[0];

            // Verificar si las dos filas deben cambiar de lugar
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
            }
        }

        if (shouldSwitch) {
            // Hacer el cambio y marcar que se ha hecho un cambio
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}

// Ejecutar la función de ordenación al cargar la página
window.onload = function() {
    ordenarPorReferencia();
};











function eliminarCaso(referencia) {
    if (confirm("¿Estás seguro de que deseas eliminar este caso?")) {
        // Redireccionar a la página de eliminación con la referencia del caso
        window.location.href = "eliminar_caso.php?referencia=" + referencia;
    }
}


document.querySelector('a[href="?logout"]').addEventListener('click', function(event) {
    if (!confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        event.preventDefault(); // Cancelar el evento de clic si el usuario no confirma
    }
});

document.addEventListener("DOMContentLoaded", function() {
  const btnNav = document.getElementById("btn-nav");
  const nav = document.querySelector("nav");

  // Función para abrir/cerrar el menú de navegación
  function toggleNav() {
    nav.classList.toggle("open");
  }

  // Evento click en el botón de navegación
  btnNav.addEventListener("click", function() {
    toggleNav();
  });

  // Evento click fuera del menú para cerrarlo
  document.addEventListener("click", function(event) {
    if (!nav.contains(event.target) && !btnNav.contains(event.target)) {
      nav.classList.remove("open");
    }
  });
});

</script>
    
</body>
</html>
