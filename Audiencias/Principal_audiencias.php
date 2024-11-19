<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: Upss.php");
    exit();
}

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
// Conexión a la base de datos MySQL
$servername = "localhost";
$username = "root";
$password = "";
$database = "legalcc";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos");
}

$sql = "SELECT referencia FROM casos";
$result = $conn->query($sql);

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopilar datos del formulario
    $titulo = $_POST["titulo"];
    $caso = $_POST["caso"];
    $modalidad = $_POST["modalidad"];
    $fecha = $_POST["fecha"];
    $hora = $_POST["hora"];
    $imputado = $_POST["imputado"];
    $victima = $_POST["victima"];
    $delito = $_POST["delito"];
    $descripcion = $_POST["descripcion"];
    $juzgado = $_POST["juzgado"];
    $abogado = $_POST["abogado"];
    $fiscal = $_POST["fiscal"];
    $sala = $_POST["sala"];
    $juez_suplente = $_POST["juez_suplente"];
    
    // Insertar datos en la base de datos
    $sql = "INSERT INTO audiencias (titulo, caso, modalidad, fecha, hora, imputado, victima, delito, descripcion, juzgado, abogado, fiscal, sala, juez_suplente)
    VALUES ('$titulo', '$caso', '$modalidad', '$fecha', '$hora', '$imputado', '$victima', '$delito', '$descripcion', '$juzgado', '$abogado', '$fiscal', '$sala', '$juez_suplente')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: Buscar_Audiencias.php");
        exit;
    } else {
        echo "Error al registrar la audiencia: " . $conn->error;
    }
    
    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Audiencias</title>

    <style>

:root {
  --main-color: #242975; /* Cambio de color principal */
  --accent-color: #2D6653; /* Nuevo color de acento */
}

* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}



.main-header {
  background: var(--main-color); /* Usar el color principal */
  width: 100%;
  height: 50px;
  display: flex; /* Alinear el contenido del encabezado */
  align-items: center; /* Alinear verticalmente */
  justify-content: space-between; /* Espacio entre los elementos */
  padding: 0 20px; /* Agregar un poco de espacio alrededor del contenido */
  margin-top: 0px;
}

nav {
  position: absolute;
  left: 0;
  top: 50px;
  width: 200px;
  height: calc(100vh - 50px);
  background: var(--accent-color); /* Usar el nuevo color de acento */
  transform: translateX(-100%);
  transition: .4s ease;
  background-color: #E6F0FF;
  
}

.navigation li {
  list-style: none;
  width: 100%;
  border-bottom: 1px solid rgba(255, 255, 255, 0.5);

  
}

.navigation a {
  color: #242975; /* Cambiar el color del texto a blanco */
  background-color: #E6F0FF;
  display: block;
  line-height: 3.5;
  padding: 15px 20px; /* Aumentar el espacio alrededor del texto */
  text-decoration: none;
  transition: .4s ease;
  font-family: Bahnschrift;
}

.navigation a:hover {
  background-color: #242975; /* Agregar un color de fondo al pasar el cursor */
  color: #E6F0FF;
  font-family: Bahnschrift;
}

#btn-nav {
  display: none;
}

#btn-nav:checked ~ nav {
  transform: translateX(0);
}

.btn-nav {
  color: #fff; /* Cambiar el color del botón a blanco */
  font-size: 20px; /* Reducir un poco el tamaño del botón */
  cursor: pointer;
  padding: 10px 15px; /* Ajustar el espacio alrededor del botón */
  transition: .2s ease;
  background: transparent; /* Hacer el botón transparente */
  border: none; /* Eliminar el borde del botón */
  outline: none; /* Eliminar el contorno del botón al hacer clic */
}

.btn-nav:hover {
  background: rgba(255, 255, 255, 0.1); /* Cambiar el color de fondo al pasar el cursor */
}

.circle-container {
        width: 70px;
        height: 70px;
        border-radius: 50%; /* Esto hace que el borde sea redondeado, creando un círculo */
        overflow: hidden; /* Oculta cualquier contenido fuera del círculo */
        margin: 50px; /* Añade un margen de 10px alrededor del círculo */
        border: 2px solid #ccc; /* Agrega un borde para mayor claridad */
    }
    
    /* Estilo para la imagen */
    .circle-image {
        width: 100%; /* Ajusta el ancho de la imagen al 100% del contenedor */
        height: auto; /* Mantiene la proporción de la imagen */
    }
    h1{
      color: W;
      font-size: 10px;
      font-family: Bahnschrift;
    }
    h2{
      color: white;
      font-size: 20px;
      font-family: Bahnschrift;
    }
 body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .container {
    max-width: 1000px;
    margin: 0px auto;
    padding: 0px;
    font-family: Bahnschrift, Arial, sans-serif; /* Agregamos Bahnschrift como primera opción */
}

    h2 {
        text-align: center;
    }
    label {
        font-weight: bold;
    }
    input[type="text"],
    input[type="date"],
    input[type="time"],
    select,
    textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        width: 20%;
background-color: #1E90FF;
color: white;
padding: 10px;
border: none;
border-radius: 4px;
cursor: pointer;
font-size: 16px;
float: right;

    }
    input[type="submit"]:hover {
        background-color: #242975;
    }


</style>

</head>
<body>

<header class="main-header">

    <label for="btn-nav" class="btn-nav">&#9776;</label>
    <input type="checkbox" id="btn-nav">
    <h2>LegalConnect</h2>
    <nav>
      <ul class="navigation">
<center>
<a href="/Formularios/Perfil.php">
<div class="circle-container">

    <img class="circle-image" src="recursos/profile.png" alt="Tu imagen">

   </div>
   </a>
        <li><a href="/Pagina_principal.php">Inicio</a></li>
        <li><a href="/Audiencias/Buscar_Audiencias.php">Audiencias</a></li>
        <li><a href="/Casos/Agregar_Casos.php">Casos</a></li>
        <li><a href="?logout">Cerrar Sesion</a></li>
        <h1>LegalConnect v.1</h1>
      </ul>
    </nav>
    </center>
  </header>




<div class="container">

<h2>Registro de Audiencias</h2>

<div style="display: flex; justify-content: space-between; width: 100%;">
    <div style="width: 48%;">
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label>Tipo de Audiencia:</label><br>
            <input type="text" name="titulo" required><br><br>
        </div>
      
<div style="width: 48%;">
    <label>Seleccionar Caso:</label><br>
    <select name="caso">
        <?php
        // Verificar si hay resultados de la consulta
        if ($result->num_rows > 0) {
            // Imprimir cada opción del menú desplegable
            while($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["referencia"] . "'>" . $row["referencia"] . "</option>";
            }
        } else {
            echo "<option value=''>No hay casos disponibles</option>";
        }
        ?>
    </select><br><br>
</div>
<?php
// Cerrar la conexión a la base de datos
$conn->close();
?> 
</div>
<div style="display: flex; justify-content: space-between; width: 100%;">
    <div style="width: 48%;">
            <label>Modalidad de Audiencias:</label><br>
            <select name="modalidad">
                <option value="Presencial">Presencial</option>
                <option value="Virtual">Virtual</option>
            </select><br><br>
        </div>
    <div style="width: 48%;">     
            <label>Seleccionar Fecha:</label><br>
            <input type="date" name="fecha" required><br><br>
        </div>
    <div style="width: 48%;"> 
            <label>Seleccionar Hora:</label><br>
            <input type="time" name="hora" required><br><br>
        </div>
</div>       

<div style="display: flex; justify-content: space-between; width: 100%;">
    <div style="width: 48%;">
        <label>Nombre del Imputado:</label><br>
        <input type="text" name="imputado" required><br><br>
    </div>
    <div style="width: 48%;">       
        <label>Nombre de la Víctima:</label><br>
        <input type="text" name="victima" required><br><br>
    </div>
    <div style="width: 48%;">       
        <label>Seleccionar Tipo de Delito:</label><br>
        <select name="delito">
            <option value="Homicidio">Homicidio</option>
            <option value="Violación">Violación</option>
            <option value="Hurto">Hurto</option>
        </select><br><br>
    </div>
</div>       
<div style="display: flex; justify-content: space-between; width: 100%;">
    <div style="width: 48%;">   
            <label>Descripción de la Audiencia:</label><br>
            <textarea name="descripcion" rows="4" cols="50"></textarea><br><br>
            </div>
</div>  
<div style="display: flex; justify-content: space-between; width: 100%;">
    <div style="width: 48%;">           
            <label>Seleccionar Juzgado:</label><br>
            <select name="juzgado">
                <option value="Juzgado 1">Juzgado 1</option>
                <option value="Juzgado 2">Juzgado 2</option>
                <option value="Juzgado 3">Juzgado 3</option>
            </select><br><br>
        </div>
    <div style="width: 48%;">           
            <label>Seleccionar Abogado:</label><br>
            <select name="abogado">
                <option value="Abogado 1">Abogado 1</option>
                <option value="Abogado 2">Abogado 2</option>
                <option value="Abogado 3">Abogado 3</option>
            </select><br><br>
        </div>
    <div style="width: 48%;">           
            <label>Seleccionar Fiscal:</label><br>
            <select name="fiscal">
                <option value="Fiscal 1">Fiscal 1</option>
                <option value="Fiscal 2">Fiscal 2</option>
                <option value="Fiscal 3">Fiscal 3</option>
            </select><br><br>
        </div>
</div>     

<div style="display: flex; justify-content: space-between; width: 100%;">
    <div style="width: 48%;"> 
            <label>Sala de Audiencias:</label><br>
            <select name="sala">
                <option value="Sala 1">Sala 1</option>
                <option value="Sala 2">Sala 2</option>
                <option value="Sala 3">Sala 3</option>
            </select><br><br>
        </div>
<div style="width: 48%;">               
            <label>Seleccionar Juez:</label><br>
            <select name="juez_suplente">
                <option value="Suplente 1">Juez 1</option>
                <option value="Suplente 2">Juez 2</option>
                <option value="Suplente 3">Juez 3</option>
            </select><br><br>
        </div>
</div>           
            <input type="submit" value="Registrar Audiencia">
        </form>


        <script>
    // Función para cargar los datos del caso seleccionado
    function cargarDatosCaso() {
        // Obtener el valor seleccionado del menú desplegable
        var casoSeleccionado = document.getElementsByName("caso")[0].value;

        // Realizar una solicitud AJAX para obtener los datos del caso seleccionado
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Convertir la respuesta JSON en un objeto JavaScript
                var datosCaso = JSON.parse(this.responseText);

                // Llenar los campos de texto con los datos del caso
                document.getElementsByName("imputado")[0].value = datosCaso.imputado;
                document.getElementsByName("victima")[0].value = datosCaso.victima;
                document.getElementsByName("delito")[0].value = datosCaso.tipo_delito;
            }
        };
        xhr.open("GET", "obtener_datos_caso.php?caso=" + casoSeleccionado, true);
        xhr.send();
    }

    // Adjuntar la función cargarDatosCaso al evento de cambio del menú desplegable
    document.getElementsByName("caso")[0].addEventListener("change", cargarDatosCaso);
</script>
</body>
</html>


