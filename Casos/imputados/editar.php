<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: ./Iniciar_Sesion.php");
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


<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";

// Clave y método de cifrado
$encryption_key = 'LegalCC'; // Debe coincidir con la clave utilizada para el cifrado
$ciphering = "AES-128-CTR";
$options = 0;
$encryption_iv = '1234567891011121'; // Debe coincidir con el IV utilizado para el cifrado

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del registro a editar
$id = $_GET['id'];

// Consultar los datos del registro por ID
$sql = "SELECT * FROM imputados WHERE id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Desencriptar los datos para mostrarlos en el formulario
$apellido = openssl_decrypt($row['apellido'], $ciphering, $encryption_key, $options, $encryption_iv);
$nombre = openssl_decrypt($row['nombre'], $ciphering, $encryption_key, $options, $encryption_iv);
$fecha_nacimiento = $row['fecha_nacimiento'];
$dui = openssl_decrypt($row['dui'], $ciphering, $encryption_key, $options, $encryption_iv);
$departamento = openssl_decrypt($row['departamento'], $ciphering, $encryption_key, $options, $encryption_iv);
$distrito = openssl_decrypt($row['distrito'], $ciphering, $encryption_key, $options, $encryption_iv);
$direccion = openssl_decrypt($row['direccion'], $ciphering, $encryption_key, $options, $encryption_iv);
$madre = openssl_decrypt($row['madre'], $ciphering, $encryption_key, $options, $encryption_iv);
$padre = openssl_decrypt($row['padre'], $ciphering, $encryption_key, $options, $encryption_iv);
$pandilla = openssl_decrypt($row['pandilla'], $ciphering, $encryption_key, $options, $encryption_iv);
$alias = openssl_decrypt($row['alias'], $ciphering, $encryption_key, $options, $encryption_iv);
$sexo = openssl_decrypt($row['sexo'], $ciphering, $encryption_key, $options, $encryption_iv);


?>



<?php
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .form-container {
            background-color: white;
            padding: 20px;
            margin: 30px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        h3 {
            text-align: left;
            color: #374D63;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"], button {
            padding: 10px;
            background-color: #374D63;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #2F455C;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 15px;
            }

            input[type="text"] {
                font-size: 14px;
            }

            input[type="submit"] {
                font-size: 14px;
            }
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
            background-color: #007bff;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 95%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus {
           
            border-color: #66afe9;
            outline: none;
            box-shadow: 0px 0px 5px rgba(102, 175, 233, 0.5);
        }

        textarea {
            height: 100px;
            resize: none;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .button-group button[disabled] {
            background-color: #cccccc;
            cursor: not-allowed;
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



        .btn-navega {
            position: fixed;
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: black;
    background-color: white;
    border: none;
  margin-top: 100px;
 margin-right: 1000px;
 margin-left: 200px;
    border-radius: 5px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.btn-navega:hover {
    background-color: white;
    color: #2c3e50;
    transform: scale(1.05);
}

.btn-navega:active {
    background-color: white;
    color: #2c3e50;
    transform: scale(0.95);
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
                    <li><a href="casos/victima.php">Victimas</a></li>
                    <li><a href="casos/imputado.php">Imputados</a></li>
                    
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






    <a class="btn-navega" href="/Casos/imputados/tabladeimputados.php" title="Volver"><i class="fas fa-arrow-left"></i></a></li>





    <div class="form-container">
        <div class="progress-bar">
            <div class="progress-step"></div>
            <div class="progress-step"></div>
            <div class="progress-step"></div>
            <div class="progress-step"></div>
        </div>

        <h2>Editar Registro</h2>
        <form method="post" action="actualizar.php">
            <!-- Paso 1 -->
            <div class="step active">
                <h3>Información Personal</h3>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo $apellido; ?>" required readonly>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required readonly>
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $fecha_nacimiento; ?>" onchange="verificarCampos();" >
                <span id="error" style="color: red;"></span><br>

                <label for="dui">DUI:</label>
                <input type="text" id="dui" name="dui" value="<?php echo $dui; ?>" pattern="\d{8}-\d{1}" title="El formato debe ser 00000000-0"><br>
        <label for="genero">Género:</label>
<select id="genero" name="genero" required onchange="mostrarCampoOtro()">
    <option value="" disabled selected hidden></option>
    <option value= "<?php echo $sexo; ?>" selected><?php echo $sexo; ?></option>
    <option value="Masculino" <?php if ($sexo === 'Masculino') echo 'selected'; ?>>Masculino</option>
    <option value="Femenino" <?php if ($sexo === 'Femenino') echo 'selected'; ?>>Femenino</option>
    <option value="Otro" <?php if ($sexo === 'Otro') echo 'selected'; ?>>Otro</option>
</select><br>

<div id="campoOtro" style="display:none;">
    <label for="otroGenero">Especifique:</label>
    <input type="text" id="otroGenero" name="otroGenero" value="<?php echo isset($otroGenero) ? htmlspecialchars($otroGenero) : ''; ?>">
</div>

        </div>

            

            <!-- Paso 2 -->
            <div class="step">
                <h3>Direccion</h3>

                
                <label for="departamento">Departamento:</label>
                <input type="text" id="departamento" name="departamento" value="<?php echo $departamento; ?>" >
                <label for="distrito">Distrito:</label>
                <input type="text" id="distrito" name="distrito" value="<?php echo $distrito; ?>" >
                <label for="direccion">Dirección:</label>
                <textarea id="direccion" name="direccion" ><?php echo $direccion; ?></textarea>
                
                
            </div>


            <div class="step">
                <h3>Familia</h3>

                <label for="madre">Madre:</label>
                <input type="text" id="madre" name="madre" value="<?php echo $madre; ?>" >
                <label for="padre">Padre:</label>
                <input type="text" id="padre" name="padre" value="<?php echo $padre; ?>">
            </div>

            <!-- Paso 3 -->
            <div class="step">
                <h3>Estrutura Organizacional</h3>
                <label for="pandilla">Pandilla:</label>
                <input type="text" id="pandilla" name="pandilla" value="<?php echo $pandilla; ?>">
                <label for="alias">Alias:</label>
                <input type="text" id="alias" name="alias" value="<?php echo $alias; ?>">
                
               
              

            </div>

            <div class="button-group">
                <button type="button" id="prevBtn" onclick="changeStep(-1)">Anterior</button>
                <button type="button" id="nextBtn" onclick="changeStep(1)">Siguiente</button>
                <input type="submit" id="submitBtn" value="Registrar" style="display:none;">
            </div>
        </form>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    verificarCampos();
    showStep(0);

    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    const duiInput = document.getElementById('dui');

    // Verifica si el campo DUI tiene un valor al cargar la página
    if (duiInput.value.trim() !== "") {
        duiInput.readOnly = true;
    }

    // Verifica si el campo fecha de nacimiento tiene un valor al cargar la página
    if (fechaNacimientoInput.value) {
        fechaNacimientoInput.readOnly = true;
    }

    // Escucha el cambio en la fecha de nacimiento
    fechaNacimientoInput.addEventListener("change", function() {
        const fechaNacimiento = new Date(this.value);
        const fechaActual = new Date();

        // Calcular la edad
        const edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
        const mes = fechaActual.getMonth() - fechaNacimiento.getMonth();
        const dia = fechaActual.getDate() - fechaNacimiento.getDate();

        if (edad < 12 || (edad === 12 && (mes < 0 || (mes === 0 && dia < 0)))) {
            const errorMsg = document.getElementById("error");
            errorMsg.textContent = "Debes tener al menos 12 años.";
            this.value = ''; // Vaciar el campo si la edad es menor de 12 años

            // Hacer que el mensaje desaparezca después de 5 segundos
            setTimeout(function() {
                errorMsg.textContent = "";
            }, 5000);
        } else {
            document.getElementById("error").textContent = ""; // Limpiar el mensaje de error inmediatamente si es válido
        }
    });
});

function verificarCampos() {
    const fechaNacimiento = new Date(document.getElementById("fecha_nacimiento").value);
    const hoy = new Date();
    const errorElement = document.getElementById("error");
    const duiElement = document.getElementById("dui");

    if (duiElement.value.trim() !== "") {
        duiElement.readOnly = true;
        return;
    }

    if (isNaN(fechaNacimiento.getTime())) {
        errorElement.textContent = "";
        duiElement.readOnly = true;
        return;
    }

    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
    const dia = hoy.getDate() - fechaNacimiento.getDate();

    if (mes < 0 || (mes === 0 && dia < 0)) {
        edad--;
    }

    if (edad >= 18) {
        errorElement.textContent = "";
        duiElement.readOnly = false;
    } else {
        errorElement.textContent = "Debe ser mayor de 18 años para ingresar el DUI.";
        duiElement.readOnly = true;
    }
}

function mostrarCampoOtro() {
    const select = document.getElementById("genero");
    const campoOtro = document.getElementById("campoOtro");
    campoOtro.style.display = select.value === "Otro" ? "block" : "none";
}

let currentStep = 0;

function showStep(step) {
    const steps = document.querySelectorAll(".step");

    steps.forEach((s, index) => {
        s.classList.toggle("active", index === step);
    });

    const progressSteps = document.querySelectorAll(".progress-step");

    progressSteps.forEach((p, index) => {
        p.classList.toggle("active", index <= step);
    });

    document.getElementById("prevBtn").disabled = step === 0;

    if (step === steps.length - 1) {
        document.getElementById("nextBtn").style.display = 'none';
        document.getElementById("submitBtn").style.display = 'inline-block';
    } else {
        document.getElementById("nextBtn").style.display = 'inline-block';
        document.getElementById("submitBtn").style.display = 'none';
    }
}

function changeStep(n) {
    const steps = document.querySelectorAll(".step");

    if (currentStep + n < 0 || currentStep + n >= steps.length) {
        return;
    }

    if (n > 0 && !validateStep()) {
        return;
    }

    currentStep += n;
    showStep(currentStep);
}

function validateStep() {
    const activeStep = document.querySelector(".step.active");
    const inputs = activeStep.querySelectorAll("input");

    for (const input of inputs) {
        if (!input.checkValidity()) {
            input.reportValidity();
            return false;
        }
    }
    return true;
}
</script>

</body>
</html>



