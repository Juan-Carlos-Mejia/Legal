<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "LegalCC";

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


// Recibir los datos del formulario
$id = $_POST['id'];
$apellido = $conn->real_escape_string(openssl_encrypt($_POST['apellido'], $ciphering, $encryption_key, $options, $encryption_iv));
$nombre = $conn->real_escape_string(openssl_encrypt($_POST['nombre'], $ciphering, $encryption_key, $options, $encryption_iv));
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$dui = $conn->real_escape_string(openssl_encrypt($_POST['dui'], $ciphering, $encryption_key, $options, $encryption_iv));
$departamento = $conn->real_escape_string(openssl_encrypt($_POST['departamento'], $ciphering, $encryption_key, $options, $encryption_iv));
$distrito = $conn->real_escape_string(openssl_encrypt($_POST['distrito'], $ciphering, $encryption_key, $options, $encryption_iv));
$direccion = $conn->real_escape_string(openssl_encrypt($_POST['direccion'], $ciphering, $encryption_key, $options, $encryption_iv));
$madre = $conn->real_escape_string(openssl_encrypt($_POST['madre'], $ciphering, $encryption_key, $options, $encryption_iv));
$padre = $conn->real_escape_string(openssl_encrypt($_POST['padre'], $ciphering, $encryption_key, $options, $encryption_iv));


if ($_POST['genero'] === 'Otro') {
    $sexo = $_POST['otroGenero'];  // Si selecciona "Otro", se guarda el valor del campo adicional
} else {
    $sexo = $_POST['genero'];  // Si no, se guarda la opción seleccionada en el campo 'sexo'
}

$sexo = openssl_encrypt($sexo, $ciphering, $encryption_key, $options, $encryption_iv);



// Actualizar los datos en la base de datos
$sql = "UPDATE victimas SET apellido='$apellido', nombre='$nombre', fecha_nacimiento='$fecha_nacimiento', dui='$dui', departamento='$departamento', distrito='$distrito', direccion='$direccion', madre='$madre', padre='$padre', sexo='$sexo' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    header("Location: /Casos/victima/tabla_de_victima.php?message=exito");
    exit; // Asegúrate de llamar a exit después de redirigir
} else {
    echo "Error al actualizar el registro: " . $conn->error;
}

$conn->close();
?>