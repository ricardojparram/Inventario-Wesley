<!DOCTYPE html>
<html lang="en">

<head>
  <title>Recuperar</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <?php $VarComp->header(); ?>
  <link href="assets/css/login.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/tienda.css">
</head>

<body class="bg-gradient-primary" id="body">

  <header class="w-100 h-100">

    <!-- Barra navegadora -->
    <?php $Nav->nav(); ?>

  </header>

  <section class="container carLogin col-xl-5 col-lg-7 col-sm-8">
    <div class="o-hidden border-0 my-5">
      <div class="p-0">
        <div class="row">
          <div class="w-100">
            <div class="p-5">
              <div class="text-end">
                <img src="assets/img/Logos Wesley/logo_wesley_sin_titulo.svg" width="50px" alt="">
              </div>
              <div class="text-center">
                <h1 class="B fw-bold text-center py-3 h2">Recuperar contraseña</h1>
                <p class="text-justify">Ingrese el correo electrónico registrado y se le enviará un correo con su contraseña actual.</p>
              </div>
              <form id="recuperar">
                <div class="form-group">
                  <div class="mb-4">
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca un correo electrónico ya registrado."><i class="ri-at-line"></i></button>
                      <input type="text" placeholder="Email" class="form-control" id="email">
                    </div>
                    <p id="error_1" style="color: red;"></p>
                  </div>
                  <p style="color:#ff0000;text-align: center;" id='error'></p>
                  <div class="d-grid">
                    <button type="submit" id="boton" class="btn btn-primary">
                      Enviar</button>
                  </div><br>
                  <hr>
                  <div class="my-3 text-center asd">
                    <span>Ya tienes cuenta? <a href="login">Iniciar sesión</a></span><br>
                    <span>No tienes cuenta? <a href="registro">Regístrate</a></span>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php $VarComp->js(); ?>

  <script src="assets/js/recuperar.js"></script>
</body>

</html>