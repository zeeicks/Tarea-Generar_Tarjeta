<?php
include_once('conexion.php');
require 'phpqrcode/qrlib.php';

// Obtener ID a generar
$id = $_GET['id'];

// Obtener datos de la base de datos
$query = "SELECT nombre, a_paterno, a_materno, rut, nacionalidad, foto, fecha_nac, lugar_nac, profesion, sexo FROM personas WHERE id = $id"; // Cambia '1' al ID de la persona deseada
$resultado = $conexion->query($query);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();

    // Obtener datos de la base de datos
    $nombre = strtoupper($fila['nombre']);
    $apellido_p = strtoupper($fila['a_paterno']);
    $apellido_m = strtoupper($fila['a_materno']);
    $fecha_nac = $fila['fecha_nac'];
    $fechaFormateada = date("d M Y", strtotime($fecha_nac)); //Modificar el formato de fecha
    $rut = $fila['rut'];
    $lugar_nac = strtoupper($fila['lugar_nac']);
    $profesion = $fila['profesion'];
    $nacionalidad = strtoupper($fila['nacionalidad']);
    $sexo = strtoupper($fila['sexo']);
   
    if($sexo == 'MASCULINO'){
        $sexo = 'M';
    } else{
        $sexo = 'F';
    }


    // Inicializar la imagen BASE
    $imagen = imagecreatefrompng('imagen_base_cedula.png');



    // GENERAR QR
    // Datos para el QR
    $datosQR = 'Nombre(s): '.$nombre.' '.$apellido_m.' '.$apellido_p.'\nRUT: '.$rut.'';

    // Ruta donde se guardará la imagen del código QR
    $rutaQR = 'qrcodes/codigo_qr.png';

    // Genera el código QR
    QRcode::png($datosQR, $rutaQR, QR_ECLEVEL_L, 10, 2);

    // Crear una imagen QR
    $imagenQR = imagecreatefrompng($rutaQR);

    // Obtener las dimensiones de la imagen del QR
    $anchoImagenQR = imagesx($imagenQR);
    $altoImagenQR = imagesy($imagenQR);

    // Definir el nuevo tamaño (ajustar según las necesidades)
    $nuevoAnchoQR = 150; // Ancho deseado
    $nuevoAltoQR = 150;  // Alto deseado

    // Redimensionar el QR
    $imagenQRRedimensionada = imagescale($imagenQR, $nuevoAnchoQR, $nuevoAltoQR);

    // Coordenadas donde superponer la imagen QR (ajusta según el diseño)
    $xImagenQR = 40;
    $yImagenQR = 580;   

    // Superponer la imagen del usuario en la imagen de la plantilla
    imagecopy($imagen, $imagenQRRedimensionada, $xImagenQR, $yImagenQR, 0, 0, $nuevoAnchoQR, $nuevoAltoQR);






    // TEXTO
    $colorTexto = imagecolorallocate($imagen, 30, 30, 30); // Negro

    // Fuente y tamaño del texto
    $fuente = 'MuseoSans_500.otf';  // Cambiar esto a la ruta del archivo de fuente TrueType (TTF)
    $fuenteItalic = 'museosans-900.ttf';
    $fuenteEjemplo = 'Chivo-Regular.ttf';
    $tamanioTexto = 19;
    $tamanioTextoReverso = 16;
    $tamanioRut = 20;
    // Obtener la fecha actual
    $fechaActual = date("d M Y");

    // Calcular la fecha de vencimiento (sumar 5 años a la fecha actual)
    $fechaVencimiento = date("d M Y", strtotime($fechaActual . " +5 years"));

    // Superponer la fecha de emision actual en la imagen
    imagettftext($imagen, $tamanioTexto, 0, 299, 368, $colorTexto, $fuente, $fechaActual);

    // Superponer la fecha de vencimiento actual en la imagen
    imagettftext($imagen, $tamanioTexto, 0, 486, 368, $colorTexto, $fuente, $fechaVencimiento);

    // SUPERPONER LOS TEXTOS:
    // Superponer la nacionalidad en la imagen
    imagettftext($imagen, $tamanioTexto, 0, 299, 265, $colorTexto, $fuente, $nacionalidad);

    // Superponer el apellido_p en la imagen
    imagettftext($imagen, $tamanioTexto, 0, 299, 137, $colorTexto, $fuente, $apellido_p);
    // Superponer el apellido_m en la imagen
    imagettftext($imagen, $tamanioTexto, 0, 299, 162, $colorTexto, $fuente, $apellido_m);

    // Superponer la fecha de nacimiento en la imagen
    imagettftext($imagen, $tamanioTexto, 0, 299, 316, $colorTexto, $fuente, $fechaFormateada);

    // Superponer el nombre en la imagen
    imagettftext($imagen, $tamanioTexto, 0, 299, 214, $colorTexto, $fuente, $nombre);

    // Superponer el RUT en la imagen
    imagettftext($imagen, $tamanioRut, 0, 102, 497, $colorTexto, $fuente, $rut);

    // Superponer el Lugar de nacimiento en la imagen
    imagettftext($imagen, $tamanioTextoReverso, 0, 170, 783, $colorTexto, $fuenteItalic, $lugar_nac);
    
    // Superponer la profesion en la imagen
    imagettftext($imagen, $tamanioTextoReverso, 0, 170, 806, $colorTexto, $fuenteItalic, $profesion);

    // Superponer el SEXO en la imagen
    imagettftext($imagen, $tamanioTexto, 0, 486, 265, $colorTexto, $fuente, $sexo);

    // Texto de ejemplo (Abajo)
    $textoEjemplo = "INCHL5168194826E02<<<<<<<<<<<<<\n0202170M2402170CHL20333044<7<8\nNOMBRE<APELLIDO<<NOMBRE<APELLI ";
    $tamanioEjemplo = 30;
    // Superponer el texto ejemplo en la imagen
    imagettftext($imagen, $tamanioEjemplo, 0, 45, 900, $colorTexto, $fuenteEjemplo, $textoEjemplo);

    
    
    // IMAGEN DESDE LA BD SOBRE LA PLANTILLA
    // Ruta de la imagen en la carpeta 'uploads'
    $rutaImagen = $fila['foto'];

    // Obtener la extensión del archivo
    $extension = pathinfo($rutaImagen, PATHINFO_EXTENSION); 

    // Variable de la imagen a superponer
    if (strtolower($extension) === 'png') {
        $imagenUsuario = imagecreatefrompng($rutaImagen);
    } elseif (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
        $imagenUsuario = imagecreatefromjpeg($rutaImagen);
    } else {
        die('Formato de imagen no compatible. Solo se admiten archivos JPEG, JPG y PNG.');
    }

    // Obtener las dimensiones de la imagen del usuario
    $anchoImagenUsuario = imagesx($imagenUsuario);
    $altoImagenUsuario = imagesy($imagenUsuario);

    // Definir el nuevo tamaño (ajustar según las necesidades)
    $nuevoAncho = 200; // Ancho deseado
    $nuevoAlto = 290;  // Alto deseado

    // Redimensionar la imagen del usuario
    $imagenUsuarioRedimensionada = imagescale($imagenUsuario, $nuevoAncho, $nuevoAlto);

    // Coordenadas donde superponer la imagen del usuario (ajusta según el diseño)
    $xImagenUsuario = 50;
    $yImagenUsuario = 130;  

    // Superponer la imagen del usuario en la imagen de la plantilla
    imagecopy($imagen, $imagenUsuarioRedimensionada, $xImagenUsuario, $yImagenUsuario, 0, 0, $nuevoAncho, $nuevoAlto);

    // Definir el nuevo tamaño (ajustar según las necesidades)
    $nuevoAncho = 70; // Ancho deseado
    $nuevoAlto = 70;  // Alto deseado
    // Redimensionar la imagen del usuario
    $imagenUsuarioRedimensionada = imagescale($imagenUsuario, $nuevoAncho, $nuevoAlto);
    // Coordenadas donde superponer la imagen del usuario (ajusta según el diseño)
    $xImagenUsuario = 700;
    $yImagenUsuario = 239; 
    // Superponer la imagen (pero la que esta pequeno de la derecha) del usuario en la imagen de la plantilla
    imagecopy($imagen, $imagenUsuarioRedimensionada, $xImagenUsuario, $yImagenUsuario, 0, 0, $nuevoAncho, $nuevoAlto);

    

    // Guardar o mostrar la imagen
    header('Content-Type: image/png'); // Establecer el encabezado para mostrar la imagen en el navegador
    imagepng($imagen); // Mostrar la imagen directamente en el navegador
    imagedestroy($imagen); // Liberar memoria
    imagedestroy($imagenUsuario);

    // Eliminar el archivo temporal del código QR
    unlink($rutaQR);

} else {
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
                <h2>No se encontro el dato en la Base de Datos.</h2>
            </div>
            <div class="container mt-5">
                <a href="index_test.php" class="btn btn-warning">Regresar</a>
            </div>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        </body>
        </html>';
}

// Cierra la conexión
$conexion->close();
?>
