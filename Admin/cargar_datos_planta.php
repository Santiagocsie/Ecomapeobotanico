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

    // Verificar si se encontraron datos de la planta
    if ($result_planta->num_rows > 0) {
        $planta = $result_planta->fetch_assoc();

        // Consulta SQL para obtener los datos de la taxonomía
        $sql_taxonomia = "SELECT * FROM TAXONOMIA WHERE FKID_Planta = $idPlanta";
        $result_taxonomia = $conn->query($sql_taxonomia);
        $taxonomia = $result_taxonomia->fetch_assoc();

        // Consulta SQL para obtener los datos del jardín botánico
        $sql_jardin_botanico = "SELECT * FROM JARDIN_BOTANICO WHERE ID_JBot = $idPlanta";
        $result_jardin_botanico = $conn->query($sql_jardin_botanico);
        $jardin_botanico = $result_jardin_botanico->fetch_assoc();

        // Consulta SQL para obtener los datos de la zona de reserva
        $sql_zona_reserva = "SELECT * FROM ZONA_RESERVA WHERE ID_ZRes = $idPlanta";
        $result_zona_reserva = $conn->query($sql_zona_reserva);
        $zona_reserva = $result_zona_reserva->fetch_assoc();

        // Puedes continuar con el resto de consultas para obtener los demás datos

        $sql_plagas = "SELECT * FROM PLAGAS WHERE ID_PLAG = $idPlanta";
        $result_plagas = $conn->query($sql_plagas);
        $plagas = $result_plagas->fetch_assoc();

        $sql_clima= "SELECT * FROM CLIMA WHERE ID_Clim = $idPlanta";
        $result_clima = $conn->query($sql_clima);
        $clima = $result_clima->fetch_assoc();

        $sql_pin = "SELECT * FROM PINES WHERE ID_Pin = $idPlanta";
        $result_pin = $conn->query($sql_pin);
        $pin = $result_pin->fetch_assoc();

        $sql_flora = "SELECT * FROM FLORACION WHERE ID_Flora = $idPlanta";
        $result_flora = $conn->query($sql_flora);
        $flora = $result_flora->fetch_assoc();
        
        $sql_cuida = "SELECT * FROM CUIDADOS WHERE ID_cuida = $idPlanta";
        $result_cuida = $conn->query($sql_cuida);
        $cuida = $result_cuida->fetch_assoc();

        $sql_loc = "SELECT * FROM LOCALIZACION WHERE ID_LOCALI = $idPlanta";
        $result_loc = $conn->query($sql_loc);
        $loc = $result_loc->fetch_assoc();

        $sql_suelo = "SELECT * FROM SUELO WHERE ID_Suelo = $idPlanta";
        $result_suelo = $conn->query($sql_suelo);
        $suelo = $result_suelo->fetch_assoc();
        $conn->close();

        // Crear un array asociativo con todos los datos
        $datos_planta = array_merge($planta, $taxonomia, $jardin_botanico, $zona_reserva,$loc, $plagas,$clima,$cuida,$suelo,$pin,$flora);

        // Convertir el array a formato JSON y mostrarlo
        header('Content-Type: application/json');
        echo json_encode($datos_planta);
    } else {
        echo json_encode(array('error' => 'No se ha proporcionado un ID de planta.'));
    }
} else {
    echo json_encode(array('error' => 'No se ha proporcionado un ID de planta.'));
}
?>


