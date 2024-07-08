<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "9@xYwHE@P&9DQ5bS";
$dbname = "prueba";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener las plantas
$sql = "SELECT PLANTAS.*, IMAGEN.direccion,LOCALIZACION.Direccion
FROM PLANTAS
JOIN LOCALIZACION ON PLANTAS.ID_Planta  = LOCALIZACION.ID_LOCALI
JOIN IMAGEN ON PLANTAS.ID_Planta = IMAGEN.FKID_Planta";
$result = $conn->query($sql);

// Generar el HTML con los datos de las plantas
$html = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Obtener la ruta de la imagen desde la base de datos
        $imagen = $row["direccion"]; // Ajusta el nombre de la columna según tu esquema de base de datos

        // Construir el HTML con la etiqueta <img>
        $html .= "<li class='planta' id='" . $row["ID_Planta"] . "' style='display: flex; align-items: center; margin-bottom: 10px;'>";
        $html .= "<img src='" . $imagen . "' alt='" . $row["Nombrep"] . "' class='imagen-planta' style='margin-right: 10px;' width='60' height='55'>";
        $html .= "<div style='flex: 1;'>";
        $html .= "<p style='margin: 0; font-weight: bold;'>" . $row["Nombrep"] . "</p>"; // Nombre de la planta
        $html .= "<p style='margin: 0;'>" . $row["Nombre_cientifico"] . "</p>";
        $html .= "<p style='margin: 0;'>" . $row["Direccion"] . "</p>"; // Dirección de la planta
        $html .= "</div>";
        $html .= "</li>";

    }
} else {
    $html = "No se encontraron plantas.";
}
// Cerrar la conexión
$conn->close();

// Devolver el HTML generado
echo $html;
?>



