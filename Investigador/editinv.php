<?php
// Líneas para la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verificar si se han enviado datos desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_planta']) && isset($_POST['id_investigacion'])) {

    // Verificar si el investigador ha iniciado sesión
    if (isset($_SESSION['investigador_id'])) {
        $FKID_Investigador = $_SESSION['investigador_id'];
    } else {
        echo json_encode(['error' => 'No se ha iniciado sesión']);
        exit;
    }

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

        $stmt = $conn->prepare("UPDATE INVESTIGACION SET autor = ?, titulo = ?, contenido = ? WHERE ID_Inv = ? AND FKID_Investigador = ? AND FKID_Planta = ?");
        $stmt->bind_param("sssiii", $autor, $titulo, $contenido, $idInvestigacion, $FKID_Investigador, $idPlanta);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $conn->commit();
                echo json_encode(['success' => 'Investigación editada exitosamente']);
            } else {
                throw new Exception('No se encontró la investigación o no se realizaron cambios.');
            }
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
    header("Location: investigador.html");
    exit;

} else {
    echo json_encode(['error' => 'Datos del formulario no enviados o incompletos']);
}
?>

