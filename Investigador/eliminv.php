<?php
// Líneas para la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verificar si se han enviado datos desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_investigacion']) && isset($_POST['id_planta'])) {

    // Verificar si el investigador ha iniciado sesión
    if (isset($_SESSION['investigador_id'])) {
        $FKID_Investigador = $_SESSION['investigador_id'];
    } else {
        echo json_encode(['error' => 'No se ha iniciado sesión']);
        exit;
    }

    $idInvestigacion = $_POST['id_investigacion'];
    $idPlanta = $_POST['id_planta'];

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
        $stmt = $conn->prepare("DELETE FROM INVESTIGACION WHERE ID_Inv = ? AND FKID_Investigador = ? AND FKID_Planta = ?");
        $stmt->bind_param("iii", $idInvestigacion, $FKID_Investigador, $idPlanta);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $conn->commit();
                echo json_encode(['success' => 'Investigación eliminada exitosamente']);
            } else {
                throw new Exception('No se encontró la investigación o no se realizaron cambios.');
            }
        } else {
            throw new Exception('Error al eliminar la investigación: ' . $stmt->error);
        }

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['error' => 'Error al eliminar la investigación: ' . $e->getMessage()]);
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

