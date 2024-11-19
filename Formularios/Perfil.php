<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: Iniciar_Sesion.php");
    exit();
    
}
$usuario_id = $_SESSION['user_id'];

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

// Iniciar sesión (si aún no se ha iniciado)


// Obtener el ID de usuario de la sesión
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

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: Bahnschrift, Arial, sans-serif; /* Agregamos Bahnschrift como primera opción */
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #242975;
    font-family: Bahnschrift, Arial, sans-serif; /* Agregamos Bahnschrift como primera opción */
    font-size: 30px;
}

/* Estilos para la tarjeta de usuario */





    h2{
      color: black;
      font-size: 10px;
      font-family: Bahnschrift;
      font-size: 10px;
    }

    .user-profile {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 300px;
}

/* Header */
.user-profile h2 {
    color: #333;
    font-size: 20px;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

/* User Info */
.user-info p {
    color: #666;
    margin: 10px 0;
}

.user-info p strong {
    color: #333;
}

/* Highlighted Info */
.highlight {
    color: #1877f2;
    font-weight: bold;
    font-size: 1.1em;
}

/* Error Message */
.error-message {
    color: red;
    font-weight: bold;
}

/* Edit Profile Button */
.edit-profile-btn {
    display: inline-block;
    background-color: #1877f2;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    text-align: center;
    margin-top: 20px;
}

.edit-profile-btn:hover {
    background-color: #145dbf;
}


.circle-container {
        width: 90px;
        height: 90px;
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


    .chat {
  display: none;
  border: 2px solid #ccc;
  border-radius: 10px;
  width: 600px;
  height: 490px;
  overflow: hidden;
  position: fixed;
  top: 30px; /* Ajusta este valor según la distancia desde la parte superior que desees */
  right: 10px; /* Ajusta este valor según la distancia desde la derecha que desees */
  cursor: move;
}

#mostrarChat {
      background-color: #242975; /* Color de fondo */
      border: none; /* Sin borde */
      color: white; /* Color del texto */
      padding: 15px 32px; /* Espacio interior */
      text-align: center; /* Alineación del texto */
      text-decoration: none; /* Sin decoración de texto */
      display: inline-block; /* Mostrar como un bloque */
      font-size: 16px; /* Tamaño de fuente */
      margin: 4px 2px; /* Márgenes */
      cursor: pointer; /* Cursor al pasar por encima */
      border-radius: 8px; /* Radio de borde */
    }
    #mostrarChat:hover {
      background-color: cadetblue; /* Cambio de color al pasar el cursor */
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


        #showCalendarButton {
            position: fixed;
            bottom: 20px;
            right: 100px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-decoration: none;
    }
    #showCalendarButton:hover {
      background-color: #0056b3;
    }


    table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
    </style>
</head>
<body>


<nav>
        <ul>
            <li><a href="/Pagina_principal.php" >Inicio</a></li>




            <li>
                <a href="/Casos/Agregar_casos.php">Casos</a>
                <ul>
                    <li><a href="casos/victima/tabla_de_victima.php">Victimas</a></li>
                    <li><a href="casos/imputados/tabladeimputados.php">Imputados</a></li>
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
                <a href="/formularios/Perfil.php" class="active">Perfil</a>
                <ul>
                    <li><a href="?logout">Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>



    <a id="showCalendarButton" href="/Chat/chat.php"><i class="fas fa-message"></i></a>





  <div class="container">
    
<center>
    <div class="user-profile">
    <h2>Datos de Usuario</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php $row = $result->fetch_assoc(); ?>
        <div class="user-info">

       
        <div class="circle-container">

    <img class="circle-image" src="/recursos/profile.png" alt="Tu imagen">

   </div>
            <p class="highlight"><strong></strong> <?php echo $row['nombre']; ?></strong> <?php echo $row['apellido']; ?></p></p>

            <p class="highlight"><strong>Profesión:</strong> <?php echo $row['tipo']; ?></p>
            <p><strong></strong> <?php echo $row['correo']; ?></p>
            <p><strong></strong> <?php echo $row['telefono']; ?></p>
          

    <?php else: ?>
        <p class="error-message">Error: No se encontraron detalles del usuario.</p>
    <?php endif; ?>
    <a href="/Formularios/Editar_Perfil.php" class="edit-profile-btn">Editar Perfil</a>
</div>

</center>

<table>
        <tr>
           
            <th>Correo</th>
            <th>Fecha y Hora</th>
            <th>IP Usuario</th>
            <th>Éxito</th>
        </tr>
        
        <?php
        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "legalcc"; // Nombre de la base de datos

        // Crear la conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Consulta SQL
        $sql = "SELECT id, usuario_id, correo, fecha_hora, ip_usuario, exito FROM historial_sesiones WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Mostrar los datos en la tabla
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                         <td>" . $row["correo"] . "</td>
                        <td>" . $row["fecha_hora"] . "</td>
                        <td>" . $row["ip_usuario"] . "</td>
                        <td>" . $row["exito"] . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay datos disponibles para este usuario</td></tr>";
        }

        // Cerrar la conexión
        $stmt->close();
        $conn->close();
        ?>

     
    </table>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>


document.querySelector('a[href="?logout"]').addEventListener('click', function(event) {
    if (!confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        event.preventDefault(); // Cancelar el evento de clic si el usuario no confirma
    }
});

document.addEventListener("DOMContentLoaded", function() {
  // Obtener referencia al elemento de chat y al botón
  var chat = document.getElementById("draggable");
  var botonMostrar = document.getElementById("mostrarChat");

  // Agregar evento de clic al botón
  botonMostrar.addEventListener("click", function() {
    // Si el chat está oculto, mostrarlo; si no, ocultarlo
    if (chat.style.display === "none") {
      chat.style.display = "block";
    } else {
      chat.style.display = "none";
    }
  });
});

   
</script>

</script>
</body>
</html>


