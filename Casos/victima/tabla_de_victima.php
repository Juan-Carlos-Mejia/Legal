<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: /Iniciar_Sesion.php");
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
$dbname = "legalcc"; // Asegúrate de tener esta base de datos creada

// Clave y método de cifrado
$encryption_key = 'LegalCC'; // Debe coincidir con la clave utilizada para el cifrado
$ciphering = "AES-128-CTR"; // Método de cifrado
$iv_length = openssl_cipher_iv_length($ciphering); // Longitud del IV
$options = 0;
$encryption_iv = '1234567891011121'; // Debe coincidir con el IV utilizado para el cifrado

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar los registros
$sql = "SELECT * FROM victimas";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros Imputados</title>

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

        .activo {
            background-color:#374D63;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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


        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            margin-top: 30px;
            max-width: 1200px;
            margin: 10px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #374D63;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #d9534f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }









        /* Estilos adicionales */


        .alert {
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #374D63;
            color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
            z-index: 1000;
            width: 300px;
        }

        
        .hidden {
            display: none;
        }
        .btn-eliminar {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: black;
    margin-top: 5px;
    background-color: white;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.btn-eliminar:hover {
    background-color: white;
    color: red;
    transform: scale(1.05);
}

.btn-eliminar:active {
    background-color: white;
    color: red;
    transform: scale(0.95);
}


.btn-editar {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: black;
    background-color: white;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.btn-editar:hover {
    background-color: white;
    color: green;
    transform: scale(1.05);
}

.btn-editar:active {
    background-color: white;
    color: green;
    transform: scale(0.95);
}




.btn-navega {
    position: fixed;
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: black;
    background-color: white;
    border: 2px solid #2c3e50; /* Borde añadido */
    margin-top: 100px;
   margin-left: 40px;
   
   
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



.message-success {
    position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #374D63;
            color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
            z-index: 1000;
            width: 300px;
}


.message-deleted {
    position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #374D63;
            color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
            z-index: 1000;
            width: 300px;
}


.btn-declarar {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: black;
    margin-top: 5px;
    background-color: white;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.btn-declarar:hover {
    background-color: white;
    color: cadetblue;
    transform: scale(1.05);
}

.btn-declarar:active {
    background-color: white;
    color: cadetblue;
    transform: scale(0.95);
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


    
    </style>
</head>
<body>





<div id="custom-alert" class="alert hidden">
        <p id="alert-message"></p>
    
    </div>

   

    <nav>
        <ul>
            <li><a href="/Pagina_principal.php" >Inicio</a></li>




            <li>
                <a href="" class="activo">Victimas</a>
                <ul>
                    <li><a href="/Casos/agregar_casos.php">Casos</a></li>
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



<?php
    if (isset($_GET['message']) && $_GET['message'] == 'exito') {
    echo '<div class="message-success" id="message"l>Los datos de victima se ha actualizado correctamente.</div>';
}
?>

<?php
    if (isset($_GET['mezage']) && $_GET['mezage'] == 'exito') {
    echo '<div class="message-success" id="message"l>La declaracion de la victima ha sido guardada correctamente.</div>';
}
?>

<a class='btn-navega' href='/Casos/victima/victima.php' title="Añadir"><i class='fas fa-plus'></i> </a>

<a id="botonArribaIzquierda" href="/Casos/victima/tabla_victima_archivada.php">
    <i class="fas fa-archive"></i>
    <span class="tooltiptext">Victimas archivadas</span>
</a>

    <div class="container">
        <h1>Registros de victimas </h1>
        
<?php

if ($result->num_rows > 0) {
    echo "<h1>Registros</h1>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Codigo</th>
                <th>Departamento</th>
                <th>Distrito</th>
               
                
                <th>Genero</th>
                
                <th>Acciones</th>
            </tr>";
    
    // Mostrar cada registro
    while ($row = $result->fetch_assoc()) {
        // Desencriptar los datos
        $id = $row['id'];
        $codigo = $row['codigo'];

        $departamento = openssl_decrypt($row['departamento'], $ciphering, $encryption_key, $options, $encryption_iv);
        $distrito = openssl_decrypt($row['distrito'], $ciphering, $encryption_key, $options, $encryption_iv);
      
        $sexo = openssl_decrypt($row['sexo'], $ciphering, $encryption_key, $options, $encryption_iv);
        
        
       
        
       
        
        // Mostrar los datos desencriptados en la tabla
        echo "<tr>
                <td>$id</td>
                <td>$codigo</td>
                
                <td>$departamento</td>
                <td>$distrito</td>
              
                <td>$sexo</td>
                
                <td><a class='btn-editar' href='editar.php?id=$id'  title='Editar'>      <i class='fas fa-edit'></i></a>

               <a class='btn-eliminar' href='archivar.php?id=$id' onclick='return confirm(\"¿Estás seguro de que deseas Archivar este registro?\");' title='Archivar'><i class='fas fa-archive'></i></a>
                <br>
               <a class='btn-declarar' href='declaracion.php?id=$id'  title='Tomar declaracion'><i class='fas fa-message'></i></a>

                </td>
                
              </tr>";
    }
    echo "</table>";
} else {
    echo "<center>";
    echo "No hay registros encontrados.";
}

$conn->close();
?>
      
      
        
    </div>


   
  
    
</body>



<script>




    // Espera 5 segundos y luego hace que el mensaje desaparezca
    setTimeout(function() {
        var message = document.getElementById("message");
        if (message) {
            message.style.opacity = "0";
        }
    }, 5000);

    // Después de 5.5 segundos (para darle tiempo a desaparecer), redirige a otra página
    setTimeout(function() {
        window.location.href = "/Casos/victima/tabla_de_victima.php"; // Cambia esto por la URL a la que quieras redirigir
    }, 5500);


window.onload = function() {
            // Obtener los parámetros de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const mensaje = urlParams.get('mensaje');

            // Mostrar el mensaje de éxito si el parámetro 'mensaje' es 'exito'
            if (mensaje === 'exito') {
                showAlert('Datos de victima agregados con exito');
            }
        };

        function showAlert(message) {
            const alertBox = document.getElementById('custom-alert');
            const alertMessage = document.getElementById('alert-message');
            alertMessage.textContent = message;
            alertBox.classList.remove('hidden');

            // Cerrar la alerta automáticamente después de 5 segundos
            setTimeout(closeAlert, 2000);
        }

        function closeAlert() {
            const alertBox = document.getElementById('custom-alert');
            alertBox.classList.add('hidden');
            // Opcional: Redirigir a otra página después de cerrar la alerta
            window.location.href = 'tabla_de_victima.php';
        }



</script>
</html>
