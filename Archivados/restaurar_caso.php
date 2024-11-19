<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "legalcc";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha recibido la referencia del caso
if (isset($_GET['referencia'])) {
    $referencia = $_GET['referencia'];

    // Obtener los datos del caso archivado desde la tabla 'casos_archivados'
    $sql = "SELECT * FROM casos_archivados WHERE referencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $referencia);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Insertar el caso de vuelta en la tabla 'casos'
        $sql_reinsertar = "INSERT INTO casos (referencia, victima, imputado, tipo_delito, documento, fecha_creacion) 
                           VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_reinsertar = $conn->prepare($sql_reinsertar);
        $stmt_reinsertar->bind_param("ssssss", $row['referencia'], $row['victima'], $row['imputado'], $row['tipo_delito'], $row['documento'], $row['fecha_creacion']);
        $stmt_reinsertar->execute();

        

        
    
        // Eliminar el caso de la tabla 'casos_archivados'
        $sql_eliminar_archivado = "DELETE FROM casos_archivados WHERE referencia = ?";
        $stmt_eliminar_archivado = $conn->prepare($sql_eliminar_archivado);
        $stmt_eliminar_archivado->bind_param("s", $referencia);
        $stmt_eliminar_archivado->execute();

        

        // Redirigir de vuelta a la página de casos archivados
        header("Location: /Archivados/casos_archivados.php");
        exit();
    } else {
        echo "Caso no encontrado.";
    }
}

// Cerrar la conexión
$conn->close();
?>
