<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #2c3e50;
            font-family: Arial, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Cambiado para apilar el contenido verticalmente */
            height: 100vh;
        }

        .container {
            font-family: Bahnschrift;
            border-radius: 10px;
            background-color: #ecf0f1;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 80px;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #000;
        }

        .password-container {
            position: relative;
            width: 100%;
            margin-bottom: 10px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: #000;
            box-sizing: border-box;
            font-family: Bahnschrift;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #007bff;
            font-size: 18px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            background-color: #2c3e50;
            color: #fff;
            cursor: pointer;
            font-family: Bahnschrift;
        }

        button:hover {
            background-color: #374D63;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
            font-family: Bahnschrift;
        }

        p {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }

        a {
            color: #1B6DC1;
            text-decoration: none;
            font-family: Bahnschrift;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #374D63;
            transform: scale(1.1);
            text-decoration: underline;
        }

        img {
            transition: transform 0.3s;
        }

        img:hover {
            transform: scale(1.05);
        }

        footer {
          
            color: white;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            position: relative;
            bottom: 0;
        }

        footer p {
            margin: 0;
            color: white;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .toggle-password
        {
            color: black;
        }


    </style>
</head>
<body>

<div class="container">
    <center>
        <img src="recursos/inicio.png" alt="Descripción de la imagen" width="190" height="150">
    </center>
    <form action="Proceso_Sesion.php" method="POST">
        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" required>
        
        <label for="contrasena">Contraseña:</label>
        <div class="password-container">
            <input type="password" id="contrasena" name="contrasena" required>
            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        </div>
        
        <p id="error-msg" class="error"></p>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <center>
        <p>¿No tienes cuenta? <a href="/Registro_usuario.php">Regístrate Ya</a></p>
    </center>
</div>

<footer> 
    <p>Legal Conect © 2024. Todos los Derechos Reservados.</p>
</footer>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('contrasena');

    togglePassword.addEventListener('click', function () {
        // Cambiar el tipo de input de password a text o viceversa
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Cambiar el icono
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');

    if (error) {
        const errorMessage = document.getElementById('error-msg');
        errorMessage.textContent = 'Correo electrónico o contraseña incorrectos.';

        setTimeout(function() {
            errorMessage.textContent = '';
        }, 5000);
    }
</script>

</body>
</html>