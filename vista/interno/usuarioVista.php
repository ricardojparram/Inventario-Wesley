<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario</title>
    <?php $VarComp->header(); ?>
    <link rel="stylesheet" href="assets/css/estiloInterno.css">
</head>
<body>
<!-- ======= Header ======= -->

      <?php 
      
        $header->Header();
                
      ?>
      
<!-- End Header -->


<!-- ======= Sidebar ======= -->

      <?php 
      
         $menu->Menu();
                
      ?>
         
  <!-- End Sidebar-->



  <main class="main" id="main">
    <div class="pagetitle">
      <h1>Usuario</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Gestionar Usuario</li>
        </ol>
      </nav>

    </div>

  <div class="card">
            <div class="card-body">

              <div class="row">
                <div class="col-6">
                  <h5 class="card-title">Usuarios Registrados</h5>
                </div>

                <div class="col-6 text-end mt-3">
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#basicModal" id="agregarModalButton">Agregar</button>
              </div>


              <!-- Table with stripped rows -->

              <div class="table-responsive">
                <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
                  <thead>

                    <tr>
                      <th scope="col">Cedula</th>
                      <th scope="col">Nombre</th>
                      <th scope="col">Apellido</th>
                      <th scope="col">Correo</th>
                      <th scope="col">Nivel</th>
                      <th scope="col">Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th scope="col">Cedula</th>
                      <th scope="col">Nombre</th>
                      <th scope="col">Apellido</th>
                      <th scope="col">Correo</th>
                      <th scope="col">Nivel</th>
                      <th scope="col">Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody id="tbody">
                  </tbody>
                </table>
              </div>
              <!-- End Table with stripped rows -->

              <!-- Modal Registrar -->
              <div class="modal fade" id="basicModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header alert alert-success">
                      <h5 class="modal-title"><strong>Agregar Nuevo Usuario</strong></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                     <form method="POST">
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Cedula</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La cédula debe tener 6 o más carácteres, solo números(0-9)."><i class="bi bi-person-fill"></i></button>
                          <input type="text" class="form-control" name="cedula" id="cedula" placeholder="Cédula">
                        </div>
                        <p class="m-0" id="errorCed" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Nombre</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-people-fill"></i></button>
                          <input type="text" class="form-control" name="name" id="name" placeholder="Nombre">
                        </div>
                        <p class="m-0" id="errorNom" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Apellido</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El apellido debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-people"></i></button>
                          <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido">
                        </div>
                        <p class="m-0" id="errorApe" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Correo</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca un correo electrónico válido. 
                            Ej: usuario@mail.es"><i class="ri-at-line"></i></button>
                          <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                        </div>
                        <p class="m-0" id="errorEmail" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Contraseña</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La contraseña debe tener como mínimo 8 carácteres. Admite cualquier tipo de carácter(a-zA-Z0-9!@#$%^&*)"><i class="bi bi-key-fill"></i></button>
                          <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña">
                        </div>
                        <p class="m-0" id="errorContra" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Nivel</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La contraseña debe tener como mínimo 8 carácteres. Admite cualquier tipo de carácter(a-zA-Z0-9!@#$%^&*)"><i class="bi bi-sort-up"></i></button>
                        <select  name= "tipoUsuario" class="form-select" id="select">
                          <option selected disabled>Niveles</option>
                          <?php if(isset($mostrarN)){
                              foreach($mostrarN as $datas){
                              ?> 
                            <option value="<?php echo $datas->id_rol; ?>" class="opcion"><?php echo $datas->nombre; ?></option>
                        <?php
                              }
                            }else{"";}?>
                          </select>
                        </div>
                        <p class="m-0" id="errorNivel" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <p id="error" style="color:#ff0000;text-align: center;"></p>
                    </div>
                    <div class="modal-footer">
                      <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarRegis">Cerrar</button>
                      <button type="button" class="btn btn-success" id="enviar">Registrar</button>
                    </div>
                  </form>
                  </div>
                </div>
              </div>

              <!-- Modal Editar -->
              <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header alert alert-success">
                      <h5 class="modal-title"><strong>Editar Usuario</strong></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                     <form method="POST">
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Cedula</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La cédula debe tener 6 o más carácteres, solo números(0-9)."><i class="bi bi-person-fill"></i></button>
                          <input type="text" class="form-control" name="cedulaEdit" id="cedulaEdit" placeholder="Cédula">
                        </div>
                        <p class="m-0" id="errorCedEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Nombre</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-people-fill"></i></button>
                          <input type="text" class="form-control" name="nameEdit" id="nameEdit" placeholder="Nombre">
                        </div>
                        <p class="m-0" id="errorNomEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Apellido</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El apellido debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-people"></i></button>
                          <input type="text" class="form-control" name="apellidoEdit" id="apellidoEdit" placeholder="Apellido">
                        </div>
                        <p class="m-0" id="errorApeEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Correo</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca un correo electrónico válido. 
                            Ej: usuario@mail.es"><i class="ri-at-line"></i></button>
                          <input type="email" class="form-control" name="emailEdit" id="emailEdit" placeholder="Email">
                        </div>
                        <p class="m-0" id="errorEmailEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Contraseña</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La contraseña debe tener como mínimo 8 carácteres. Admite cualquier tipo de carácter(a-zA-Z0-9!@#$%^&*)"><i class="bi bi-key-fill"></i></button>
                          <input type="password" class="form-control" name="passwordEdit" id="passwordEdit" placeholder="Contraseña">
                        </div>
                        <p class="m-0" id="errorContraEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="row mb-1">
                        <label for="inputText" class="col-sm-3 col-form-label"><strong>Nivel</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La contraseña debe tener como mínimo 8 carácteres. Admite cualquier tipo de carácter(a-zA-Z0-9!@#$%^&*)"><i class="bi bi-sort-up"></i></button>
                        <select  name= "tipoUsuarioEdit" class="form-select" id="selectEdit">
                          <option selected disabled>Niveles</option>
                          <?php if(isset($mostrarN)){
                              foreach($mostrarN as $datas){
                              ?> 
                            <option value="<?php echo $datas->id_rol; ?>" class="opcion"><?php echo $datas->nombre; ?></option>
                        <?php
                              }
                            }else{"";}?>
                          </select>
                        </div>
                        <p class="m-0" id="errorNivelEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <p id="error2" style="color:#ff0000;text-align: center;"></p>
                    </div>
                    <div class="modal-footer">
                      <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarRegisEdit">Cerrar</button>
                      <button type="button" class="btn btn-success" id="enviarEdit">Actualizar</button>
                    </div>
                  </form>
                  </div>
                </div>
              </div>

              <!-- Modal Eliminar-->
              <div class="modal fade" id="delModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <h5>Los datos serán eliminados completamente del sistema</h5>
                      <p class="m-0" id="errorDel" style="color:#ff0000;text-align: center;"></p>
                    </div>
                    <div class="modal-footer" id="divEli">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarModalDel">Cancelar</button>
                      <button type="button" class="btn btn-danger" id="delete">Borrar</button>
                    </div>
                  </div>
                </div>
              </div>


  </main>

</body>

  <?php $VarComp->js(); ?>
  <script type="text/javascript" src="assets/js/usuario.js"></script>
 
</html>