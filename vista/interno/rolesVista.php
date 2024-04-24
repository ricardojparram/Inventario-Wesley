<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Roles</title>
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
    <h1>Roles</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Gestionar módulos y permisos</li>
      </ol>
    </nav>

  </div>

  <div class="card">
    <div class="card-body">

      <div class="row">
        <div class="col-6">
          <h5 class="card-title">Roles listados</h5>
        </div>

        <div class="col-6 text-end mt-3">
         <button 
            type="button"
            class="btn btn-success"
            data-bs-toggle="modal"
            data-bs-target="#Agregar"
            <?= (isset($permiso['Registrar'])) ? "" : "disabled"?> 
          >
            Agregar
          </button>
       </div>
     </div>


        <!-- Table with stripped rows -->

        <div class="table-responsive">
          <table class="table table-bordered " id="tabla" width="100%" cellspacing="0">
            <thead>

              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Usuarios totales</th>
                <th scope="col" style="width: 20%;">Opciones</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Usuarios totales</th>
                <th scope="col" style="width: 20%;">Opciones</th>
              </tr>
            </tfoot>
            <tbody>
            </tbody>
          </table>
        </div>
        <!-- End Table with stripped rows -->

        

  </main>

</body>

<?php $VarComp->js(); ?>
<script type="text/javascript" src="assets/js/roles.js"></script>

</html>
<!-- MODAL AGERGAR -->
<div class="modal fade " id="Agregar" tabindex="-1">
  <div class="modal-dialog modal-md ">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h4 class="modal-title"> <strong>Registrar rol</strong> </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id = "agregarform">
      <div class="modal-body ">


          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">

                <div class="form-group col-12 ">                          
                  <label class="col-form-label"> <strong>Nombre del rol</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el nombre del rol"><i class="bi bi-file-person"></i></button> 
                    <input class="form-control" id="rol_nombre" placeholder="Introduzca el rol">
                  </div>
                  <p style="color:#ff0000;margin-left: 10px;" id="error1"></p>

                </div>

              </div>
            </div>
          </div>

      </div>

      <p style="color:#ff0000;text-align: center;" id="error"><?php echo (isset($respuesta)) ? $respuesta : " "; ?></p>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success " id="registrar">Registrar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- MODAL AGREGAR FINAL -->

<!-- MODAL EDITAR -->
<div class="modal fade " id="modal_editar" tabindex="-1">
  <div class="modal-dialog modal-md ">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h4 class="modal-title"> <strong>Editar rol</strong> </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id = "editarform">
      <div class="modal-body ">


          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">

                <div class="form-group col-12 ">                          
                  <label class="col-form-label"> <strong>Nombre del rol</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Modifique el nombre del rol"><i class="bi bi-file-person"></i></button> 
                    <input class="form-control" id="rol_nombre_edit" placeholder="Modifique el rol">
                  </div>
                  <p style="color:#ff0000;margin-left: 10px;" id="error2"></p>

                </div>

              </div>
            </div>
          </div>

      </div>

      <p style="color:#ff0000;text-align: center;" id="error"><?php echo (isset($respuesta)) ? $respuesta : " "; ?></p>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success " id="editar">Editar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- MODAL EDITAR FINAL -->

<!-- MODAL BORRAR -->
<div class="modal fade" id="modal_borrar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="display: none; ">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Advertencia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Desea Borrar los datos del Rol?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cerrar</button>
        <button id="borrar" type="button" class="btn btn-danger">Borrar</button>
      </div>
    </div> 
  </div>
</div>
<!-- MODAL BORRAR FINAL-->

<!-- Modal Ḿódulos y permisos -->
<div class="modal fade" id="modal_permisos" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content h-75">
      <div class="modal-header " style="background: var(--color-hover); border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
        <h5 class="modal-title text-white"><strong>Asignar módulos y permisos</strong></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body w-100 pt-0">
        <div class="table-responsive">
          <table class="table table-borderless table-hover" width="100%" cellspacing="0">
            <thead class="">

              <tr>
                <th class="text-center font-italic">Módulos</th>
                <th class="text-center " >Permisos</th>
              </tr>

            </thead>

            <tbody id="tabla_permisos" class="">

            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" id="enviarPermisos">Actualizar</button>
      </div>
    </div>
  </div>
</div>
