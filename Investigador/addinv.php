<?php
// Lineas para la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Asegurarse de que la sesión se inicie al principio del script

// Verificar si se han enviado datos desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_planta'])) {

    // Verificar si el investigador ha iniciado sesión
    if (isset($_SESSION['investigador_id'])) {
        $FKID_Investigador = $_SESSION['investigador_id'];
    } else {
        echo json_encode(['error' => 'No se ha iniciado sesión']);
        exit;
    }

    // Obtener el ID de la planta desde el formulario
    $idPlanta = $_POST['id_planta'];

    // Verificar si las claves 'autor', 'titulo' y 'contenido' están definidas en $_POST
    if (!isset($_POST['autor'], $_POST['titulo'], $_POST['contenido'])) {
        echo json_encode(['error' => 'Datos del formulario incompletos']);
        exit;
    }

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

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Preparar los datos para su inserción en la base de datos
        $autor = $_POST["autor"];
        $titulo = $_POST["titulo"];
        $contenido = $_POST["contenido"];

        $stmt = $conn->prepare("INSERT INTO INVESTIGACION (autor, titulo, contenido, FKID_Investigador, FKID_Planta) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $autor, $titulo, $contenido, $FKID_Investigador, $idPlanta);

        if ($stmt->execute()) {
            $conn->commit();
            echo json_encode(['success' => 'Investigación añadida exitosamente']);
        } else {
            throw new Exception('Error al añadir la investigación: ' . $stmt->error);
        }

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(['error' => 'Error al añadir la investigación: ' . $e->getMessage()]);
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();

    // Redireccionar 
    header("Location: investigador.html");
    exit;

} else {
    echo json_encode(['error' => 'Datos del formulario no enviados o incompletos']);
}
?>

