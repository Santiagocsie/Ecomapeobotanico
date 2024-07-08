<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "9@xYwHE@P&9DQ5bS"; // La contraseña de tu base de datos
$dbname = "prueba"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para obtener los pines
$sql = "SELECT PINES.ID_Pin, PINES.nombre, PINES.latitud, PINES.longitud, IMAGEN.direccion, LOCALIZACION.Direccion 
        FROM PINES 
        LEFT JOIN IMAGEN ON PINES.ID_Pin = IMAGEN.FKID_Planta
        LEFT JOIN LOCALIZACION ON PINES.ID_Pin = LOCALIZACION.ID_LOCALI";


$result = $conn->query($sql);

$pines = array();

if ($result->num_rows > 0) {
    // Obtener datos de cada fila y agregarlos al array de pines
    while($row = $result->fetch_assoc()) {
        $pines[] = $row;
    }
}

// Convertir el array de pines a formato JSON y devolverlo
echo json_encode($pines);

// Cerrar conexión
$conn->close();
?>

