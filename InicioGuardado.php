<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrando sesión</title>
    <style>
      

        /* Estilos globales para centrar la animación en la página */
/* Estilos globales para centrar el contenido */
body, html {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #2c3e50; /* Fondo claro elegante */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Fuente moderna */
    color: #333; /* Color de texto oscuro */
}

/* Loader: círculo giratorio */
.loader {
    border: 10px solid #e0e0e0; /* Borde gris claro */
    border-top: 10px solid #007bff; /* Borde superior azul */
    border-radius: 50%;
    width: 80px;
    height: 80px;
    animation: spin 1.2s ease-in-out infinite;
    margin-bottom: 20px; /* Separación con el texto */
}

/* Animación de giro */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Estilos para los textos */
h1 {
    font-size: 24px;
    margin-bottom: 10px;
    color: white /* Azul suave */
}

#loading-msg {
    font-size: 18px;
    margin-bottom: 5px;
    color: white;
}

#redirect-msg {
    font-size: 16px;
    color: #666; /* Texto gris oscuro */
    animation: fadeIn 3s ease-in-out infinite alternate;
    color: white;
}

/* Animación de desvanecimiento para el mensaje de redirección */
@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}


    </style>
</head>
<body>

<div class="loader"></div>
    <h1>Iniciando sesión</h1>
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
                window.location.href = '/Pagina_principal.php';
            }, 2000); // 2000 milisegundos = 2 segundos
        }, 2000); // 2000 milisegundos = 2 segundos
    </script>
</body>
</html>