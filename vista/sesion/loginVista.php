<!doctype html>
<html lang="en">

<head>
  <title>Inciar sesión</title>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
</head>

</html>
<?php $VarComp->header(); ?>
<link href="assets/css/login.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/tienda.css">

</head>



<body class="bg-gradient-primary" id="body">

  <header class="w-100 h-100">

    <!-- Barra navegadora -->
    <?php $Nav->nav(); ?>

  </header>


  <div class="container carLogin col-xl-5 col-lg-7 col-sm-8">
    <div class="o-hidden border-0 my-5">
      <div class="p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="w-100">
            <div class="p-5">
              <div class="text-end">
                <img src="assets/img/Logos Wesley/logo_wesley_sin_titulo.svg" width="50px" alt="">
              </div>
              <div class="text-center">
                <h1 class="B fw-bold text-center py-2">Iniciar Sesión</h1>
              </div>
              <form id="user">
                <div class="form-group">
                  <div class="mb-4">
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Ingrese la sede en la que se encuentra."><i class="bi bi-hospital"></i></button>
                      <select class="form-select" id="sede">
                        <?php foreach ($sedes as $row) : ?>
                          <option value="<?= $row['id_sede'] ?>"> <?= $row["nombre"] ?> </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="mb-4">
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca su cédula registrada en el sistema."><i class="bi bi-person-fill"></i></button>
                      <select class="input-group-text" id="preDocument">
                        <option value="V">V</option>
                        <option value="E">E</option>
                      </select>
                      <input type="text" placeholder="Cédula" class="form-control" id="cedula">
                    </div>
                  </div>
                  <div class="mb-4">
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la contraseña registrada con su cédula."><i class="bi bi-key-fill"></i></button>
                      <input type="password" placeholder="Contraseña" class="form-control" id="pass">
                    </div>
                  </div>
                  <p style="color:#ff0000;text-align: center;" id="error"></p>
                  <div class="d-grid">
                    <button type="submit" id="boton" class="btn btn-primary">Iniciar Sesión</button>
                  </div><br>
                  <hr>
                  <div class="my-3 text-center asd">
                    <span>Olvidaste tu contraseña? <a href="recuperar">Recuperar contraseña</a></span>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php $VarComp->js(); ?>

  <script src="assets/js/login.js"></script>




</body>

</html>