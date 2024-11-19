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
    <title>LegalConnect - Ayuda</title>
    <link rel="stylesheet" href="/estilos/menu.css">
    <style>
        /* Estilo general */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 1.5rem 2rem;
            text-align: center;
            border-bottom: 2px solid #fff;
        }
        main {
            padding: 2rem;
        }
        h1 {
            font-size: 2.5rem;
            margin: 0;
        }
        h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }
        
        /* Sección de preguntas frecuentes */
        .faq {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }

        .question {
            font-weight: bold;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 1rem;
            background-color: #f1f1f1;
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: background-color 0.3s ease;
        }

        .question:hover {
            background-color: #e0e0e0;
        }

        .answer {
            display: none;
            margin-left: 2rem;
            font-size: 1rem;
            color: #555;
            padding-top: 0.5rem;
            padding-bottom: 1rem;
        }

        /* Indicaciones Textuales */
        .instructions {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-top: 2rem;
        }

        ul {
            list-style-type: disc;
            margin-left: 2rem;
        }

        ul li {
            margin-bottom: 1rem;
            font-size: 1rem;
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
            <li><a href="/Audiencias/Principal_audiencias.php">Audiencias</a></li>
                  <?php if ($tipo_usuario === 'fiscal' || $tipo_usuario === 'abogado'): ?>  

            <li><a href="/Audiencias/ver_solicitudes.php">Mis Solicitudes</a></li>

            <?php endif; ?>

            <?php if ($tipo_usuario === 'juez'): ?>  

            <li><a href="/Audiencias/ver_solicitudes.php">Solicitudes</a></li>

            <?php endif; ?>
            <li><a href="/Formularios/asistencia.php" class="active">Asistencia</a></li>
            <li>
                <a href="/formularios/Perfil.php">Perfil</a>
                <ul>
                    <li><a href="?logout">Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>

<main>
    <section class="faq">
        <h2>Preguntas Frecuentes (FAQ)</h2>
        
        <div class="question" onclick="toggleAnswer(this)">¿Cómo creo una cuenta en LegalConnect?</div>
        <div class="answer">Para crear una cuenta, dirígete a la página de registro y sigue las instrucciones proporcionadas.</div>
        
        <div class="question" onclick="toggleAnswer(this)">¿Cómo recupero mi contraseña?</div>
        <div class="answer">Puedes recuperar tu contraseña haciendo clic en "¿Olvidaste tu contraseña?" en la página de inicio de sesión.</div>
        
        <div class="question" onclick="toggleAnswer(this)">¿Qué hago si mi cuenta está bloqueada?</div>
        <div class="answer">Si tu cuenta está bloqueada, contacta a soporte para recibir ayuda con el desbloqueo.</div>
        
        <div class="question" onclick="toggleAnswer(this)">¿Puedo cambiar mi correo electrónico registrado?</div>
        <div class="answer">Sí, puedes cambiar tu correo electrónico desde la sección "Mi Perfil". Ve a "Editar perfil" y actualiza tu dirección de correo.</div>
        
        <div class="question" onclick="toggleAnswer(this)">¿Cómo puedo eliminar mi cuenta?</div>
        <div class="answer">Para eliminar tu cuenta, ve a "Configuración de cuenta" y selecciona "Eliminar cuenta". Ten en cuenta que esta acción es irreversible.</div>
        
        <div class="question" onclick="toggleAnswer(this)">¿Qué métodos de pago están disponibles?</div>
        <div class="answer">Actualmente, aceptamos pagos con tarjeta de crédito, débito y PayPal.</div>
        
        <div class="question" onclick="toggleAnswer(this)">¿Cómo puedo contactar al soporte técnico?</div>
        <div class="answer">Puedes contactar al soporte desde el formulario de contacto en esta sección o enviando un correo a soporte@legalconnect.com.</div>
        
        <div class="question" onclick="toggleAnswer(this)">¿Qué hago si no recibo correos de LegalConnect?</div>
        <div class="answer">Verifica que los correos no estén en tu carpeta de spam. Si el problema persiste, actualiza tu correo electrónico o contacta al soporte.</div>
        
        <div class="question" onclick="toggleAnswer(this)">¿Cómo puedo obtener un recibo o factura de mi pago?</div>
        <div class="answer">Después de realizar un pago, recibirás un correo con el recibo. También puedes descargarlo desde "Historial de pagos" en tu cuenta.</div>
        
        <div class="question" onclick="toggleAnswer(this)">¿LegalConnect ofrece descuentos o promociones?</div>
        <div class="answer">Sí, ofrecemos descuentos y promociones periódicamente. Revisa la página principal o suscríbete a nuestro boletín para enterarte.</div>
        
        <!-- Añadir más preguntas y respuestas según sea necesario -->
    </section>

    <section class="instructions">
        <h2>Indicaciones Textuales</h2>
        <p>Encuentra a continuación una guía detallada sobre cómo navegar y usar las funciones de LegalConnect:</p>
        
        <ul>
            <li><strong>Registro de cuenta:</strong> Completa el formulario de registro y verifica tu correo electrónico para activar tu cuenta.</li>
            <li><strong>Inicio de sesión:</strong> Ingresa tu correo electrónico y contraseña. Si olvidaste tu contraseña, haz clic en "¿Olvidaste tu contraseña?"</li>
            <li><strong>Editar perfil:</strong> En "Mi Perfil", selecciona "Editar" para actualizar tu información personal, incluyendo correo y número de contacto.</li>
            <li><strong>Subir documentos:</strong> Dirígete a "Mis Documentos" y haz clic en "Subir" para agregar archivos necesarios para tus trámites.</li>
            <li><strong>Configurar notificaciones:</strong> En "Configuración", ajusta tus preferencias de notificación para recibir avisos importantes.</li>
            <li><strong>Revisar el historial de pagos:</strong> Entra a "Historial de pagos" para ver tus transacciones y descargar recibos.</li>
            <li><strong>Cancelar suscripción:</strong> Si decides cancelar tu suscripción, ve a "Configuración de cuenta" y selecciona "Cancelar suscripción".</li>
            <li><strong>Asistencia en línea:</strong> Utiliza el botón de "Ayuda en vivo" para hablar con un representante de soporte en tiempo real.</li>
            <li><strong>Reportar un problema:</strong> Si encuentras algún problema en la plataforma, completa el formulario de "Reportar un problema" en esta sección.</li>
            <li><strong>Ver políticas de privacidad:</strong> Las políticas de privacidad y términos de uso están disponibles en el pie de página de la plataforma.</li>
            <!-- Añadir más indicaciones según sea necesario -->
        </ul>
    </section>
</main>

<script>
    function toggleAnswer(questionElement) {
        const answerElement = questionElement.nextElementSibling;
        answerElement.style.display = (answerElement.style.display === "none" || !answerElement.style.display) ? "block" : "none";
    }
</script>

</body>
</html>

