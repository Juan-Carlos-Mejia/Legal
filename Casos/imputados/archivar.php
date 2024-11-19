<?php
// Datos de conexión a la base de datos
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

// Obtener el ID del registro a archivar
$id = $_GET['id'];

// Consultar el registro a archivar
$sql = "SELECT * FROM imputados WHERE id='$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Preparar los datos para insertar en la tabla archivados

    $codigo = $row['codigo'];
    $apellido = $row['apellido'];
    $codigo = $row['codigo'];
    $nombre = $row['nombre'];
    $fecha_nacimiento = $row['fecha_nacimiento'];
    $dui = $row['dui'];
    $departamento = $row['departamento'];
    $distrito = $row['distrito'];
    $direccion = $row['direccion'];
    $madre = $row['madre'];
    $padre = $row['padre'];
    $pandilla = $row['pandilla'];
    $alias = $row['alias'];
    $sexo = $row['sexo'];
    $estado = "Condenado";

    // Insertar en la tabla archivados
    $sql_insert = "INSERT INTO imputados_archivados (codigo, apellido, nombre, fecha_nacimiento, dui, departamento, distrito, direccion, madre, padre, pandilla, alias, sexo, estado) VALUES ('$codigo', '$apellido', '$nombre', '$fecha_nacimiento', '$dui', '$departamento', '$distrito', '$direccion', '$madre', '$padre', '$pandilla', '$alias', '$sexo' , '$estado')";

    
    $sql_delete = "DELETE FROM imputados WHERE id='$id'";
    
    if ($conn->query($sql_insert) === TRUE) {
        // Eliminar de la tabla registros
        if ($conn->query($sql_delete) === TRUE) {
            header("Location: tabladeimputados.php?message=deleted");
        } else {
            echo "Error al eliminar el registro: " . $conn->error;
        }
    } else {
        echo "Error al archivar el registro: " . $conn->error;
    }
} else {
    echo "Registro no encontrado.";
}

$conn->close();

exit;

?>

