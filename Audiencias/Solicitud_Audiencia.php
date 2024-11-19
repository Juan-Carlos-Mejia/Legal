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



// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del usuario que inició sesión
$user_id = $_SESSION['user_id'];

// Consultar el nombre del usuario autenticado
$sql_user = "SELECT nombre, apellido FROM usuarios WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$stmt_user->bind_result($nombre_usuario, $apellido_usuario);
$stmt_user->fetch();
$stmt_user->close();

// Obtener el ID del caso (puede provenir de la base de datos o de un parámetro en la URL)



  
$caso_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Consulta SQL
$sql_caso = "SELECT id FROM audiencias WHERE id = ?";
$stmt_caso = $conn->prepare($sql_caso);
$stmt_caso->bind_param("i", $caso_id);
$stmt_caso->execute();
$stmt_caso->bind_result($resultado_id);
$stmt_caso->fetch();
$stmt_caso->close();

   


// Consultar el juez asignado
$sql_juez = "SELECT id, nombre, apellido FROM usuarios WHERE tipo = 'juez' LIMIT 1";
$stmt_juez = $conn->prepare($sql_juez);
$stmt_juez->execute();
$stmt_juez->bind_result($juez_id, $nombre_juez, $apellido_juez);
$stmt_juez->fetch();
$stmt_juez->close();




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

// Cerrar conexión
$conn->close();

// Resto del código aquí (contenido de la página principal)
//___________________________________________HTML Normal_____________________________________________________________________________________
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>

body {
            font-family: 'Bahnschrift', sans-serif;
            background-color: #e8f0fa;
            margin: 0;
            padding: 0;
           
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



    #miFormulario {
    background: #f9f9f9; /* Color de fondo más suave */
    border-radius: 12px; /* Bordes más redondeados */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); /* Sombra más pronunciada */
    max-width: 500px;
    padding: 25px; /* Más espacio interno */
    margin: 50px auto; /* Centrado horizontal */
    width: 90%; /* Ancho adaptable */
}

label {
    font-size: 18px; /* Tamaño de fuente ligeramente mayor */
    font-weight: 500; /* Peso de fuente más ligero */
    margin-bottom: 8px; /* Espacio debajo de las etiquetas */
    display: block;
    color: #333; /* Color de texto más oscuro para mejor legibilidad */
}

input[type="hidden"], input[type="date"], textarea {
    border: 1px solid #ddd; /* Borde más claro */
    border-radius: 6px; /* Bordes más redondeados */
    box-sizing: border-box;
    font-size: 16px;
    padding: 12px; /* Más espacio interno */
    width: 100%;
    background-color: #fff; /* Fondo blanco para campos de entrada */
}

textarea {
    height: 120px; /* Mayor altura para áreas de texto */
    resize: vertical;
}

input[type="submit"] {
    background-color: #007bff;
    border: none;
    border-radius: 6px; /* Bordes más redondeados */
    color: #fff;
    cursor: pointer;
    font-size: 16px;
    padding: 12px; /* Más espacio interno */
    width: 100%;
    transition: background-color 0.3s, transform 0.2s; /* Transición para el hover */
}

input[type="submit"]:hover {
    background-color: #0056b3;
    transform: scale(1.02); /* Efecto de escala al pasar el ratón */
}

/* Negrita para las etiquetas del juez */
label[for="juez"] {
    font-weight: bold;
}

/* Cursiva para el nombre del juez */
label.juez-nombre {
    font-style: italic;
}



.form-section {
    margin-bottom: 25px; /* Mayor espacio entre secciones */
}

.form-section label:last-child {
    margin-bottom: 0;
}




label.strong {
    font-weight: bold;
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



  



  <form id="miFormulario" action="guardar_datos.php" method="POST">


  <div class="form-section">
    <label><strong>ID de audiencia</strong></label>
    <label><em><?php echo htmlspecialchars($resultado_id); ?></em></label>
    <input type="hidden" id="caso_id" name="caso_id" value="<?php echo htmlspecialchars($resultado_id); ?>" >
</div>



        <div class="form-section">
        <label><strong>De:</strong></label>
          <label><em><?php echo $nombre_usuario . ' ' . $apellido_usuario; ?></em></label>
            <input type="hidden" name="usuario_id" value="<?php echo $user_id; ?>">
        </div>

    

        <div class="form-section">
        <label for="juez"><strong>Para:</strong></label>
        
        <label>Juez: <em><?php echo $nombre_juez . ' ' . $apellido_juez; ?></em></label>
        <input type="hidden" name="juez_id" value="<?php echo $juez_id; ?>">

        </div>
        <div class="form-section">
        <label for="razon"><strong>Razón:</strong></label>
            <textarea id="razon" name="razon" required></textarea>
        </div>

      


      
        
        <div class="form-section">
        <label for="fecha"><strong>Fecha:</strong></label>
            <input type="date" id="fecha" name="fecha" required>
        </div>
        
        <input type="submit" value="Enviar Solicitud">
    </form>

<script>


document.addEventListener('DOMContentLoaded', function() {
            const fechaInput = document.getElementById('fecha');

            // Establecer la fecha mínima como hoy
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const minDate = `${yyyy}-${mm}-${dd}`;

            fechaInput.setAttribute('min', minDate);

            // Validar si se selecciona un sábado o domingo
            document.getElementById('fecha').addEventListener('change', function() {
            var fechaInput = this;
            var fechaSeleccionada = new Date(fechaInput.value);
            var diaSemana = fechaSeleccionada.getDay(); // 0 = Domingo, 1 = Lunes, ..., 6 = Sábado

            if (diaSemana === 6 || diaSemana === -1) {
                alert('No se pueden seleccionar los dias domingos.');
                fechaInput.value = ''; // Limpiar el campo de fecha
            }
        });
    });

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
