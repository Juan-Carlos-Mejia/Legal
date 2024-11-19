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
<html>
<head>
    <title>Detalles de Audiencia</title>

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

    .user-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 50px;
    margin-bottom: 20px;
    margin-left: 300px;
    font-family: Bahnschrift, Arial, sans-serif; /* Agregamos Bahnschrift como primera opción */
    margin-right: 300px;
    margin-top: 20px;
}


.user-card h2 {
    color: #333;
    font-size: 28px;
    margin-bottom: 15px;
    text-align: center;
}

.user-card p {
    color: #444;
    font-size: 18px;
    margin: 8px 0;
}

.user-card strong {
    font-weight: bold;
}

.user-card .details {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.user-card .details > div {
    width: 48%;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .user-card .details > div {
        width: 100%;
    }
}
/* Estilo para el botón */


/* Estilo para el enlace */
a {
  text-decoration: none; /* Sin decoración de texto */
}

/* Cambio de estilo para el enlace al pasar el ratón por encima */


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
            margin-left: 889px;
            margin-top: 500px;
        }

        #botonArribaIzquierda:hover {
            background-color: #0056b3;
            color: #fff;
        }



        #botonRegresar {
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
            margin-left: 280px;
            margin-top: 500px;
        }

        #botonRegresar:hover {
            background-color: #0056b3;
            color: #fff;
        }






        .btn-regresar {
    background-color: #007bff; /* Cambia el color de fondo */
    color: white; /* Cambia el color del texto */
    border: none; /* Elimina el borde */
    padding: 10px 20px; /* Espaciado interno */
    text-align: center; /* Alinea el texto al centro */
    text-decoration: none; /* Elimina el subrayado */
    display: inline-block; /* Alinea el botón en línea */
    font-size: 16px; /* Tamaño de la fuente */
    margin: 4px 2px; /* Margen */
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
    border-radius: 4px; /* Bordes redondeados */
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

// Obtener el ID de la audiencia de la URL
$id = $_GET["id"];

// Consulta para obtener la información de la audiencia específica
$sql = "SELECT * FROM audiencias WHERE id = $id";
$result = $conn->query($sql);

// Mostrar la información de la audiencia
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();



} else {
    echo "No se encontró la audiencia.";
}



 
// Cerrar la conexión
$conn->close();
?>

<div class="user-card">
<h2>Detalles de Audiencia</h2>
    <div style="display: flex; justify-content: space-between; width: 100%;">
        <div style="width: 48%;">
            <p><strong>Título:</strong> <?php echo $row["titulo"]; ?></p>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; width: 100%;">
        <div style="width: 48%;">
            <p><strong>Caso:</strong> <?php echo $row["caso"]; ?></p>
        </div>
            
    <div style="width: 48%;">   
            <p><strong>Víctima:</strong> <?php echo $row["victima"]; ?></p>
        </div>
    <div style="width: 48%;"> 
            <p><strong>Imputado:</strong> <?php echo $row["imputado"]; ?></p>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; width: 100%;">
        <div style="width: 48%;">
            <p><strong>Descripción:</strong> <?php echo $row["descripcion"]; ?></p>
            </div>
    </div>

    <div style="display: flex; justify-content: space-between; width: 100%;">
        <div style="width: 48%;">
            <p><strong>Delito:</strong> <?php echo $row["delito"]; ?></p>
            </div>
    <div style="width: 48%;">   
            <p><strong>Fecha:</strong> <?php echo $row["fecha"]; ?></p>
            </div>
    <div style="width: 48%;">   
            <p><strong>Hora:</strong> <?php echo $row["hora"]; ?></p>
            </div>
    </div>

    <div style="display: flex; justify-content: space-between; width: 100%;">
        <div style="width: 48%;">    

            <p><strong>Modalidad:</strong> <?php echo $row["modalidad"]; ?></p>
     </div>
    <div style="width: 48%;">       
            <p><strong>Juzgado:</strong> <?php echo $row["juzgado"]; ?></p>
            </div>
    <div style="width: 48%;">
            <p><strong>Abogado:</strong> <?php echo $row["abogado"]; ?></p>
            </div>
    </div>    
    
    <div style="display: flex; justify-content: space-between; width: 100%;">
    <div style="width: 48%;"> 
            <p><strong>Fiscal:</strong> <?php echo $row["fiscal"]; ?></p>
            </div>
    <div style="width: 48%;">
            <p><strong>Juez:</strong> <?php echo $row["juez_suplente"]; ?></p>
            </div>
    <div style="width: 48%;">   
            <p><strong>Sala:</strong> <?php echo $row["sala"]; ?></p>

            </div>
    </div> 
</div>


    <?php


echo '<a id="botonArribaIzquierda" href="editar_audiencia.php?id=' . $id . '">Reprogramar Audiencias</a>';

echo '';

  
    ?>

    


</body>

<script>
function goBack() {
    window.history.back();
}

</script>
</html>
