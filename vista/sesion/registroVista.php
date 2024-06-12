<!DOCTYPE html>
<html lang="en">

<head>
  <title>Registrar</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <?php $VarComp->header(); ?>
    <link href="assets/css/login.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/tienda.css">

</head>

<body class="bg-gradient-primary" id="body">

<header class="w-100 h-100">
    
  <!-- Barra navegadora -->
  <?php $tiendaComp->nav(); ?>

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
                        <h1 class="B fw-bold text-center py-2 ">Crear cuenta</h1>
                      </div>

                        <form class="user">
                                
                          <div class="form-group">

                            <div class="mb-4">
                              <div class="input-group">
                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La cédula debe tener 6 o más carácteres, solo números(0-9)."><i class="bi bi-person-fill"></i></button>
                                <input type="text" placeholder="Cédula" class="form-control" id="cedula">
                              </div>

                            </div>

                            <div class="row">
                            <div class="mb-4 col-sm-6">
                              <div class="input-group">
                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-people-fill"></i></button> 
                                <input type="text" placeholder="Nombre" class="form-control"  id="name">
                              </div>

                            </div>

                            <div class="mb-4 col-sm-6">
                              <div class="input-group">
                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El apellido debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-people"></i></button> 
                               <input type="text" placeholder="Apellido" class="form-control" id="apellido">
                               </div> 

                            </div>
                            </div>

                            <div class="mb-4">
                              <div class="input-group">
                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca un correo electrónico válido. 
                                Ej: usuario@mail.es"><i class="ri-at-line"></i></button> 
                               <input type="text" placeholder="Email" class="form-control" id="email">
                              </div>  
         
                            </div>
               
                            <div class="mb-4">
                              <div class="input-group">
                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La contraseña debe tener como mínimo 8 carácteres. Admite cualquier tipo de carácter(a-zA-Z0-9!@#$%^&*)"><i class="bi bi-key-fill"></i></button> 
                                 <input type="password" placeholder="Contraseña" class="form-control" id="password">
                               </div> 
                            </div>

                            <div class="mb-4">
                              <div class="input-group">
                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la contraseña registrada con su cédula."><i class="bi bi-key"></i></button>  
                                <input type="password" placeholder="Repetir contraseña" class="form-control" id="repass">
                               </div> 
                            </div>

                            </div>
                            <p style="color:#ff0000;text-align: center;" id="error"></p>
                            <div class="d-grid">
                              <button type="submit" id="boton" class="btn btn-primary">Registrar Cuenta</button>
                            </div><br>

                            

                            <hr>

                            <div class="my-3 text-center asd">
                              <span>¿Ya tienes cuenta? <a href="?url=login">Iniciar sesión</a></span><br>
                              <span>¿Olvidaste tu contraseña? <a href="?url=recuperar">Recuperar Contraseña</a></span>
                            </div>

               </div>
           </div>
        </div>
      </div>
    </div>

  </div>

  <?php $VarComp->js();?>   

  <script src="assets/js/registrar.js"></script>

</body>

</html>
