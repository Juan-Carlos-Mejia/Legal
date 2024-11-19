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
//___________________________________________HTML Normal_____________________________________________________________________________________
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor de Documentos DOCX</title>
    <!-- Incluir TinyMCE con tu API -->
    <script src="https://cdn.tiny.cloud/1/3foww2ks0exfu5njoq1sre6v2mxs80meoygho4mfhsijta6k/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- Incluir docx.js -->
    <script src="https://cdn.jsdelivr.net/npm/docx@8.0.0/build/index.js"></script>
    <!-- Incluir Mammoth.js para la conversión de DOCX a HTML -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
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
        nav {
            background-color: #004080;
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
            background-color: #003366;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        /* Estilo del submenú "Cerrar sesión" */
        ul li ul {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #004080;
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
            background-color: #003366;
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
            color: #004080;
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
        #editor-container {
            width: 80%;
            margin: 20px auto;
        }


        .load-docx {
            background-color: #004080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;

        }
        
        .load-docx:hover {
            background-color: #003366;
        }
    </style>
</head>
<body>

<nav>
        <ul>
            <li><a href="/Pagina_principal.php">Inicio</a></li>
            <li><a href="/Casos/Buscar_Casos.php">Casos</a></li>
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

    <div id="editor-container">
        
        <input type="file" id="docx-file" accept=".docx"><br><br>

        <button id="load-docx" >Cargar archivo DOCX</button><br><br>
        <textarea id="editor"></textarea><br><br>
        <button id="save-docx">Guardar como DOCX</button>
    </div>

    <script>
        // Inicializar TinyMCE con tu API
        tinymce.init({
            selector: '#editor',
            height: 500,
            plugins: 'autosave lists table',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table',
            menubar: false
        });

        let loadedFile;

        // Leer el archivo DOCX
        document.getElementById('docx-file').addEventListener('change', function (event) {
            loadedFile = event.target.files[0];
        });

        // Cargar el contenido del archivo DOCX en el editor cuando se haga clic en el botón
        document.getElementById('load-docx').addEventListener('click', function () {
            if (loadedFile) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const arrayBuffer = e.target.result;
                    
                    // Usar Mammoth.js para convertir DOCX a HTML
                    mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
                        .then(function(result) {
                            const htmlContent = result.value; // El contenido convertido a HTML
                            tinymce.get('editor').setContent(htmlContent); // Cargar el HTML en TinyMCE
                        })
                        .catch(function(err) {
                            console.error('Error al convertir el archivo DOCX:', err);
                        });
                };
                reader.readAsArrayBuffer(loadedFile);
            } else {
                alert("Por favor selecciona un archivo DOCX primero.");
            }
        });

        // Guardar el contenido como DOCX
        document.getElementById('save-docx').addEventListener('click', function () {
            const htmlContent = tinymce.get('editor').getContent({ format: 'html' }); // Obtener el contenido como HTML
            
            // Crear el documento DOCX basado en el contenido
            const doc = new docx.Document({
                sections: [{
                    properties: {},
                    children: [
                        new docx.Paragraph({
                            children: [
                                new docx.TextRun({
                                    text: htmlContent.replace(/<\/?[^>]+(>|$)/g, ""), // Remover etiquetas HTML
                                    break: 1 // Agregar un salto de línea
                                })
                            ]
                        })
                    ]
                }]
            });

            // Generar el archivo DOCX y descargarlo
            docx.Packer.toBlob(doc).then(blob => {
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = 'documento.docx';
                document.body.appendChild(a);
                a.style.display = 'none';
                
                // Hacer clic en el enlace para descargar el archivo
                a.click();
                URL.revokeObjectURL(a.href); // Liberar el objeto URL después de la descarga
            }).catch(err => {
                console.error('Error al generar el documento DOCX:', err);
            });
        });
    </script>
</body>
</html>



