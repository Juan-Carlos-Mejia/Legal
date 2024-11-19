<?php

session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: Upss.php"); 
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
// Conexión a la base de datos
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

$sql = "
    SELECT 
        ar.id, 
        u.nombre AS nombre_usuario, 
        u.apellido AS apellido_usuario, 
        j.nombre AS nombre_juez, 
        j.apellido AS apellido_juez, 
        ar.razon, 
        ar.fecha_sugerida, 
        ar.estado, 
        ar.respuesta,
        ar.fecha_creacion,
        ar.caso_id
       
    FROM 
        solicitudes ar
    JOIN 
        usuarios u ON ar.usuario_id = u.id
    JOIN 
        usuarios j ON ar.juez_id = j.id
";

$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Solicitudes</title>

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
            background-color: #004080;
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
            background-color: #003366;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        /* Estilo del submenú "Cerrar sesión" */
        ul li ul {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #004080;
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
            background-color: #003366;
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
            color: #004080;
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











/* Estilo para la tabla */
table {
    width: calc(70% - 40px); /* Ajusta el ancho para incluir el margen */
    border-collapse: collapse;
    margin: 20px auto; /* Espacio arriba y abajo, y centrado horizontalmente */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra para darle profundidad */
    border-radius: 8px; /* Bordes redondeados */
    overflow: hidden; /* Asegura que los bordes redondeados se apliquen correctamente */
}

/* Estilo para las celdas de la tabla */
th, td {
    border: 1px solid #ddd;
    padding: 12px 15px; /* Mayor padding para mejorar la legibilidad */
    text-align: left;
}

/* Estilo para los encabezados */
th {
    background-color: #f4f4f4; /* Color de fondo más suave */
    color: #333; /* Color del texto */
    font-weight: bold; /* Negrita para los encabezados */
}

/* Estilo para las filas alternas */
tr:nth-child(even) {
    background-color: #f9f9f9; /* Color de fondo alterno para filas pares */
}

/* Estilo para las filas al pasar el mouse por encima */
tr:hover {
    background-color: #f1f1f1; /* Color de fondo al pasar el mouse */
}

.btn-volver {
    font-family: 'Bahnschrift', sans-serif;
    background-color: #4CAF50; /* Color verde */
    color: white; /* Texto blanco */
    padding: 10px 20px; /* Espaciado interno */
    border: none; /* Sin bordes */
    border-radius: 5px; /* Bordes redondeados */
    margin-left: 1000px;
    cursor: pointer; /* Manito al pasar por encima */
    text-transform: uppercase; /* Texto en mayúsculas */
    font-size: 16px; /* Tamaño de la fuente */
    transition: background-color 0.3s ease; /* Animación en el color de fondo */
}

.btn-volver:hover {
    background-color: #45a049; /* Color verde más oscuro al pasar el mouse */
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
    margin-left: 1000px;
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
    border-radius: 4px; /* Bordes redondeados */
}

.btn-regresar:hover {
  
  background-color: #0069d9;
}



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

    </style>
</head>
<body>


<nav>
        <ul>
            <li><a href="/Pagina_principal.php">Inicio</a></li>
            <li><a href="/Casos/Buscar_Casos.php">Casos</a></li>
            <li><a href="/Audiencias/Buscar_Audiencias.php">Audiencias</a></li>
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


  












    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Juez</th>
                <th>Audiencia ID</th>
                <th>Razón</th>
                <th>Fecha Sugerida</th>
                <th>Estado</th>
                <th>Respuesta</th>
                <th>Fecha de Solicitud</th>

                <?php
                if ($tipo_usuario === 'juez' ): ?>  
                <th>Acciones</th>
                <?php endif; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Salida de cada fila
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
             
                    echo "<td>" . $row['nombre_usuario'] . " " . $row['apellido_usuario'] . "</td>";
                    echo "<td>" . $row['nombre_juez'] . " " . $row['apellido_juez'] . "</td>";
                    
                    echo "<td><a href='ver_audiencia.php?id=" . $row["caso_id"] . "'>" . $row["caso_id"] . "</a></td>";
                    echo "<td>" . $row["razon"] . "</td>";
                    
                    echo "<td>" . $row["fecha_sugerida"] . "</td>";
                    echo "<td>" . $row["estado"] . "</td>";
                    echo "<td>" . $row["respuesta"] . "</td>";
                    echo "<td>" . $row["fecha_creacion"] . "</td>";
                    
                    if ($tipo_usuario === 'juez' ): ?>   
<td>
    <a class='edit-button' href='responder_solicitud.php?id=<?php echo $row["id"]; ?>' title="Responder solicitud">
        <i class="fas fa-paper-plane"></i>
    </a>
</td>

                        <?php endif; 
                          
                        
       }
   } else {
                echo "<tr><td colspan='7'>No hay solicitudes disponibles</td></tr>";
            }

            
            $conn->close();
            ?>
        </tbody>
    </table>

    <button onclick="goBack()" class="btn-regresar" title="Regresar">
    <i class="fas fa-arrow-left"></i>
</button>


<a href="buscar_audiencias.php" class="btn-regresar" title="Tabla de Audiencias">
    <i class="fas fa-table"></i>
</a>

</body>

<script>
function goBack() {
    window.history.back();
}
  
window.onload = function() {
            // Obtener los parámetros de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const mensaje = urlParams.get('mensaje');

            // Mostrar el mensaje de éxito si el parámetro 'mensaje' es 'exito'
            if (mensaje === 'exito') {
                alert('La solicitud fue respondida con exito');
                // Opcional: Redirigir a otra página después de mostrar el mensaje
                window.location.href = 'ver_solicitudes.php';
            }
        }
  </script>
</html>
