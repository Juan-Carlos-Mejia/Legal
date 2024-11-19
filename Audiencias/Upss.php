<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upps, te has perdido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #333;
        }
        #start-over-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        #start-over-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Upps, te has perdido</h1>
    <p>Parece que te has perdido por aquí. ¡No te preocupes! Siempre puedes empezar de nuevo.</p>
    <button id="start-over-btn">Empecemos de nuevo</button>

    <script>
        // JavaScript para manejar el clic del botón
        document.getElementById('start-over-btn').addEventListener('click', function() {
 
            window.location.href = '/Iniciar_Sesion.php';
            console.log('Empezando de nuevo...');
        });
    </script>
</body>
</html>
