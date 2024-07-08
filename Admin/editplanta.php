<?php
// Lineas para la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se han enviado datos desde el formulario
if (isset($_POST['id_planta'])) {
    // Obtener el ID de la planta desde el formulario
    $idPlanta = intval($_POST['id_planta']); // Convertir a entero para mayor seguridad

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

    $conn->begin_transaction();
    try {
        echo "ID de la planta: " . $idPlanta;

        // Preparar los datos para su actualización en la base de datos
        $nombre = $_POST["nombre"];
        $nombre_cientifico = $_POST["nombre_cientifico"];
        $reino = $_POST["reino"];
        $division = $_POST["division"];
        $subdivision = $_POST["SubDivision"];
        $clase = $_POST["clase"];
        $subclase = $_POST["SubClase"];
        $orden = $_POST["orden"];
        $familia = $_POST["familia"];
        $genero = $_POST["genero"];
        $especie = $_POST["especie"];
        $ciudad = $_POST["ciudad"];
        $direccion = $_POST["direccion"];
        $zona_reserva = $_POST["zona_reserva"];
        $ubicacion = $_POST["ubicacion"];
        $jardin_botanico = $_POST["jardin_botanico"];
        $superficie_decimal = $_POST["superficie_decimal"];
        $inicio_flora = $_POST["inicio_flora"];
        $fin_flora = $_POST["fin_flora"];
        $tipo_cuidado = $_POST["tipo_cuidado"];
        $informacion = $_POST["informacion"];
        $tipo_plaga = $_POST["tipo_plaga"];
        $descripcion_plaga = $_POST["descripcion_plaga"];
        $ciudad_clima = $_POST["ciudad_clima"];
        $descripcion_clima = $_POST["descripcion_clima"];
        $temperatura_promedio = $_POST["temperatura_promedio"];
        $humedad_promedio = $_POST["humedad_promedio"];
        $descripcion_suelo = $_POST["descripcion_suelo"];
        $latitud = $_POST["latitud"];
        $longitud = $_POST["longitud"];

        // Función para ejecutar una consulta preparada
        function executeQuery($conn, $sql, $params, $types) {
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Error preparando la consulta: " . $conn->error);
            }
            $stmt->bind_param($types, ...$params);
            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando la consulta: " . $stmt->error);
            }
            $stmt->close();
        }

        // Actualizar datos en la tabla PLANTAS
        $sql_plantas = "UPDATE PLANTAS SET Nombrep = ?, Nombre_cientifico = ? WHERE ID_Planta = ?";
        executeQuery($conn, $sql_plantas, [$nombre, $nombre_cientifico, $idPlanta], 'ssi');

        // Actualizar datos en la tabla TAXONOMIA
        $sql_taxonomia = "UPDATE TAXONOMIA 
                          SET nombre = ?, reino = ?, division = ?, subdivision = ?, clase = ?, subclase = ?, orden = ?, familia = ?, genero = ?, especie = ? 
                          WHERE FKID_Planta = ?";
        executeQuery($conn, $sql_taxonomia, [$nombre_cientifico, $reino, $division, $subdivision, $clase, $subclase, $orden, $familia, $genero, $especie, $idPlanta], 'ssssssssssi');

        // Actualizar datos en la tabla JARDIN_BOTANICO
        $sql_jardin_botanico = "UPDATE JARDIN_BOTANICO 
                                SET nombrep = ?, nombrej = ?, ubicacion = ?, superficie = ? 
                                WHERE ID_JBot = ?";
        executeQuery($conn, $sql_jardin_botanico, [$nombre_cientifico, $jardin_botanico, $ubicacion, $superficie_decimal, $idPlanta], 'sssdi');

        // Actualizar datos en la tabla ZONA_RESERVA
        $sql_zona_reserva = "UPDATE ZONA_RESERVA 
                             SET nombrez = ? 
                             WHERE ID_ZRes = ?";
        executeQuery($conn, $sql_zona_reserva, [$zona_reserva, $idPlanta], 'si');

        // Actualizar datos en la tabla PINES
        $sql_pines = "UPDATE PINES 
                      SET nombre = ?, latitud = ?, longitud = ? 
                      WHERE ID_Pin = ?";
        executeQuery($conn, $sql_pines, [$nombre_cientifico, $latitud, $longitud, $idPlanta], 'sssi');

        // Actualizar datos en la tabla CLIMA
        $sql_clima = "UPDATE CLIMA 
                      SET nombrec = ?, descripcionc = ?, temperatura_promedio = ?, humedad_promedio = ? 
                      WHERE ID_Clim = ?";
        executeQuery($conn, $sql_clima, [$ciudad_clima, $descripcion_clima, $temperatura_promedio, $humedad_promedio, $idPlanta], 'sssdi');

        // Actualizar datos en la tabla SUELO
        $sql_suelo = "UPDATE SUELO 
                      SET Descripcions = ? 
                      WHERE ID_Suelo = ?";
        executeQuery($conn, $sql_suelo, [$descripcion_suelo, $idPlanta], 'si');

        // Actualizar datos en la tabla CUIDADOS
        $sql_cuidados = "UPDATE CUIDADOS 
                         SET tipo_cuidado = ?, INFORMACION = ? 
                         WHERE ID_cuida = ?";
        executeQuery($conn, $sql_cuidados, [$tipo_cuidado, $informacion, $idPlanta], 'ssi');

        // Actualizar datos en la tabla PLAGAS
        $sql_plagas = "UPDATE PLAGAS 
                       SET TIPO = ?, Descripcion = ? 
                       WHERE ID_PLAG = ?";
        executeQuery($conn, $sql_plagas, [$tipo_plaga, $descripcion_plaga, $idPlanta], 'ssi');

        // Actualizar datos en la tabla FLORACION
        $sql_floracion = "UPDATE FLORACION 
                          SET inicio_flora = ?, fin_flora = ? 
                          WHERE ID_Flora = ?";
        executeQuery($conn, $sql_floracion, [$inicio_flora, $fin_flora, $idPlanta], 'ssi');

        // Actualizar datos en la tabla REGION
        $sql_region = "UPDATE REGION 
                       SET Nombre = ?, Ciudad = ? 
                       WHERE ID_Reg = ?";
        executeQuery($conn, $sql_region, [$nombre, $ciudad, $idPlanta], 'ssi');

        // Actualizar datos en la tabla LOCALIZACION
        $sql_localizacion = "UPDATE LOCALIZACION 
                             SET Direccion = ?, Ciudad = ? 
                             WHERE ID_LOCALI = ?";
        executeQuery($conn, $sql_localizacion, [$direccion, $ciudad, $idPlanta], 'ssi');

        // Verificar si se ha proporcionado una nueva imagen
        if (!empty($_FILES["imagen"]["name"])) {
            // Mover la nueva imagen a una ubicación permanente
            $imagen_nombre = $_FILES["imagen"]["name"];
            $imagen_temporal = $_FILES["imagen"]["tmp_name"];
            $directorio_destino = "../Admin/imagen/"; // Ruta relativa al directorio raíz del servidor
            $ruta_imagen = $directorio_destino . $imagen_nombre;

            // Mover la imagen al directorio de destino
            if (move_uploaded_file($imagen_temporal, $ruta_imagen)) {
                // Actualizar la ruta de la imagen en la tabla IMAGEN
                $sql_update_imagen = "UPDATE IMAGEN 
                                      SET direccion = ? 
                                      WHERE FKID_Planta = ?";
                executeQuery($conn, $sql_update_imagen, [$ruta_imagen, $idPlanta], 'si');

                echo "Imagen actualizada correctamente.";
            } else {
                echo "Error al mover la nueva imagen al directorio de destino.";
            }
        }

        $conn->commit();

        echo json_encode(array('success' => 'Los datos de la planta y sus relacionados han sido editados exitosamente.'));
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(array('error' => 'Error al editar la planta: ' . $e->getMessage()));
    }

    // Cerrar conexión
    $conn->close();

    header("Location: Admin.html");
    exit;
}
?>

