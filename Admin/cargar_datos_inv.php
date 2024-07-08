<?php
// Verificar si se ha enviado un ID de planta por GET
if (isset($_GET['id'])) {
    // Obtener el ID de la planta desde la URL
    $idPlanta = $_GET['id'];

    // Crear conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "9@xYwHE@P&9DQ5bS"; // La contraseña de tu base de datos
    $dbname = "prueba"; // Nombre de tu base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Consulta SQL para obtener los datos de la planta
    $sql_planta = "SELECT * FROM PLANTAS WHERE ID_Planta = $idPlanta";
    $result_planta = $conn->query($sql_planta);
    $planta = $result_planta->fetch_assoc();

        // Crear un array asociativo con todos los datos
        $datos_planta = array_merge($planta);

        // Convertir el array a formato JSON y mostrarlo
        header('Content-Type: application/json');
        echo json_encode($datos_planta);
    } else {
        echo json_encode(array('error' => 'No se ha proporcionado un ID de planta.'));
    }
?>
