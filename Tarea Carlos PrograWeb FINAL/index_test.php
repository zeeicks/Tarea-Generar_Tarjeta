<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Registro de Personas</title>
</head>
<body>
<div class="container mt-5">
  <h2>Formulario de Registro</h2>
  <form action="procesar_registro.php" method="post" enctype="multipart/form-data">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="nombre">Nombre(s)</label>
        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Juan Pedro" required>
      </div>
      <div class="form-group col-md-6">
        <label for="a_paterno">Apellido Paterno</label>
        <input type="text" class="form-control" id="a_paterno" name="a_paterno" placeholder="Carrasco" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="a_materno">Apellido Materno</label>
        <input type="text" class="form-control" id="a_materno" name="a_materno" placeholder="Maldonado" required>
      </div>
      <div class="form-group col-md-6">
        <label for="rut">RUT (con puntos y guion)</label>
        <input type="text" class="form-control" id="rut" name="rut" placeholder="12.987.654-0" required>
    </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="nacionalidad">Nacionalidad</label>
        <select class="form-control" id="nacionalidad" name="nacionalidad" required>
          <!-- Agrega opciones según las nacionalidades que necesites -->
          <option value="chilena">Chilena</option>
          <option value="otra">Otra</option>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label>Sexo</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="sexo" id="sexo_masculino" value="masculino" required>
          <label class="form-check-label" for="sexo_masculino">Masculino</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="sexo" id="sexo_femenino" value="femenino" required>
          <label class="form-check-label" for="sexo_femenino">Femenino</label>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="fecha_nac">Fecha de Nacimiento</label>
        <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
      </div>
      <div class="form-group col-md-6">
        <label for="lugar_nac">Lugar de Nacimiento</label>
        <input type="text" class="form-control" id="lugar_nac" name="lugar_nac" placeholder="Ej: Santiago" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="profesion">Profesión</label>
        <input type="text" class="form-control" id="profesion" name="profesion" placeholder="No informada / Profesion" required>
      </div>
      <div class="form-group col-md-6">
        <label>¿Inscrito en el Registro de Discapacidad?</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="discapacidad" id="discapacidad_si" value="discapacidad" required>
          <label class="form-check-label" for="discapacidad_si">Si</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="discapacidad" id="discapacidad_no" value="discapacidad" required>
          <label class="form-check-label" for="discapacidad_no">No</label>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label>¿Es Donante?</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="donante" id="donante_si" value="donante" required>
          <label class="form-check-label" for="donante_si">Si</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="donante" id="donante_no" value="donante" required>
          <label class="form-check-label" for="donante_no">No</label>
        </div>
      </div>
      <div class="form-group col-md-6">
        <label for="foto">Foto</label>
        <input type="file" class="form-control-file" id="foto" name="foto" required accept=".jpeg, .jpg, .png">
      </div>
    </div>
    
    <input type="submit" class="btn btn-primary mt-3" value="Guardar">
  </form>

  <hr>

  <h2>Personas Registradas</h2>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Nombre</th>
        <th scope="col">Apellido Paterno</th>
        <th scope="col">Apellido Materno</th>
        <!-- Agrega más columnas según los campos que desees mostrar en la tabla -->
      </tr>
    </thead>
    <tbody>
      <?php
      include_once('conexion.php');

      $result = $conexion->query("SELECT * FROM personas");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['a_paterno']}</td>
                <td>{$row['a_materno']}</td>
              </tr>";
        // Agrega más columnas según los campos que hayas definido en tu tabla
      }
      ?>
    </tbody>
  </table>

  <div class="container mt-5">
    <h2>Generar Cédula por ID</h2>

    <form action="generar_cedula.php" method="get">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="id">Ingrese el ID de la cédula:</label>
          <input type="text" class="form-control" id="id" name="id" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Generar Cédula</button>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
