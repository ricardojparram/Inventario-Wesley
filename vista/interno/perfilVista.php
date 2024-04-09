<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <?php $VarComp->header(); ?>
    <link rel="stylesheet" href="assets/css/estiloInterno.css">
    <link rel="stylesheet" href="assets/css/perfil.css">
    <link rel="stylesheet" href="assets/css/cropper.css"/>
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

  <main id="main" class="main"> 
<div class="pagetitle">
      <h1>Perfil</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Perfil</li>
        </ol>
      </nav>
    </div>
  
    <section class="section profile">
      <div class="row">
      <div class="col-xl-4"></div>
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img class="fotoPerfil rounded-circle" src="<?= $_SESSION['fotoPerfil']; ?>" alt="Profile">
              <h2 class="nombreCompleto"> </h2>
              <h3></h3>
            </div>
          </div>

        </div>
        <div class="col-xl-4"></div>

        <div class="col-xl-7">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview" id="perfil">Ver Perfil</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar Perfil</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Cambiar Contraseña</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <h5 class="card-title">Detalles del Perfil</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nombre Completo</div>
                    <div class="col-lg-9 col-md-8" id="name"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Trabajo</div>
                    <div class="col-lg-9 col-md-8" id="nivel"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Cedula</div>
                    <div class="col-lg-9 col-md-8" id="cedula"></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Correo</div>
                    <div class="col-lg-9 col-md-8" id="email"></div>
                  </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form id="formEditar" enctype="multipart/form-data">

                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Imagen de Perfil</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="row">
                          <div class="col-lg-5 col-md-4 col-sm-4 col-xs-3">
                            <img class="fotoPerfil" id="imgEditar" src="<?= $_SESSION['fotoPerfil']; ?>" alt="Profile">
                          </div>
                          <div class="col-lg-7 col-md-8 col-sm-8 col-xs-9 row">
                            <div class="col-12 mt-3">

                              <input type="file" name="foto" id="foto" class="form-control " title="Sube tu nueva foto de perfil"></input>

                            </div>

                            <div class="col-12 mt-2">
                              <a href="#" class="btn btn-danger" id="borrarFoto" title="Eliminar foto de perfil">Eliminar <i class="bi bi-trash"></i></a>
                            </div>
                          </div>

                        </div>
                        
                        
                      </div>
                    </div>

                    
                    <div class="row">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nombre</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="nombre" type="text" class="form-control" id="nameEdit" >
                        <p id="errorNom" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>
                    <div class="row">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Apellido</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="apellido" type="text" class="form-control" id="apeEdit" >
                        <p id="errorApe" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>
                    <div class="row ">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">N° Documento</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="input-group">
                          <select class="input-group-text " id="preDocument">
                            <option value="V">V</option>
                            <option value="E">E</option>
                          </select>
                          <input type="text" class="form-control" id="cedulaEdit" >
                        </div>
                        <p id="errorCedu" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>

                    <div class="row">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="emailEdit" >
                        <p id="errorEmail" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>
                    <p id="error" style="color:#ff0000;text-align: center;"></p>
                    <div class="text-center">
                      <button type="button" class="btn btn-success" id="enviarDatos">Guardar Cambios</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                
                 <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form >

                    <div class="row">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Contraseña Actual</label>
                      <div class="col-md-8 col-lg-9">
                        <input  type="password" class="form-control" id="password">
                        <p id="passwordError" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>

                    <div class="row">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nueva Contraseña</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="password" class="form-control" id="newPassword">
                        <p id="newPasswordError" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>

                    <div class="row">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Repita Nueva Contraseña</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="password" class="form-control" id="rePassword">
                        <p id="rePasswordError" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>
                    <p id="error2" style="color:#ff0000;text-align: center;"></p>
                    <div class="text-center">
                      <button type="button" class="btn btn-success" id="editContra">Cambiar Contraseña</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        
      </div>
      <div class="col-xl-5">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Otros Usuarios</h5>
              <div class="bd-example">
                <ul id="users" class="list-group list-group-flush">
                  
                </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>


</main>
</body>

<div class="modal fade" id="fotoModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h5 class="modal-title"><strong>Recortar Foto de Perfil</strong></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="row ">
          <div class="col-md-6 mx-auto text-center" id="imgContainer">
            <div class="">
              <img id="imgModal" class="img-fluid w-100 h-auto" src="#">
            </div>
          </div>        
        </div>

      </div>

      <div class="modal-footer">
        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarModal">Cancelar</button>
        <button type="button" class="btn btn-success" id="aceptar">Aceptar</button>
      </div>
  </div>
</div>
</div>


<div style="position: fixed;z-index: 99999;background: #000000b3;border-radius: 6px;padding: 21px;top: 0;width: 100%;height: 100%;display:none;" id="displayProgreso">
    <div style="height: 70px;width: 250px;position: relative;top: 50%;margin: auto;">
        <div style="padding: 23px;background: #fffcf269; border-radius: 8px;">
            <div class="progress progress-bar-primary">
                <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" id="progressBar"role="progressbar" style="width: 25%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>

<?php $VarComp->js(); ?>
<script src="assets/js/cropper.min.js"></script>
<script type="text/javascript" src="assets/js/perfil.js"></script>
</html>