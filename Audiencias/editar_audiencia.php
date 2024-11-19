<?php
session_start(); 

// Redirigir si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: Upss.php");
    exit();
}

// Manejar cierre de sesión
if (isset($_GET['logout'])) {
    if ($_GET['logout'] == 'confirm') {
        session_destroy();
        header("Location: Iniciar_Sesion.php");
        exit();
    } else {
        header("Location: ?logout=confirm");
        exit();
    }
}

// Función para conectar a la base de datos
function conectarDB() {
    $conn = new mysqli("localhost", "root", "", "legalcc");
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    return $conn;
}

// Si el formulario fue enviado, actualizar la audiencia
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectarDB();
    $id = $_POST["id"];
    $titulo = $_POST["titulo"];
    $caso = $_POST["caso"];
    $modalidad = $_POST["modalidad"];
    $fecha = $_POST["fecha"];
    $hora = $_POST["hora"];
    $imputado = $_POST["imputado"];
    $victima = $_POST["victima"];
    $delito = $_POST["delito"];
    $descripcion = $_POST["descripcion"];
    $juzgado = $_POST["juzgado"];
    $abogado = $_POST["abogado"];
    $fiscal = $_POST["fiscal"];
    $sala = $_POST["sala"];
    $juez_suplente = $_POST["juez_suplente"];
    
    // Actualizar audiencia
    $sql = "UPDATE audiencias SET 
                titulo='$titulo', caso='$caso', modalidad='$modalidad', 
                fecha='$fecha', hora='$hora', imputado='$imputado', 
                victima='$victima', delito='$delito', descripcion='$descripcion', 
                juzgado='$juzgado', abogado='$abogado', fiscal='$fiscal', 
                sala='$sala', juez_suplente='$juez_suplente' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ver_audiencia.php?id=$id");
        exit();
    } else {
        echo "Error al actualizar la audiencia: " . $conn->error;
    }

    $conn->close();


    
}

// Obtener la audiencia a editar
$conn = conectarDB();
$id = $_GET["id"];
$sql_audiencia = "SELECT * FROM audiencias WHERE id = $id";
$result_audiencia = $conn->query($sql_audiencia);

// Mostrar el formulario solo si se encontró la audiencia
if ($result_audiencia->num_rows > 0) {
    $row = $result_audiencia->fetch_assoc();
    $sql_cases = "SELECT referencia FROM casos";
    $result_cases = $conn->query($sql_cases);
    
    $selected_case = $row['caso'];
    $currentPage = htmlspecialchars($_SERVER["PHP_SELF"]);
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalcc";
$user_id = $_SESSION['user_id'];
$sql = "SELECT tipo FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tipo_usuario);
$stmt->fetch();
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Audiencia</title>
</head>
<style>
    body {
            font-family: 'Bahnschrift', sans-serif;
            background-color: #e8f0fa;
            margin: 0;
            padding: 0;
           
            flex-direction: column;
            height: 100vh;
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

        
.container {
    background-color: #fff; /* Usamos notación más corta para colores */
    margin: 100px auto 40px; /* Simplificamos el margen y alineación */
    border-radius: 12px; /* Aumentamos la curvatura para un look más suave */
    padding: 25px 30px; /* Incrementamos el padding para mayor respiro */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Añadimos sombra para mayor contraste */
   max-width: 800px;
    font-family: 'Bahnschrift', Arial, sans-serif;
}

label {
    font-weight: 600; /* Optamos por un peso ligeramente menor para mejor legibilidad */
    display: block;
    margin-bottom: 8px; /* Aumentamos espacio entre etiqueta y campo */
    color: #333; /* Color más suave para las etiquetas */
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 24px; /* Agrandamos el título */
    color: #1E90FF; /* Añadimos color para hacerlo más visible */
}

input[type="text"],
input[type="date"],
input[type="time"],
select,
textarea {
    width: 100%;
    padding: 12px; /* Incrementamos padding para mejorar la experiencia de usuario */
    margin-bottom: 20px; /* Mayor espacio entre campos */
    border: 1px solid #ccc;
    border-radius: 6px; /* Curvatura más suave */
    box-sizing: border-box;
    font-size: 16px; /* Texto más grande para mejor legibilidad */
    transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Añadimos sombra suave en interacción */
}

input[type="text"]:focus,
input[type="date"]:focus,
input[type="time"]:focus,
select:focus,
textarea:focus {
    border-color: #1E90FF;
    box-shadow: 0 0 5px rgba(30, 144, 255, 0.5); /* Sombra suave al enfocar */
    outline: none;
}

input[type="submit"] {
    width: 100%; /* Ajustamos el ancho al 100% */
    background-color: #1E90FF;
    color: #fff;
    padding: 14px; /* Padding más grande para mayor prominencia */
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 18px; /* Botón con tipografía más grande */
    text-align: center;
    transition: background-color 0.3s ease, transform 0.2s ease; /* Añadimos un suave efecto de animación al hover */
}

input[type="submit"]:hover {
    background-color: #242975;
    transform: translateY(-2px); /* Efecto de elevación al hover */
}

@media (max-width: 768px) {
    .container {
        padding: 20px;
        margin: 60px 20px; /* Reducimos márgenes en pantallas pequeñas */
    }

    input[type="submit"] {
        width: 100%; /* Aseguramos que el botón ocupe todo el ancho */
    }
}

    </style>
<body>

<nav>
        <ul>
            <li><a href="/Pagina_principal.php">Inicio</a></li>
            <li>
                <a href="/Casos/Buscar_Casos.php">Casos</a>
                <ul>
                    <li><a href="">Victimas</a></li>
                    <li><a href="">Imputados</a></li>
                    
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








    <center><h1>Reprogramación</h1></center>
    <div class="container">
        <form method="post" action="<?= $currentPage ?>">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <label for="titulo">Título de Audiencia:</label>
                    <input type="text" id="titulo" name="titulo" value="<?= $row['titulo']; ?>">
                </div>
                <div style="width: 48%;">
                    <label for="caso">Seleccionar Caso:</label>
                    <select id="caso" name="caso">
                        <?php
                        while($row_case = $result_cases->fetch_assoc()) {
                            $selected = ($row_case['referencia'] == $selected_case) ? "selected" : "";
                            echo "<option value='{$row_case['referencia']}' $selected>{$row_case['referencia']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" value="<?= $row['fecha']; ?>">
                </div>
                <div style="width: 48%;">
                    <label for="hora">Hora:</label>
                    <input type="time" id="hora" name="hora" value="<?= $row['hora']; ?>">
                </div>
                <div style="width: 48%;">
                    <label for="modalidad">Modalidad de Audiencia:</label>
                    <select id="modalidad" name="modalidad">
                        <option value="Presencial" <?= ($row['modalidad'] == 'Presencial') ? 'selected' : ''; ?>>Presencial</option>
                        <option value="Virtual" <?= ($row['modalidad'] == 'Virtual') ? 'selected' : ''; ?>>Virtual</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <label for="imputado">Imputado:</label>
                    <input type="text" id="imputado" name="imputado" value="<?= $row['imputado']; ?>">
                </div>
                <div style="width: 48%;">
                    <label for="victima">Víctima:</label>
                    <input type="text" id="victima" name="victima" value="<?= $row['victima']; ?>">
                </div>
                <div style="width: 48%;">
                    <label for="delito">Tipo de Delito:</label>
                    <select id="delito" name="delito">
                        <option value="Homicidio" <?= ($row['delito'] == 'Homicidio') ? 'selected' : ''; ?>>Homicidio</option>
                        <option value="Violacion" <?= ($row['delito'] == 'Violacion') ? 'selected' : ''; ?>>Violación</option>
                        <option value="Hurto" <?= ($row['delito'] == 'Hurto') ? 'selected' : ''; ?>>Hurto</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <label for="descripcion">Descripción de la Audiencia:</label>
                    <textarea id="descripcion" name="descripcion"><?= $row['descripcion']; ?></textarea>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <label for="juzgado">Seleccionar Juzgado:</label>
                    <select id="juzgado" name="juzgado">
                        <option value="Juzgado 1" <?= ($row['juzgado'] == 'Juzgado 1') ? 'selected' : ''; ?>>Juzgado 1</option>
                        <option value="Juzgado 2" <?= ($row['juzgado'] == 'Juzgado 2') ? 'selected' : ''; ?>>Juzgado 2</option>
                        <option value="Juzgado 3" <?= ($row['juzgado'] == 'Juzgado 3') ? 'selected' : ''; ?>>Juzgado 3</option>
                    </select>
                </div>
                <div style="width: 48%;">
                    <label for="abogado">Seleccionar Abogado:</label>
                    <select id="abogado" name="abogado">
                        <option value="Abogado 1" <?= ($row['abogado'] == 'Abogado 1') ? 'selected' : ''; ?>>Abogado 1</option>
                        <option value="Abogado 2" <?= ($row['abogado'] == 'Abogado 2') ? 'selected' : ''; ?>>Abogado 2</option>
                        <option value="Abogado 3" <?= ($row['abogado'] == 'Abogado 3') ? 'selected' : ''; ?>>Abogado 3</option>
                    </select>
                </div>
                <div style="width: 48%;">
                    <label for="fiscal">Seleccionar Fiscal:</label>
                    <select id="fiscal" name="fiscal">
                        <option value="Fiscal 1" <?= ($row['fiscal'] == 'Fiscal 1') ? 'selected' : ''; ?>>Fiscal 1</option>
                        <option value="Fiscal 2" <?= ($row['fiscal'] == 'Fiscal 2') ? 'selected' : ''; ?>>Fiscal 2</option>
                        <option value="Fiscal 3" <?= ($row['fiscal'] == 'Fiscal 3') ? 'selected' : ''; ?>>Fiscal 3</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <label for="sala">Seleccionar Sala:</label>
                    <select id="sala" name="sala">
                        <option value="Sala 1" <?= ($row['sala'] == 'Sala 1') ? 'selected' : ''; ?>>Sala 1</option>
                        <option value="Sala 2" <?= ($row['sala'] == 'Sala 2') ? 'selected' : ''; ?>>Sala 2</option>
                        <option value="Sala 3" <?= ($row['sala'] == 'Sala 3') ? 'selected' : ''; ?>>Sala 3</option>
                    </select>
                </div>
                <div style="width: 48%;">     
        <label for="juez_suplente">Juez Suplente:</label><br>
        <select id="juez_suplente" name="juez_suplente">
            <option value="Suplente 1" <?php if ($row['juez_suplente'] == 'Suplente 1') echo 'selected'; ?>>Suplente 1</option>
            <option value="Suplente 2" <?php if ($row['juez_suplente'] == 'Suplente 2') echo 'selected'; ?>>Suplente 2</option>
            <option value="Suplente 3" <?php if ($row['juez_suplente'] == 'Suplente 3') echo 'selected'; ?>>Suplente 3</option>
        </select><br>
        </div>
    </div>
            <input type="submit" value="Guardar Cambios">
        </form>
    </div>
<?php
} else {
    echo "No se encontró la audiencia.";
}


?>
</body>
</html>