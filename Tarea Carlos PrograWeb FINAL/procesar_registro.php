<?php
include_once('conexion.php');
include_once('validar_rut.php');

// Iniciar sesión
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $a_paterno = mysqli_real_escape_string($conexion, $_POST['a_paterno']);
    $a_materno = mysqli_real_escape_string($conexion, $_POST['a_materno']);
    $rut = $_POST['rut'];
    $nacionalidad = mysqli_real_escape_string($conexion, $_POST['nacionalidad']);
    $sexo = mysqli_real_escape_string($conexion, $_POST['sexo']);
    $fecha_nac = mysqli_real_escape_string($conexion, $_POST['fecha_nac']);
    $lugar_nac = mysqli_real_escape_string($conexion, $_POST['lugar_nac']);
    $profesion = mysqli_real_escape_string($conexion, $_POST['profesion']);
    $discapacidad = isset($_POST['discapacidad']) ? 1 : 0;
    $donante = isset($_POST['donante']) ? 1 : 0;

    // Procesar la imagen
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["foto"]["name"]);
    
    // Obtener la extensión del archivo
    $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar la extensión del archivo
    $allowed_extensions = array("jpeg", "jpg", "png");
    if (!in_array($extension, $allowed_extensions)) {
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
          <title>Registro de Personas</title>
        </head>
        <body>
            <div class="container mt-5">
                <h2>Solo se permiten archivos JPEG, JPG o PNG. Intenta nuevamente.</h2>
            </div>
            <div class="container mt-5">
                <a href="index_test.php" class="btn btn-warning">Regresar</a>
            </div>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        </body>
        </html>';
        return;
    }
    
    move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    $foto = mysqli_real_escape_string($conexion, $target_file);

    // Validar RUT
    if (!validarRut($rut)) {
        // RUT inválido
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
          <title>Registro de Personas</title>
        </head>
        <body>
            <div class="container mt-5">
                <h2>El rut ingresado es invalido, intente nuevamente.</h2>
            </div>
            <div class="container mt-5">
                <a href="index_test.php" class="btn btn-warning">Regresar</a>
            </div>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        </body>
        </html>';
        return;
    }

    // Verificar si el RUT ya existe en la base de datos
    $rut_existente_query = "SELECT COUNT(*) as count FROM personas WHERE rut = '$rut'";
    $rut_existente_result = $conexion->query($rut_existente_query);
    $rut_existente_data = $rut_existente_result->fetch_assoc();

    if ($rut_existente_data['count'] > 0) {
        // RUT ya existe en la base de datos
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
          <title>Registro de Personas</title>
        </head>
        <body>
            <div class="container mt-5">
                <h2>Ya existe una persona con este RUT, intente nuevamente.</h2>
            </div>
            <div class="container mt-5">
                <a href="index_test.php" class="btn btn-warning">Regresar</a>
            </div>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        </body>
        </html>';
        return;
    }

    // Insertar datos en la base de datos
    $query = "INSERT INTO personas (nombre, a_paterno, a_materno, rut, nacionalidad, sexo, fecha_nac, lugar_nac, profesion, discapacidad, donante, foto) VALUES ('$nombre', '$a_paterno', '$a_materno', '$rut', '$nacionalidad', '$sexo', '$fecha_nac', '$lugar_nac', '$profesion', '$discapacidad', '$donante', '$foto')";

    if ($conexion->query($query) === TRUE) {
        // Registro exitoso
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
          <title>Registro de Personas</title>
        </head>
        <body>
            <div class="container mt-5">
                <h2>Registro exitoso.</h2>
            </div>
            <div class="container mt-5">
                <a href="index_test.php" class="btn btn-success">Regresar</a>
            </div>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        </body>
        </html>';
    } else {
        // Error al registrar
        echo "Error al registrar: " . $conexion->error;
    }
}

// Cierra la conexión
$conexion->close();
?>
