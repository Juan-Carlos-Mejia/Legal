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
$sql = "SELECT * FROM victimas WHERE id='$id'";
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
    $sexo = $row['sexo'];
    $estado = "Resuelto";

    // Insertar en la tabla archivados
    $sql_insert = "INSERT INTO victimas_archivados (codigo, apellido, nombre, fecha_nacimiento, dui, departamento, distrito, direccion, madre, padre, sexo, estado) VALUES ('$codigo', '$apellido', '$nombre', '$fecha_nacimiento', '$dui', '$departamento', '$distrito', '$direccion', '$madre', '$padre', '$sexo' ,'$estado')";

    
    $sql_delete = "DELETE FROM victimas WHERE id='$id'";
    
    if ($conn->query($sql_insert) === TRUE) {
        // Eliminar de la tabla registros
        if ($conn->query($sql_delete) === TRUE) {
            header("Location: tabla_de_victima.php?message=deleted");
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