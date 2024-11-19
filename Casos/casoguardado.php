<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrando sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            color: white;;
            background: #0A3C7A;

        }
        h1 {
            color: white;
        }
        #loading-msg {
            display: block;
        }
        #redirect-msg {
            display: none;
        }


        .error-message {
  position: fixed;
  top: 10px;
  left: 50%;
  transform: translateX(-50%);
  background-color: #f44336;
  color: white;
  padding: 15px 20px;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  z-index: 9999;
  transition: opacity 0.5s ease;
}

.hidden {
  opacity: 0;
  pointer-events: none;
}
    </style>
</head>
<body>
    <h1>Caso almacenado exitosamente</h1>
    <img src="/recursos/preview.gif" alt="GIF" height="200px" width="200px" >
    <p id="loading-msg">Espera un momento...</p>
    <p id="redirect-msg">Redirigiendo...</p>

    <?php
        // Simulación de cierre de sesión
        sleep(2); // Simula un proceso de cierre de sesión que tarda 2 segundos
    ?>


<?php
if(isset($_GET['error'])) {
    echo '<div id="errorMessage" class="error-message">';
    echo "<p>" . htmlspecialchars($_GET['error']) . "</p>";
    echo '</div>';
}
?>
    <script>
        // JavaScript para mostrar el mensaje de redirección después de un tiempo
        setTimeout(function() {
            document.getElementById('loading-msg').style.display = 'none';
            document.getElementById('redirect-msg').style.display = 'block';
            // Redirigir después de 2 segundos
            setTimeout(function() {
                window.location.href = '/Casos/Buscar_Casos.php';
            }, 1000); // 1000 milisegundos = 2 segundos
        }, 1000); // 1000 milisegundos = 2 segundos



        
        document.addEventListener('DOMContentLoaded', function() {
  var errorMessage = document.getElementById('errorMessage');

  // Mostrar mensaje de error
  errorMessage.classList.remove('hidden');

  // Ocultar mensaje después de 5 segundos
  setTimeout(function() {
    errorMessage.classList.add('hidden');
  }, 5000);
});
    </script>
</body>
</html>