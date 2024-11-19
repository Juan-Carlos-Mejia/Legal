<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: /Iniciar_Sesion.php");
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
$dbname = "legalcc"; // Asegúrate de tener esta base de datos creada

// Clave y método de cifrado
$encryption_key = 'LegalCC'; // Cambia esta clave por una clave segura
$ciphering = "AES-128-CTR"; // Método de cifrado
$iv_length = openssl_cipher_iv_length($ciphering); // Longitud del IV
$options = 0;
$encryption_iv = '1234567891011121'; // Vector de inicialización (IV)

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para generar un código en el formato IMP 000-0000-000
function generarCodigoImputado() {
    $codigo = 'IMP ' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT) . '-' . 
                      str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . '-' . 
                      str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    return $codigo;
}


// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $dui = $_POST['dui'];
    $departamento = $_POST['departamento'];
    $distrito = $_POST['distrito'];
    $direccion = $_POST['direccion'];
    $madre = $_POST['madre'];
    $padre = $_POST['padre'];
    $pandilla = $_POST['pandilla'];
    $alias = $_POST['alias'];

    // Verificar el valor de 'sexo'
    if ($_POST['sexo'] === 'Otro') {
        $sexo = $_POST['otroSexo'];  // Si selecciona "Otro", se guarda el valor del campo adicional
    } else {
        $sexo = $_POST['sexo'];  // Si no, se guarda la opción seleccionada en el campo 'sexo'
    }

    // Encriptar los datos
    $apellido_encrypted = openssl_encrypt($apellido, $ciphering, $encryption_key, $options, $encryption_iv);
    $nombre_encrypted = openssl_encrypt($nombre, $ciphering, $encryption_key, $options, $encryption_iv);
    $dui_encrypted = openssl_encrypt($dui, $ciphering, $encryption_key, $options, $encryption_iv);
    $departamento_encrypted = openssl_encrypt($departamento, $ciphering, $encryption_key, $options, $encryption_iv);
    $distrito_encrypted = openssl_encrypt($distrito, $ciphering, $encryption_key, $options, $encryption_iv);
    $direccion_encrypted = openssl_encrypt($direccion, $ciphering, $encryption_key, $options, $encryption_iv);
    $madre_encrypted = openssl_encrypt($madre, $ciphering, $encryption_key, $options, $encryption_iv);
    $padre_encrypted = openssl_encrypt($padre, $ciphering, $encryption_key, $options, $encryption_iv);
    $pandilla_encrypted = openssl_encrypt($pandilla, $ciphering, $encryption_key, $options, $encryption_iv);
    $alias_encrypted = openssl_encrypt($alias, $ciphering, $encryption_key, $options, $encryption_iv);

    // Encriptar el campo 'sexo'
    $sexo_encrypted = openssl_encrypt($sexo, $ciphering, $encryption_key, $options, $encryption_iv);

    // Generar el código del imputado
    $codigo_imputado = generarCodigoImputado();

    // Preparar y ejecutar la consulta SQL
    $sql = "INSERT INTO imputados (codigo, apellido, nombre, fecha_nacimiento, dui, departamento, distrito, direccion, madre, padre, pandilla, alias, sexo)
            VALUES ('$codigo_imputado', '$apellido_encrypted', '$nombre_encrypted', '$fecha_nacimiento', '$dui_encrypted', '$departamento_encrypted', 
                    '$distrito_encrypted', '$direccion_encrypted', '$madre_encrypted', '$padre_encrypted', '$pandilla_encrypted', '$alias_encrypted', '$sexo_encrypted')";

    if ($conn->query($sql) === TRUE) {
        header("Location: /Casos/imputados/tabladeimputados.php?mensaje=exito");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>

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

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin-top: 30px;
            align-self: center;
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


h2 {
    color: #2c3e50;
            font-size: 12px;
            margin-bottom: 20px;
            letter-spacing: 2px;
    
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

    
    <form action="" method="POST">
    <h2> Registro de Imputado </h2>
        <div class="progress-bar">
            <div class="progress-step"></div>
            <div class="progress-step"></div>
            <div class="progress-step"></div>
            <div class="progress-step"></div>
        </div>

        <!-- Paso 1 -->
        <div class="step active">
            <h1>Datos Generales</h1>

            <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required autocomplete="off"><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required autocomplete="off"><br>

        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" onchange="validarEdad()"><br>
        <span id="error" style="color: red;"></span><br>

        <label for="dui">DUI:</label>
        <input type="text" id="dui" name="dui" pattern="\d{8}-\d{1}" title="El formato debe ser 00000000-0" disabled><br>

        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo" onchange="mostrarCampoOtro()">
            <option value="">Seleccione un sexo</option>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
            <option value="Otro">Otro</option>
        </select><br>

            <div id="campoOtro" style="display:none;">
        <label for="otroSexo">Especifique:</label>
        <input type="text" id="otroSexo" name="otroSexo">
</div>


        </div>

        <!-- Paso 2 -->
        <div class="step">
        <h1>Direccion</h1>
            <label for="departamento">Departamento:</label>
            <select id="departamento" name="departamento" onchange="updateDistricts()" >
            <option value="">Seleccione un departamento</option>
            <option value="San Salvador">San Salvador</option>
            <option value="La Libertad">La Libertad</option>
            <option value="Santa Ana">Santa Ana</option>
            <option value="San Vicente">San Vicente</option>
            <option value="Ahuachapán">Ahuachapán</option>
            <option value="Cabañas">Cabañas</option>
            <option value="Chalatenango">Chalatenango</option>
            <option value="Cuscatlán">Cuscatlán</option>
            <option value="La Paz">La Paz</option>
            <option value="La Unión">La Unión</option>
            <option value="Morazán">Morazán</option>
            <option value="San Miguel">San Miguel</option>
            <option value="Sonsonate">Sonsonate</option>
            <option value="Usulután">Usulután</option>

         
            
            <!-- Agregar los otros 11 departamentos de El Salvador -->
        </select><br>

        <label for="distrito">Municipio:</label>
        <select id="distrito" name="distrito" >
            <option value="">Seleccione un Municipio</option>
        </select><br>


        <label for="direccion">Especificar Dirección:</label>
        <textarea id="direccion" name="direccion" ></textarea>
        </div>


        <div class="step">

        <h1>Familia</h1>
            <label for="madre">Nombre de la Madre:</label>
            <input type="text" id="madre" name="madre"  autocomplete="off">

            <label for="padre">Nombre del Padre:</label>
            <input type="text" id="padre" name="padre" autocomplete="off">

          
        </div>

        <!-- Paso 4 -->
        <div class="step">

        <h1>Estrutura Organizacional</h1>
            <label for="pandilla">Pandilla:</label>
            <input type="text" id="pandilla" name="pandilla" required autocomplete="off">

            <label for="alias">Alias:</label>
            <input type="text" id="alias" name="alias"  autocomplete="off">

            
        </div>

        <div class="button-group">
            <button type="button" id="prevBtn" onclick="changeStep(-1)" disabled>Anterior</button>
            <button type="submit" id="nextBtn" onclick="changeStep(1)">Siguiente</button>
           
        </div>
    </form>

   


    <script>




function mostrarCampoOtro() {
        var select = document.getElementById("sexo");
        var campoOtro = document.getElementById("campoOtro");
        
        if (select.value === "Otro") {
            campoOtro.style.display = "block";
        } else {
            campoOtro.style.display = "none";
        }
    }


function validarEdad() {
            const fechaNacimiento = new Date(document.getElementById("fecha_nacimiento").value);
            const hoy = new Date();
            const errorElement = document.getElementById("error");
            const duiElement = document.getElementById("dui");

            if (isNaN(fechaNacimiento)) {
                errorElement.textContent = "Por favor, ingrese una fecha válida.";
                duiElement.disabled = true;
                return;
            }

            // Cálculo de la edad
            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            const mes = hoy.getMonth() - fechaNacimiento.getMonth();
            const dia = hoy.getDate() - fechaNacimiento.getDate();

            if (mes < 0 || (mes === 0 && dia < 0)) {
                edad--;
            }

            // Validación
            if (edad >= 18) {
                errorElement.textContent = "";
                duiElement.disabled = false;  // Habilitar el campo DUI
            } else {
                errorElement.textContent = "Debe ser mayor de 18 años para ingresar el DUI.";
                duiElement.disabled = true;  // Deshabilitar el campo DUI
            }
        }

document.getElementById("fecha_nacimiento").addEventListener("change", function() {
        const fechaNacimiento = new Date(this.value);
        const fechaActual = new Date();
        
        // Calcular la edad
        const edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
        const mes = fechaActual.getMonth() - fechaNacimiento.getMonth();
        const dia = fechaActual.getDate() - fechaNacimiento.getDate();
        
        if (edad < 12 || (edad === 12 && (mes < 0 || (mes === 0 && dia < 0)))) {
            const errorMsg = document.getElementById("error");
            errorMsg.textContent = "Debes tener al menos 12 años.";
            this.value = ''; // Vaciar el campo si la edad es menor de 15 años
            
            // Hacer que el mensaje desaparezca después de 5 segundos
            setTimeout(function() {
                errorMsg.textContent = "";
            }, 5000);
        } else {
            document.getElementById("error").textContent = ""; // Limpiar el mensaje de error inmediatamente si es válido
        }
    });








        function updateDistricts() {
            const departmentSelect = document.getElementById("departamento");
            const districtSelect = document.getElementById("distrito");

            const districtsByDepartment = {

                "San Salvador": ["San Salvador Norte", "San Salvador Oeste", "San Salvador Este", "San Salvador Sur", "San Salvador Central"],
                "La Libertad": ["La Libertad Norte", "La Libertad Sur", "La Libertad Oeste", "La Libertad Este", "La Libertad Centro", "La Libertad Costa"],
                "Chalatenango": ["Chalatenango Norte", "Chalatenango Sur", "Chalatenango Centro"],
                "Santa Ana": ["Santa Ana Norte", "Santa Ana Centro", "Santa Ana Oeste", "Santa Ana Este"],
                "Sonsonate": ["Sonsonate Norte", "Sonsonate Centro", "Sonsonate Oeste", "Sonsonate Este"],
                "Ahuachapán": ["Ahuachapán Norte", "Ahuachapán Centro", "Ahuachapán Sur"],
                "La Paz": [  "La Paz Oeste", "La Paz Este", "La Paz Centro"],
                "Cuscatlán": ["Cuscatlán Norte", "Cuscatlán Sur"],
                "Cabañas": ["Cabañas Este", "Cabañas Oeste"],
                "San Vicente": ["San Vicente Norte", "San Vicente Sur"],
                "Usulután": ["Usulután Norte", "Usulután Este", "Usulután Oeste"],
                "San Miguel": ["San Miguel Norte", "San Miguel Centro", "San Miguel Oeste"],
                "Morazán": ["Morazán Norte", "Morazán sur"],
              
                "La Unión": ["La Unión Norte", "La Union Sur"],
                
               
                
              
                


            };

            // Obtener el departamento seleccionado
            const selectedDepartment = departmentSelect.value;

            // Limpiar los distritos actuales
            districtSelect.innerHTML = "";

            // Llenar los nuevos distritos
            if (selectedDepartment in districtsByDepartment) {
                districtsByDepartment[selectedDepartment].forEach(function(district) {
                    const option = document.createElement("option");
                    option.value = district;
                    option.text = district;
                    districtSelect.appendChild(option);
                });
            }
        }

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
            document.getElementById("nextBtn").textContent = step === steps.length - 1 ? "Registrar" : "Siguiente";
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
