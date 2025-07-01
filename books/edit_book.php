<?php
// Datos de conexión a la base de datos
$servername = "localhost"; // Nombre del servidor
$username = "usuario"; // Nombre de usuario de la base de datos
$password = "contraseña"; // Contraseña de la base de datos
$database = "nombre_de_la_base_de_datos"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

echo "Conexión exitosa"; // Esto se imprimirá si la conexión fue exitosa

// Aquí puedes ejecutar tus consultas SQL
// Por ejemplo:
$sql = "SELECT * FROM tabla";
$result = $conn->query($sql);

// Cerrar la conexión al finalizar
$conn->close();
?>
