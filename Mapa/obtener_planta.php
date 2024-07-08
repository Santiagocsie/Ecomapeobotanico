<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "9@xYwHE@P&9DQ5bS"; // Tu contraseña de la base de datos
$dbname = "prueba"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID de la planta desde la solicitud GET
$id = intval($_GET['id']);

// Consulta preparada para obtener todas las investigaciones de la planta
$sql = "SELECT INVESTIGACION.autor, INVESTIGACION.titulo, INVESTIGACION.contenido
        FROM INVESTIGACION
        WHERE INVESTIGACION.FKID_Planta = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // "i" indica que el parámetro es un entero

// Ejecutar la consulta preparada
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

// Obtener el resultado de la consulta
$result = $stmt->get_result();
$investigaciones = array();

// Obtener todas las investigaciones relacionadas con la planta
while ($row = $result->fetch_assoc()) {
    $investigaciones[] = $row;
}

// Consulta preparada para obtener los datos de la planta
$sql_planta = "SELECT TAXONOMIA.*, IMAGEN.direccion, CLIMA.*, REGION.*, SUELO.*, CUIDADOS.*, PLAGAS.*, 
                    LOCALIZACION.*, JARDIN_BOTANICO.*, ZONA_RESERVA.*, FLORACION.*
               FROM TAXONOMIA
               JOIN IMAGEN ON TAXONOMIA.ID_Taxo = IMAGEN.FKID_Planta
               JOIN CLIMA ON TAXONOMIA.ID_Taxo = CLIMA.ID_Clim
               JOIN REGION ON TAXONOMIA.ID_Taxo = REGION.ID_Reg
               JOIN SUELO ON TAXONOMIA.ID_Taxo = SUELO.ID_Suelo
               JOIN CUIDADOS ON TAXONOMIA.ID_Taxo = CUIDADOS.ID_cuida
               JOIN PLAGAS ON TAXONOMIA.ID_Taxo = PLAGAS.ID_PLAG
               JOIN LOCALIZACION ON TAXONOMIA.ID_Taxo = LOCALIZACION.ID_LOCALI
               JOIN JARDIN_BOTANICO ON TAXONOMIA.ID_Taxo = JARDIN_BOTANICO.ID_JBot
               JOIN ZONA_RESERVA ON TAXONOMIA.ID_Taxo = ZONA_RESERVA.ID_ZRes
               JOIN FLORACION ON TAXONOMIA.ID_Taxo = FLORACION.ID_Flora
               WHERE TAXONOMIA.ID_Taxo = ?";
$stmt_planta = $conn->prepare($sql_planta);
$stmt_planta->bind_param("i", $id); // "i" indica que el parámetro es un entero

// Ejecutar la consulta preparada para obtener los datos de la planta
if (!$stmt_planta->execute()) {
    die("Error executing query: " . $stmt_planta->error);
}

// Obtener el resultado de la consulta
$result_planta = $stmt_planta->get_result();

// Obtener los datos de la planta
if ($result_planta->num_rows > 0) {
    // Obtener los datos de la planta y agregarlos al array
    $planta = $result_planta->fetch_assoc();
    // Agregar las investigaciones al array de la planta antes de enviarlo como JSON
    $planta['investigaciones'] = $investigaciones;
} else {
    die("Planta no encontrada.");
}

// Convertir el array de la planta a formato JSON y devolverlo
echo json_encode($planta);

// Cerrar conexiones
$stmt->close();
$stmt_planta->close();
$conn->close();
?>

