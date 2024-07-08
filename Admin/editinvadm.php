<?php
// Lineas para la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se han enviado datos desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_planta']) && isset($_POST['id_investigacion'])) {

    // Obtener los datos del formulario
    $idPlanta = $_POST['id_planta'];
    $idInvestigacion = $_POST['id_investigacion'];

    if (!isset($_POST['autor'], $_POST['titulo'], $_POST['contenido'])) {
        echo json_encode(['error' => 'Datos del formulario incompletos']);
        exit;
    }

    $servername = "localhost";
    $username = "root";
    $password = "9@xYwHE@P&9DQ5bS";
    $dbname = "prueba";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->begin_transaction();

    try {
        $autor = $_POST["autor"];
        $titulo = $_POST["titulo"];
        $contenido = $_POST["contenido"];

        $stmt = $conn->prepare("UPDATE INVESTIGACION SET autor = ?, titulo = ?, contenido = ? WHERE ID_Inv = ? AND FKID_Planta = ?");
        $stmt->bind_param("sssii", $autor, $titulo, $contenido, $idInvestigacion, $idPlanta);

        if ($stmt->execute()) {
            $conn->commit();
            echo json_encode(['success' => 'Investigación editada exitosamente']);
        } else {
            throw new Exception('Error al editar la investigación: ' . $stmt->error);
        }

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['error' => 'Error al editar la investigación: ' . $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();

    // Redireccionar
    header("Location: Admin.html");
    exit;

} else {
    echo json_encode(['error' => 'Datos del formulario no enviados o incompletos']);
}
?>

