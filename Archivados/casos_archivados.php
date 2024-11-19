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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Casos Archivados</title>
    <style>

       



  .table-container, .table-container2 {
    margin: 0 auto;
    max-width: 1000px;
}

.table-container {
    height: 400px;
    margin-top: 10px;
    overflow-y: auto;
}

.custom-table {
    display: flex;
    flex-direction: column;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.table-header, .table-row {
    display: flex;
}

.table-cell {
    flex: 1;
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.table-header {
    font-weight: bold;
    background-color: #f2f2f2;
}

.no-data {
    padding: 12px;
    text-align: center;
}

#botonArribaIzquierda {
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
            margin-left: 1200px;
            margin-top: 50px;
        }

        #botonArribaIzquierda:hover {
    background-color: #0056b3;
    color: white;
}

/* Estilos para el tooltip */
#botonArribaIzquierda .tooltip {
    visibility: hidden;
    width: 100px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%; /* Ajusta la posición del tooltip */
    left: 50%;
    margin-left: -50px; /* Centra el tooltip */
    opacity: 0;
    transition: opacity 0.3s;
    font-family: Arial, sans-serif;
}

#botonArribaIzquierda:hover .tooltip {
    visibility: visible;
    opacity: 1;
}








        .table-search {

        margin-left: 390px;
        padding: 10px;
        width: 700px; /* Ancho aumentado a 400px */
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s ease;
        margin-top: 50px; /* Añadir margen superior */
}

.table-search:focus {
  outline: none;
  border-color: #66afe9;
}











.btn-restaurar{
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
            background-color: white;
    color: black;
  
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-family: Arial, sans-serif;
    font-size: 16px;
    transition: background-color 0.3s, color 0.3s;
    position: relative
        }

        .btn-restaurar:hover {
            background-color: #0056b3; /* Darker Green on Hover */
            color: #fff;
            font-family: Bahnschrift;
            background-color: #0056b3;
            color: white; 
        }


        .btn-restaurar .tooltiptext {
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

.btn-restaurar:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}




.popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
}

.popup-content {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    text-align: center;
    z-index: 1001;
    max-width: 400px;
    width: 100%;
}

.popup-content h2 {
    margin-bottom: 20px;
    font-size: 24px;
}

.popup-content p {
    margin-bottom: 30px;
}

.btn-confirmar, .btn-cancelar {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    margin: 5px;
    cursor: pointer;
    font-size: 16px;
}





.btn-confirmar {
   
    background-color: white;
    color: black;
    font-family: Bahnschrift;
}
.btn-confirmar:hover {
 
            color: #fff;
            font-family: Bahnschrift;
            background-color: #0056b3;
            color: white;
            
            
}




.btn-cancelar {
  background-color:#0056b3 ;
  color: white;
}

.btn-cancelar:hover {
  background-color: #1C205C;
  color: white; 
}



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

.notification.visible {
  display: block;
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

        .activo {
            background-color:#374D63;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }



    </style>
</head>
<body>
<nav>
        <ul>
            <li><a href="/Pagina_principal.php">Inicio</a></li>
            <li>
                <a href="" class="activo">Archivados</a>
                <ul>
                    <li><a href="/casos/victima/tabla_de_victima.php">Victimas</a></li>
                    <li><a href="/casos/imputados/tabladeimputados.php">Imputados</a></li>
                    <li><a href="/Casos/agregar_casos.php">Casos</a></li>
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
<h1>Casos Archivados</h1>
</center>


<a id="botonArribaIzquierda" href="/Casos/Buscar_Casos.php">
    <i class="fas fa-table"></i>
    <span class="tooltip">Tabla de Casos</span>
</a>

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

// Consultar los casos archivados
$sql = "SELECT referencia, victima, imputado, tipo_delito, fecha_creacion, fecha_expiracion, archivos_documento 
        FROM casos_archivados 
        ORDER BY fecha_creacion DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

  echo "<input type='text' id='buscador' class='table-search' placeholder='Buscar casos archivados...'>";


  
  echo "<div class='table-container'><table class='custom-table' id='tablaCasos'>
          <tr class='table-header'>
              <th class='table-cell'>Referencia</th>
              <th class='table-cell'>Víctima</th>
              <th class='table-cell'>Imputado</th>
              <th class='table-cell'>Tipo de Delito</th>
              <th class='table-cell'>Fecha de Creación</th>
              <th class='table-cell'>Tiempo para Prescribir</th>
              <th class='table-cell'>Acciones</th>
          </tr>";
  
  // Mostrar cada caso archivado
  while($row = $result->fetch_assoc()) {
      $archivo = htmlspecialchars($row['archivos_documento']);
      $ruta_documento = "Casos/documentos/" . $archivo; // Cambia esta ruta según corresponda

      echo "<tr class='table-row'>
      <td class='table-cell'>" . htmlspecialchars($row['referencia']) . "</td>
      <td class='table-cell'>" . htmlspecialchars($row['victima']) . "</td>
      <td class='table-cell'>" . htmlspecialchars($row['imputado']) . "</td>
      <td class='table-cell'>" . htmlspecialchars($row['tipo_delito']) . "</td>
      <td class='table-cell'>" . htmlspecialchars($row['fecha_creacion']) . "</td>
      <td class='table-cell'>" . htmlspecialchars($row['fecha_expiracion']) . "</td>
      <td class='table-cell'>
          <a href='#' onclick='mostrarPopupRestauracion(\"" . urlencode($row['referencia']) . "\")'>
              <button class='btn-restaurar'>
                  <i class='fas fa-undo'></i>
                  <span class='tooltiptext'>Restaurar Caso</span>
              </button>
          </a>
      </td>
    </tr>";

  }
  echo "</table></div>";
} else {
  echo "<div class='no-data' colspan='6'>No hay casos archivados.</div>";
}

// Cerrar la conexión
$conn->close();
?>
<div id="popupRestauracion" class="popup-overlay">
    <div class="popup-content">
      
        <h2>Confirmación</h2>
        <center>
        <p>¿Estás seguro de que deseas restaurar este caso?</p>
        </center>
       
        <button class="btn-confirmar" id="btnConfirmar"><i class="fas fa-check"></i></button>

              
        <button class="btn-cancelar" onclick="cerrarPopupRestauracion()">X</i></button>
    </div>
</div>
<div id="notification" class="notification hidden">
    <p>El caso ha sido restaurado correctamente.</p>
    
</div>

<script>


function mostrarPopupRestauracion(referencia) {
    var popup = document.getElementById('popupRestauracion');
    popup.style.display = 'block';
    
    // Asigna la referencia al botón de confirmación
    var btnConfirmar = document.getElementById('btnConfirmar');
    btnConfirmar.setAttribute('onclick', 'confirmarRestauracion("' + referencia + '")');
}

function cerrarPopupRestauracion() {
    var popup = document.getElementById('popupRestauracion');
    popup.style.display = 'none';
}

function confirmarRestauracion(referencia) {
    cerrarPopupRestauracion();
    
    var notification = document.getElementById('notification');
    notification.classList.remove('hidden');
    notification.classList.add('visible');

    // Simular restauración del caso (puedes reemplazar esto con la lógica real de restauración)
    console.log('Caso restaurado: ' + referencia);

    // Ocultar la notificación después de unos segundos y redirigir
    setTimeout(function() {
        notification.classList.remove('visible');
        notification.classList.add('hidden');
        
        // Redirigir después de ocultar la notificación
        window.location.href = 'restaurar_caso.php?referencia=' + referencia;
    }, 3000);
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


// Filtrar la tabla en tiempo real
document.getElementById('buscador').addEventListener('keyup', function() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById('buscador');
    filter = input.value.toUpperCase();
    table = document.getElementById('tablaCasos');
    tr = table.getElementsByTagName('tr');

    for (i = 1; i < tr.length; i++) { // Comienza en 1 para omitir la fila de encabezado
        tr[i].style.display = 'none';
        td = tr[i].getElementsByTagName('td');
        for (var j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                    break;
                }
            }
        }
    }
});

</script>

</body>
</html>

