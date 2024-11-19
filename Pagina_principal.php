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
    <title>Página Principal</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <head>
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        


        /* Estilo del chat */

        .chat {
  display: none;
  border: 2px solid #ccc;
  border-radius: 10px;
  width: 350px;
  height: 450px;
  overflow: hidden;
  position: fixed;
  margin-top: 50px;
  margin-right: 30px;
  top: 30px; /* Ajusta este valor según la distancia desde la parte superior que desees */
  right: 10px; /* Ajusta este valor según la distancia desde la derecha que desees */
  cursor: move;
}

#mostrarChat {
            position: fixed;
            bottom: 20px;
            right: 20px;
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
    }
    #mostrarChat:hover {
      background-color: #374D63; /* Cambio de color al pasar el cursor */
    }

    #calendar {
    width: 100%; /* Asegura que el calendario ocupe el 100% del contenedor */
    height: 70vh; /* Ajusta la altura del calendario según tus necesidades */
    max-width: 1200px; /* Ancho máximo para el calendario */
    margin: 0 auto; /* Centra el calendario */
    padding: 20px; /* Espacio alrededor del calendario */
    }





 /* Ajuste en el contenedor del calendario */
/* Ajuste en el contenedor del calendario con scroll */
/* Ajuste en el contenedor del calendario con scroll */
#calendar {
  display: none; /* Escondemos el calendario por defecto */
  max-width: 700px; /* Ancho máximo */
  margin: 20px auto;
  background-color: #ffffff;
  padding: 20px;
  border-radius: 12px; /* Bordes redondeados */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
  
  /* Scroll */
  max-height: 500px; /* Altura máxima del calendario */
  overflow-y: auto; /* Añadir scroll vertical si el contenido sobrepasa la altura */
}

/* Estilos para personalizar el scroll en navegadores webkit */
#calendar::-webkit-scrollbar {
  width: 10px; /* Ancho de la barra de desplazamiento */
}

#calendar::-webkit-scrollbar-thumb {
  background: #2c3e50; /* Color de la barra de desplazamiento */
  border-radius: 10px; /* Bordes redondeados */
  box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.3); /* Sombra interna para darle profundidad */
}

#calendar::-webkit-scrollbar-thumb:hover {
  background: #2c3e50; /* Cambiar el color cuando se pase el cursor */
}

#calendar::-webkit-scrollbar-track {
  background: #2c3e50; /* Color del fondo de la barra de desplazamiento */
  border-radius: 10px; /* Bordes redondeados */
}

/* Estilo de scroll en Firefox */
#calendar {
  scrollbar-width: thin; /* Ancho fino */
  scrollbar-color: white #2c3e50;
  border-radius: 20px; /* Color del scroll (barra y fondo) */
}

/* Media queries para hacer que el diseño sea más responsive */
@media (max-width: 768px) {
  #calendar {
    max-width: 100%;
    margin: 10px;
    padding: 10px;
    max-height: 400px; /* Ajustamos la altura máxima en pantallas pequeñas */
  }
}

/* Ajusta los colores según la proximidad de los eventos */
.event-cercano {
  background-color: #FF6347; /* Rojo */
  color: #ffffff;
  border: 1px solid #d9534f;
}

.event-medio {
  background-color: #FFD700; /* Amarillo */
  color: #333333;
  border: 1px solid #ffc107;
}

.event-lejano {
  background-color: #90EE90; /* Verde */
  color: #333333;
  border: 1px solid #28a745;
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
    }
    #showCalendarButton:hover {
      background-color: #0056b3;
    }

/* Estilos para el popup de audiencias cercanas */
.popup {
 margin-top: 55px;
  position: fixed;
  top: 20px;
  margin-left: 400px;
  right: 20px;
  width: 200px;
  padding: 10px;
  background-color: #ffffff;
  border: 1px solid #d9534f;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  z-index: 1000;
}

.popup-content h3 {
  margin: 0 0 10px;
  color: #d9534f;
}

.popup-content p {
  margin: 5px 0;
  color: #333;
}

#closePopup {
  display: inline-block;
  margin-top: 10px;
  padding: 5px 10px;
  background-color: #d9534f;
  color: #ffffff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

#closePopup:hover {
  background-color: #c9302c;
}



    </style>
</head>
<body>




    <nav>
        <ul>
            <li><a href="/Pagina_principal.php" class="active">Inicio</a></li>




            <li>
                <a href="/Casos/Agregar_casos.php">Casos</a>
                <ul>
                    <li><a href="casos/victima/tabla_de_victima.php">Victimas</a></li>
                    <li><a href="casos/imputados/tabladeimputados.php">Imputados</a></li>
                    <li><a href="/archivados/casos_archivados.php">Archivados</a></li>
                </ul>
            
            
            </li>
            <li><a href="/Audiencias/Principal_audiencias.php">Audiencias</a></li>
                  <?php if ($tipo_usuario === 'fiscal' || $tipo_usuario === 'abogado'): ?>  

            <li><a href="/Audiencias/ver_solicitudes.php">Mis Solicitudes</a></li>

            <?php endif; ?>

            <?php if ($tipo_usuario === 'juez'): ?>  

            <li><a href="/Audiencias/ver_solicitudes.php">Solicitudes</a></li>

            <?php endif; ?>
            <li><a href="/Formularios/asistencia.php">Asistencia</a></li>
            <li>
                <a href="/formularios/Perfil.php">Perfil</a>
                <ul>
                    <li><a href="?logout">Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>





    <div class="content">
        <h1>Bienvenido a la Página Principal</h1>
        <p>Explora las opciones en el menú para gestionar tus casos, audiencias y notificaciones de forma eficiente. Elige una opción para comenzar.</p>
        <div id='calendar'>
    </div>



    <button id="mostrarChat"><i class="fas fa-robot"></i></button>

  <div id="draggable" class="chat">
    <iframe src="/chatbot.php" width="350" height="450" frameborder="10" scrolling="no" ></iframe>
</div>


<button id="showCalendarButton"><i class="fas fa-calendar"></i></button>

  <div id='calendar'></div>

<script>


document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var showCalendarButton = document.getElementById('showCalendarButton');
      var calendarVisible = false; // Estado para rastrear la visibilidad del calendario


      function showPopup(eventTitle, eventDate) {
    const popup = document.createElement('div');
    popup.className = 'popup';
    popup.innerHTML = `
      <div class="popup-content">
        <h3>Audiencia cercana</h3>
        <p><strong>${eventTitle}</strong></p>
        <p>Fecha: ${eventDate.toLocaleDateString()}</p>
        <button id="closePopup"></button>
      </div>
    `;
    document.body.appendChild(popup);

    // Cerrar el popup al hacer clic en el botón "Cerrar"
    document.getElementById('closePopup').addEventListener('click', function() {
      popup.remove();
    });

    // Desaparecer el popup automáticamente después de 5 segundos
    setTimeout(() => {
      popup.remove();
    }, 5000);
  }




      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es', // Para configurar el calendario en español
        events: function(fetchInfo, successCallback, failureCallback) {
          // Hacer solicitud AJAX para obtener los eventos
          fetch('calendario.php')
        .then(response => response.json())
        .then(data => {
          const today = new Date();

          // Asigna colores según la proximidad de la fecha del evento
          data = data.map(event => {
            const eventDate = new Date(event.start);
            const diffDays = Math.ceil((eventDate - today) / (1000 * 60 * 60 * 24));

            if (diffDays <= 3) {
              event.classNames = ['event-cercano'];
              showPopup(event.title, eventDate);
            } else if (diffDays <= 7) {
              event.classNames = ['event-medio'];
            } else {
              event.classNames = ['event-lejano'];
            }
            return event;
          });

          successCallback(data);
        })
        .catch(error => {
              console.error('Error al cargar los eventos:', error);
              failureCallback(error);
            });
        }
      });

      // Botón para alternar la visibilidad del calendario
      showCalendarButton.addEventListener('click', function() {
        if (calendarVisible) {
          calendarEl.style.display = 'none'; // Ocultar el calendario
           // Cambiar el texto del botón
        } else {
          calendarEl.style.display = 'block'; // Mostrar el calendario
          calendar.render(); // Renderizar el calendario si no se ha hecho ya
           // Cambiar el texto del botón
        }
        calendarVisible = !calendarVisible; // Cambiar el estado
      });
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
