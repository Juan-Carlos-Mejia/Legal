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
            background: #387DE4;

        }
        h1 {
            color: #fff;
        }
        #loading-msg {
            display: block;
        }
        #redirect-msg {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Registro Completado</h1>
    <img src="recursos/listo.gif" alt="GIF">
    <p id="loading-msg">Espera un momento...</p>
    <p id="redirect-msg">Redirigiendo...</p>

    <?php
        // Simulación de cierre de sesión
        sleep(2); // Simula un proceso de cierre de sesión que tarda 2 segundos
    ?>

    <script>
        // JavaScript para mostrar el mensaje de redirección después de un tiempo
        setTimeout(function() {
            document.getElementById('loading-msg').style.display = 'none';
            document.getElementById('redirect-msg').style.display = 'block';
            // Redirigir después de 2 segundos
            setTimeout(function() {
                window.location.href = '/Iniciar_Sesion.php';
            }, 2000); // 2000 milisegundos = 2 segundos
        }, 2000); // 2000 milisegundos = 2 segundos
    </script>
</body>
</html>