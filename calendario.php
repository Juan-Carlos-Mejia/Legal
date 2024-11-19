<?php
// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "legalcc");

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Consulta para obtener los eventos
$sql = "SELECT id, titulo as title, fecha as start FROM audiencias";
$result = $mysqli->query($sql);

$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Agregar una URL dinámica para ver la audiencia
        $row['url'] = 'Audiencias/ver_audiencia.php?id=' . $row['id'];
        $events[] = $row;
    }
}

echo json_encode($events);
?>
