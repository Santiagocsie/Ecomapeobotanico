<?php
// Lineas para la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se han enviado datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "9@xYwHE@P&9DQ5bS"; // La contraseña de tu base de datos
    $dbname = "prueba"; // Nombre de tu base de datos

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->begin_transaction();

    try {

    // Preparar los datos para su inserción en la base de datos
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
    // Aquí puedes continuar con el resto de datos...
    
       // Preparar la consulta para insertar datos en la tabla PLANTAS
       $sql_plantas = $conn->prepare("INSERT INTO PLANTAS (Nombrep, Nombre_cientifico) VALUES (?, ?)");
       $sql_plantas->bind_param("ss", $nombre, $nombre_cientifico);

       // Asignar valores
       $nombre = $_POST["nombre"];
       $nombre_cientifico = $_POST["nombre_cientifico"];

       // Ejecutar la consulta
       $sql_plantas->execute();

       // Obtener el ID de la planta insertada
       $id_planta = $conn->insert_id;

        // Mover la imagen a una ubicación permanente
    $imagen_nombre = $_FILES["imagen"]["name"];
    $imagen_temporal = $_FILES["imagen"]["tmp_name"];
    $directorio_destino = "../Admin/imagen/"; // Cambiar a tu directorio destino
    $ruta_imagen = $directorio_destino.$imagen_nombre;

    // Mover la imagen al directorio de destino
    if (move_uploaded_file($imagen_temporal, $ruta_imagen)) {
        // Insertar la ruta de la imagen en la tabla IMAGEN
        $sql_insert_imagen = "INSERT INTO IMAGEN (FKID_Planta, direccion)
                                VALUES ('$id_planta', '$ruta_imagen')";

        // Ejecutar la inserción de la imagen
        if ($conn->query($sql_insert_imagen) === TRUE) {
            echo "Datos de planta e imagen insertados correctamente.";
        } else {
            echo "Error al insertar la imagen: " . $conn->error;
        }
    } else {
        echo "Error al mover la imagen al directorio de destino.";
    }

    $sql_taxonomia = $conn->prepare("INSERT INTO TAXONOMIA (nombre, reino, division, subdivision, clase, subclase, orden, familia, genero, especie, FKID_Planta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql_taxonomia->bind_param("ssssssssssi", $nombre_cientifico, $reino, $division, $subdivision, $clase, $subclase, $orden, $familia, $genero, $especie, $id_planta);
    $sql_taxonomia->execute();
    
    // Preparar la consulta para insertar datos en la tabla JARDIN_BOTANICO
    $sql_jardin_botanico = $conn->prepare("INSERT INTO JARDIN_BOTANICO (nombrep, nombrej, ubicacion, superficie, fecha_creacion) VALUES (?, ?, ?, ?, NOW())");
    $sql_jardin_botanico->bind_param("ssdi", $nombre_cientifico, $jardin_botanico, $ubicacion, $superficie_decimal);
    $sql_jardin_botanico->execute();
    
    // Preparar la consulta para insertar datos en la tabla ZONA_RESERVA
    $sql_zona_reserva = $conn->prepare("INSERT INTO ZONA_RESERVA (nombrep, nombrez) VALUES (?, ?)");
    $sql_zona_reserva->bind_param("ss", $nombre_cientifico, $zona_reserva);
    $sql_zona_reserva->execute();
    
    // Preparar la consulta para insertar datos en la tabla PINES
    $sql_pines = $conn->prepare("INSERT INTO PINES (nombre, latitud, longitud) VALUES (?, ?, ?)");
    $sql_pines->bind_param("sdd", $nombre_cientifico, $latitud, $longitud);
    $sql_pines->execute();
    
    // Preparar la consulta para insertar datos en la tabla CLIMA
    $sql_clima = $conn->prepare("INSERT INTO CLIMA (nombrec, descripcionc, temperatura_promedio, humedad_promedio) VALUES (?, ?, ?, ?)");
    $sql_clima->bind_param("ssdd", $ciudad_clima, $descripcion_clima, $temperatura_promedio, $humedad_promedio);
    $sql_clima->execute();
    
    // Preparar la consulta para insertar datos en la tabla SUELO
    $sql_suelo = $conn->prepare("INSERT INTO SUELO (Nombre, Descripcions) VALUES (?, ?)");
    $sql_suelo->bind_param("ss", $nombre_cientifico, $descripcion_suelo);
    $sql_suelo->execute();
    
    // Preparar la consulta para insertar datos en la tabla CUIDADOS
    $sql_cuidados = $conn->prepare("INSERT INTO CUIDADOS (Nombre, tipo_cuidado, INFORMACION) VALUES (?, ?, ?)");
    $sql_cuidados->bind_param("sss", $nombre_cientifico, $tipo_cuidado, $informacion);
    $sql_cuidados->execute();
    
    // Preparar la consulta para insertar datos en la tabla PLAGAS
    $sql_plagas = $conn->prepare("INSERT INTO PLAGAS (Nombre, TIPO, Descripcion) VALUES (?, ?, ?)");
    $sql_plagas->bind_param("sss", $nombre_cientifico, $tipo_plaga, $descripcion_plaga);
    $sql_plagas->execute();
    
    // Preparar la consulta para insertar datos en la tabla FLORACION
    $sql_floracion = $conn->prepare("INSERT INTO FLORACION (nombre, inicio_flora, fin_flora) VALUES (?, ?, ?)");
    $sql_floracion->bind_param("sss", $nombre_cientifico, $inicio_flora, $fin_flora);
    $sql_floracion->execute();
    
    // Preparar la consulta para insertar datos en la tabla REGION
    $sql_region = $conn->prepare("INSERT INTO REGION (Nombre, Ciudad) VALUES (?, ?)");
    $sql_region->bind_param("ss", $nombre_cientifico, $ciudad);
    $sql_region->execute();
    
    // Preparar la consulta para insertar datos en la tabla LOCALIZACION
    $sql_localizacion = $conn->prepare("INSERT INTO LOCALIZACION (Nombre, Direccion, Ciudad) VALUES (?, ?, ?)");
    $sql_localizacion->bind_param("sss", $nombre_cientifico, $direccion, $ciudad);
    $sql_localizacion->execute();
    
        // Mover la imagen a una ubicación permanente
        $imagen_nombre = $_FILES["imagen"]["name"];
        $imagen_temporal = $_FILES["imagen"]["tmp_name"];
        $directorio_destino = "../Admin/imagen/"; // Cambiar a tu directorio destino
        $ruta_imagen = $directorio_destino.$imagen_nombre;
    
        $conn->commit();

        echo json_encode(array('success' => 'Los datos de la planta y sus relacionados han sido eliminados exitosamente.'));
        } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(array('error' => 'Error al insertar la planta: ' . $e->getMessage()));
        }
    
        // Cerrar conexión
        $conn->close();

// Redireccionar a Admin.html
     header("Location: Admin.html");
      exit;
    }
    ?>


