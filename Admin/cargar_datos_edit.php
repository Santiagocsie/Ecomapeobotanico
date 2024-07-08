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
    $sql_planta = "SELECT * FROM PLANTAS WHERE ID_Planta = ?";
    $stmt_planta = $conn->prepare($sql_planta);
    $stmt_planta->bind_param("i", $idPlanta);
    $stmt_planta->execute();
    $result_planta = $stmt_planta->get_result();

    if ($result_planta->num_rows > 0) {
        $planta = $result_planta->fetch_assoc();

        // Consulta SQL para obtener todas las investigaciones relacionadas con la planta
        $sql_investigaciones = "SELECT * FROM INVESTIGACION WHERE FKID_Planta = ?";
        $stmt_investigaciones = $conn->prepare($sql_investigaciones);
        $stmt_investigaciones->bind_param("i", $idPlanta);
        $stmt_investigaciones->execute();
        $result_investigaciones = $stmt_investigaciones->get_result();
        $investigaciones = [];

        // Recorrer los resultados de las investigaciones y agregarlas al array de investigaciones
        while ($row = $result_investigaciones->fetch_assoc()) {
            $investigaciones[] = $row;
        }

        // Agregar las investigaciones al array de datos de la planta
        $planta['investigaciones'] = $investigaciones;

        // Convertir el array a formato JSON y mostrarlo
        header('Content-Type: application/json');
        echo json_encode($planta);
    } else {
        echo json_encode(array('error' => 'No se encontró la planta con el ID proporcionado.'));
    }

    // Cerrar las declaraciones y la conexión
    $stmt_planta->close();
    $stmt_investigaciones->close();
    $conn->close();
} else {
    echo json_encode(array('error' => 'No se ha proporcionado un ID de planta.'));
}
?>

