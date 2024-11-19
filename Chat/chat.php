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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat en Tiempo Real</title>
    <link rel="stylesheet" href="chat.css">
</head>
<body>

<nav>
    <ul>
        <li><a href="/Pagina_principal.php">Inicio</a></li>
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

<div class="chat-container">
    <div class="users-list">
        <h3>Usuarios</h3>
        <div id="users"></div>
    </div>

    <div class="chat-window">
        <div id="chat-box" class="chat-box">
            Selecciona un usuario para comenzar el chat.
        </div>
        <div class="input-area">
            <input type="text" id="message" class="input-message" placeholder="Escribe un mensaje..." disabled>
            <button id="sendBtn" disabled onclick="sendMessage()">Enviar</button>
        </div>
    </div>
</div>

<script>



// Variables globales
// Variables globales
let selectedUserId = null;
let selectedUserName = null;
let intervalId = null;
let lastMessage = "";
let firstLoad = true; // Control para la primera carga al seleccionar usuario

// Función para seleccionar un usuario
function selectUser(id, name) {
    selectedUserId = id;
    selectedUserName = name;
    document.getElementById('chat-box').innerHTML = `<p>Estás chateando con <strong>${selectedUserName}</strong></p>`;
    document.getElementById('message').disabled = false;
    document.getElementById('sendBtn').disabled = false;
    firstLoad = true; // Indica que es la primera carga del chat con el nuevo usuario
    loadChat(selectedUserId);

    if (intervalId) {
        clearInterval(intervalId);
    }
    intervalId = setInterval(function() {
        loadChat(selectedUserId);
    }, 2000);
}

// Función para cargar los mensajes del chat
function loadChat(userId) {
    fetch(`obtener_mensajes.php?usuario_id=${userId}`)
        .then(response => response.text())
        .then(data => {
            let chatBox = document.getElementById('chat-box');

            // Actualizar el contenido del chat
            let formattedChat = data.replace(/Ellos:/g, `${selectedUserName}:`);
            chatBox.innerHTML = formattedChat;

            // Si el nuevo contenido es diferente al último mensaje y no es la primera carga
            if (data !== lastMessage && !firstLoad) {
                // Actualizar el último mensaje con el contenido actual
                lastMessage = data;

                // Mostrar notificación si hay un mensaje nuevo
                if (Notification.permission === "granted") {
                    new Notification("Nuevo mensaje de " + selectedUserName, {
                        body: "Tienes un nuevo mensaje en el chat",
                        icon: "/Recursos/inicio.png" // Ruta a un ícono opcional para la notificación
                    });
                }
            }

            // Cambiar el estado de primera carga después de la primera actualización
            firstLoad = false;

            // Mantener el scroll abajo
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => console.log('Error al cargar mensajes:', error));
}


// Función para enviar un mensaje
function sendMessage() {
    const message = document.getElementById('message').value;
    if (selectedUserId && message) {
        const chatBox = document.getElementById('chat-box');
        const sentMessage = document.createElement('div');
        sentMessage.classList.add('message', 'sent');
        sentMessage.textContent = `Tú: ${message}`;
        chatBox.appendChild(sentMessage);

        fetch('enviar_mensaje.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `remitente_id=<?php echo $_SESSION['user_id']; ?>&destinatario_id=${selectedUserId}&mensaje=${message}`
        })
        .then(response => response.text())
        .then(data => console.log(data))
        .catch(error => console.log('Error al enviar mensaje:', error));

        document.getElementById('message').value = '';
        chatBox.scrollTop = chatBox.scrollHeight;
    } else {
        alert('Selecciona un usuario y escribe un mensaje.');
    }
}

// Cargar usuarios cuando la página carga
window.onload = function() {
    fetch('usuarios.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('users').innerHTML = data;
        })
        .catch(error => console.log('Error al cargar usuarios:', error));
};

// Solicitar permiso de notificación cuando se carga la página
window.onload = function() {
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    // Cargar usuarios
    fetch('usuarios.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('users').innerHTML = data;
        })
        .catch(error => console.log('Error al cargar usuarios:', error));
};

</script>

</body>
</html>