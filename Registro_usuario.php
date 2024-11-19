<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #2c3e50;
            font-family: Bahnschrift;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .box {
            background-color: #ecf0f1;
          
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 50px;
            max-width: 800px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .box h2 {
            margin-top: 0;
            text-align: center;
        }

        .box form {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .box label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .box input,
        .box select {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            width: 100%;
        }

        .box button {
            padding: 10px 0;
            font-size: 16px;
            background-color: #2c3e50;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            width: 100%;
            max-width: 200px;
        }

        .box button:hover {
            background-color: #374D63;
        }

        .boton-registro {
            background-color: #2c3e50;
            color: white;
            padding: 10px 0;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            max-width: 200px;
        }

        .boton-registro:hover {
            background-color: #374D63;
        }

        .barra-negra {
            width: 80%;
            height: 2px;
            background-color:  #1E90FF;
            margin: 10px 0;
        }

        .letras {
            font-size: 12px;
            color: white;
        }

        p {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }

        a {
            color: blue;
            
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
            color: #2c3e50;
        }

        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-width: 300px;
            text-align: center;
        }

        .popup.error {
            background-color: #d9534f;
            color: #fff;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: black;
            font-size: 18px;
        }
        .password-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .password-field {
            position: relative;
            width: 48%;
        }

        img {
            transition: transform 0.3s;
        }

        img:hover {
            transform: scale(1.05);
        }






        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .progress-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .progress-step {
            width: 100%;
            height: 10px;
            background-color: #e0e0e0;
            position: relative;
            margin: 0 5px;
        }

        .progress-step.active {
            background-color: #374D63;
        }


        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Estilos del contenido del pop-up */
        .popup-content {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 80%;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        /* Estilos del botón de cerrar */
        .close-btn {
            cursor: pointer;
            color: #555;
            font-size: 24px;
            position: absolute;
            top: 10px;
            right: 15px;
        }

        /* Estilos del contenido del texto */
        .popup-content h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .popup-content p {
            font-size: 16px;
            line-height: 1.6;
            text-align: justify;
        }

        .popup-content ol, .popup-content ul {
            margin-left: 20px;
        }

        .popup-content li {
            margin-bottom: 10px;
        }



        .checkbox-container {
            display: flex;
            align-items: center;
            font-size: 16px;
            color: #333;
        }

        .checkbox-container input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.2);
            cursor: pointer;
        }



        
    </style>
</head>
<body>
<?php
$error_message = isset($_GET['mensaje']) ? $_GET['mensaje'] : null;
?>

<?php if ($error_message !== null): ?>
    <div class="popup error">
        <p><?php echo $error_message; ?></p>
    </div>
<?php endif; ?>

<div class="box">
    <image src="recursos/inicio.png" alt="Descripción de la imagen" width="190" height="150">

   
    <form action="Proceso_registro.php" method="POST" enctype="multipart/form-data">

<!-- Barra de progreso -->
<div class="progress-bar">
    <div class="progress-step"></div>
    
    <div class="progress-step"></div>
</div>

<!-- Paso 1 -->
<div class="step active">
    <div style="display: flex; justify-content: space-between; width: 95%;">
        <div style="width: 48%;">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div style="width: 48%;">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; width: 95%;">

        <div style="width: 48%;">
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required>
        </div>


        <div style="width: 48%;">
            <label for="tipo">Oficio:</label>
            <select id="tipo" name="tipo" required>
                <option value="" disabled selected hidden></option>
                <option value="abogado">Abogado</option>
                <option value="juez">Juez</option>
                <option value="fiscal">Fiscal</option>
            </select>
        </div>

    </div>


 
</div>





<div class="popup-overlay" id="popupOverlay">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <h2>Términos y Condiciones de LegalConnect</h2>
            <p><strong>Fecha de última actualización:</strong> [Fecha]</p>
            <p>Bienvenido a LegalConnect. Al acceder o utilizar nuestra plataforma, usted acepta cumplir con estos Términos y Condiciones. Si no está de acuerdo, le recomendamos no utilizar nuestros servicios.</p>
            <ol>
                <li><strong>Aceptación de los Términos</strong><br>
                    Al crear una cuenta o usar los servicios de LegalConnect, usted confirma que ha leído, entendido y acepta estos términos.
                </li>
                <li><strong>Descripción del Servicio</strong><br>
                    LegalConnect es una plataforma [breve descripción del servicio, ej., "de consulta y gestión legal para abogados y clientes"]. Nos reservamos el derecho de modificar, suspender o descontinuar el servicio en cualquier momento, con o sin previo aviso.
                </li>
                <li><strong>Registro y Seguridad</strong><br>
                    Usted se compromete a proporcionar información precisa, completa y actualizada durante el proceso de registro y a mantener la seguridad de su cuenta. Es responsable de todas las actividades que ocurran bajo su cuenta.
                </li>
                <li><strong>Uso de la Plataforma</strong><br>
                    <ul>
                        <li><strong>Prohibiciones:</strong> No debe usar LegalConnect para ningún propósito ilegal o no autorizado.</li>
                        <li><strong>Restricciones de Contenido:</strong> Está prohibido subir contenido que sea ofensivo, difamatorio, o que infrinja los derechos de terceros.</li>
                        <li><strong>Cumplimiento Legal:</strong> Usted acepta cumplir con todas las leyes y regulaciones aplicables.</li>
                    </ul>
                </li>
                <li><strong>Propiedad Intelectual</strong><br>
                    LegalConnect y su contenido original, características y funcionalidades son propiedad exclusiva de [Tu Empresa/Nombre de la Organización]. Está prohibida la reproducción, distribución, modificación o cualquier uso no autorizado del contenido.
                </li>
                <li><strong>Privacidad</strong><br>
                    Nos tomamos en serio la privacidad de nuestros usuarios. Por favor, consulte nuestra [Política de Privacidad] para comprender cómo recopilamos, usamos y protegemos su información personal.
                </li>
                <li><strong>Limitación de Responsabilidad</strong><br>
                    LegalConnect no se hace responsable de:
                    <ul>
                        <li>Cualquier daño o pérdida derivada del uso de la plataforma.</li>
                        <li>Interrupciones, errores o falta de disponibilidad del servicio.</li>
                        <li>Contenido o información proporcionada por otros usuarios.</li>
                    </ul>
                </li>
                <li><strong>Modificaciones a los Términos</strong><br>
                    Nos reservamos el derecho de modificar estos términos en cualquier momento. Le notificaremos sobre los cambios significativos. Su uso continuado de la plataforma después de dichos cambios implica su aceptación de los términos modificados.
                </li>
                <li><strong>Ley Aplicable y Jurisdicción</strong><br>
                    Estos términos se rigen por las leyes de [país o jurisdicción]. Cualquier disputa que surja en relación con estos términos estará sujeta a la jurisdicción exclusiva de los tribunales de [ciudad o país].
                </li>
                <li><strong>Contacto</strong><br>
                    Para consultas sobre estos términos y condiciones, comuníquese con nosotros a través de [correo electrónico de contacto].
                </li>
            </ol>
        </div>
    </div>





<!-- Paso 3 -->
<div class="step">
    <label for="correo" style="width: 100%;">Correo Electrónico:</label>
    <input type="email" id="correo" name="correo" required>

    <div class="password-container">
        <div class="password-field">
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required minlength="8" maxlength="20" 
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}" 
                title="Debe contener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial (@, #, $, %, ^, &, +, =)">
            <span class="toggle-password" onclick="togglePasswordVisibility('contrasena', 'togglePassword1')">
                <i class="fas fa-eye" id="togglePassword1"></i>
            </span>
        </div>

        <div class="password-field">
            <label for="confirmar_contrasena">Confirmar Contraseña:</label>
            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
            <span class="toggle-password" onclick="togglePasswordVisibility('confirmar_contrasena', 'togglePassword2')">
                <i class="fas fa-eye" id="togglePassword2"></i>
            </span>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; width: 20%;">
        <div style="width: 60%;">
            <label class="checkbox-container">
                <input type="checkbox" name="accept_terms" value="yes" required>
                <a href="#" onclick="showPopup()"> Acepto los Términos y Condiciones </a>
            </label>
        </div>
    </div>

</div>
<!-- Controles del formulario -->
<div class="button-group">
    <button type="button" id="prevBtn" onclick="changeStep(-1)"><i class="fas fa-arrow-left"></i></button>
    <button type="submit" id="nextBtn" onclick="changeStep(1)"><i class="fas fa-arrow-right"></i></button>
</div>

<p>¿Ya tienes cuenta? <a href="/Iniciar_Sesion.php">Iniciar Sesión</a></p>
<center>
    <p>LegalConnect © 2024. Todos los Derechos Reservados.</p>
</center>
</form>



</div>

   

<script>


function showPopup() {
            document.getElementById("popupOverlay").style.display = "flex";
        }

        function closePopup() {
            document.getElementById("popupOverlay").style.display = "none";
        }
    function togglePasswordVisibility(inputId, iconId) {
        const passwordField = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }

    document.getElementById("tipo").addEventListener("change", function() {
        if (this.value === "") {
            this.setCustomValidity("Por favor, selecciona un oficio válido.");
        } else {
            this.setCustomValidity("");
        }
    });

    document.getElementById("contrasena").addEventListener("input", function() {
        var confirmarContrasenaInput = document.getElementById("confirmar_contrasena");
        if (this.value !== confirmarContrasenaInput.value) {
            confirmarContrasenaInput.setCustomValidity("Las contraseñas no coinciden");
        } else {
            confirmarContrasenaInput.setCustomValidity("");
        }
    });

    document.getElementById("confirmar_contrasena").addEventListener("input", function() {
        var contrasenaInput = document.getElementById("contrasena");
        if (this.value !== contrasenaInput.value) {
            this.setCustomValidity("Las contraseñas no coinciden");
        } else {
            this.setCustomValidity("");
        }
    });
</script>

<script>
        
        let currentStep = 0;

        // Mostrar la siguiente sección
        function showStep(step) {
            const steps = document.querySelectorAll(".step");
            steps.forEach((s, index) => {
                s.classList.toggle("active", index === step);
            });

            const progressSteps = document.querySelectorAll(".progress-step");
            progressSteps.forEach((p, index) => {
                p.classList.toggle("active", index <= step);
            });

            currentStep = step;

            document.getElementById("prevBtn").disabled = step === 0;
            document.getElementById("nextBtn").innerHTML = step === steps.length - 1 ? "<i class='fas fa-check'></i>" : "<i class='fas fa-arrow-right'></i>";

        }

        // Ir al siguiente o anterior paso
        function changeStep(n) {
            const steps = document.querySelectorAll(".step");
            if (currentStep + n < 0 || currentStep + n >= steps.length) {
                return;
            }

            if (n > 0 && !validateStep()) return; // Validar antes de avanzar

            currentStep += n;
            showStep(currentStep);
        }

        // Validar que los campos de cada paso estén completos
        function validateStep() {
            const activeStep = document.querySelector(".step.active");
            const inputs = activeStep.querySelectorAll("input, select, textarea");
            for (const input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
            return true;
        }

        document.addEventListener("DOMContentLoaded", function() {
            showStep(0); // Mostrar el primer paso al cargar la página
        });
    </script>
</body>
</html>
