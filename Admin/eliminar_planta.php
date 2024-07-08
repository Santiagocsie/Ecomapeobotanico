<?php
// Verificar si se ha enviado un ID de planta por GET
if (isset($_GET['id'])) {
    // Obtener el ID de la planta desde la URL
    $idPlanta = intval($_GET['id']); // Convertir a entero para mayor seguridad

    // Crear conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "9@xYwHE@P&9DQ5bS"; // La contraseña de tu base de datos
    $dbname = "prueba"; // Nombre de tu base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        echo json_encode(array('error' => 'Error de conexión: ' . $conn->connect_error));
        die(); // Terminar el script
    }

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        $messages = array();

        // Verificar si existe información en la tabla INVESTIGACION
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM INVESTIGACION WHERE FKID_Planta = ?");
        $stmt->bind_param("i", $idPlanta);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            // Si hay información en INVESTIGACION, proceder a eliminarla
            $stmt = $conn->prepare("DELETE FROM INVESTIGACION WHERE FKID_Planta = ?");
            $stmt->bind_param("i", $idPlanta);
            if ($stmt->execute() === TRUE) {
                $messages[] = "Datos eliminados de la tabla Investigación correctamente.";
            } else {
                throw new Exception("Error al eliminar datos de la tabla Investigación: " . $stmt->error);
            }
        } else {
            $messages[] = "No se encontró información en la tabla Investigación.";
        }

        // Definir las consultas DELETE para cada tabla relacionada, excluyendo INVESTIGACION
        $queries = array(
            "DELETE FROM TAXONOMIA WHERE FKID_Planta = ?" => "Taxonomía",
            "DELETE FROM JARDIN_BOTANICO WHERE ID_JBot = ?" => "Jardín Botánico",
            "DELETE FROM PLAGAS WHERE ID_PLAG = ?" => "Plagas",
            "DELETE FROM CLIMA WHERE ID_Clim = ?" => "Clima",
            "DELETE FROM REGION WHERE ID_Reg = ?" => "Región",
            "DELETE FROM PINES WHERE ID_Pin = ?" => "Pines",
            "DELETE FROM FLORACION WHERE ID_Flora = ?" => "Floración",
            "DELETE FROM CUIDADOS WHERE ID_cuida = ?" => "Cuidados",
            "DELETE FROM LOCALIZACION WHERE ID_LOCALI = ?" => "Localización",
            "DELETE FROM SUELO WHERE ID_Suelo = ?" => "Suelo",
            "DELETE FROM IMAGEN WHERE FKID_Planta = ?" => "Imagen",
            "DELETE FROM PLANTAS WHERE ID_Planta = ?" => "Plantas"
        );

        // Ejecutar cada consulta y almacenar mensajes
        foreach ($queries as $query => $tableName) {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $idPlanta);
            if ($stmt->execute() === TRUE) {
                $messages[] = "Datos eliminados de la tabla $tableName correctamente.";
            } else {
                throw new Exception("Error al eliminar datos de la tabla $tableName: " . $stmt->error);
            }
        }

        // Confirmar la transacción
        $conn->commit();

        echo json_encode(array('success' => 'Los datos de la planta y sus relacionados han sido eliminados exitosamente.', 'messages' => $messages));
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(array('error' => 'Error al eliminar la planta: ' . $e->getMessage()));
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo json_encode(array('error' => 'No se ha proporcionado un ID de planta.'));
}
?>

