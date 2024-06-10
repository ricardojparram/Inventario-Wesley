<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedes</title>
   <?php $VarComp->header(); ?>
    <link rel="stylesheet" href="assets/css/estiloInterno.css">
    <link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap5.min.css">
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
      <h1>Sedes</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Gestionar sedes de envío de cada fundación</li>
        </ol>
      </nav>

    </div>
    
    <div class="card">
      <div class="card-body">

        <div class="row">
          <div class="col-6">
            <h5 class="card-title">Sedes</h5>
          </div>

          <div class="col-6 text-end mt-3">
            <button type="button" id="agregarModal" <?= $disabled = (isset($permiso['Registrar'])) ? "" : "disabled"?> 
            class="btn btn-success" data-bs-toggle="modal" data-bs-target="#Agregar">Agregar</button>
          </div>
        </div>


        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="tableMostrar" width="100%" cellspacing="0">
            <thead>

              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Telefono</th>
                <th scope="col">Direccion</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>


            <tbody id = "tbody">

            </tbody>
          </table>
        </div>

      </div>
    </div>


  </main>


</body>

  <?php $VarComp->js(); ?>

  <script src="assets/js/sede.js"></script> 


 
</html>

<!-- TODOS LOS MODAL -->

<!-- MODAL AGERGAR -->
<div class="modal fade " id="Agregar" tabindex="-1">
  <div class="modal-dialog modal-md ">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h4 class="modal-title"> <strong>Registrar Sede de envío</strong> </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body ">

        <form id="agregarform">

          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">
              <div class="form-group col-12 ">                          
                  <label class="col-form-label"> <strong>Nombre de la sede</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Ingrese el nombre de la sede de envío."><i class="bi bi-card-text"></i></button> 
                    <input class="form-control" id="sedeNomb" required="" placeholder="Nombre de la sede de envío">
                  </div>
                  <p style="color:#ff0000;margin-left: 10px;" id="error1"></p>

                </div>



                <div class="form-group col-12 ">                          
                    <label for="inputText" class="col-form-label"><strong>Telefono de la sede</strong></label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Seleccione una empresa de envío."><i class="bi bi-building"></i></button>
                      <input class="form-control" id="sedeTele" required="" placeholder="Telefono de la sede">
                    </div>
                    <p style="color:#ff0000;margin-left: 10px;" id="error2"></p>
                </div>
               
               

                <div class="form-group col-12 ">                          
                  <label class="col-form-label"> <strong>Direccion de la sede</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la ubicación de la sede de envío"><i class="bi bi-pin-map-fill"></i></button> 
                    <input class="form-control" id="sedeDirec" required="" placeholder="Direccion de la sede">
                  </div>
                  <p style="color:#ff0000;margin-left: 10px;" id="error3"></p>

                </div>

              </div>

            </div>
          </div>

        </form>
      </div>

      <p style="color:#ff0000;text-align: center;" id="error"><?php echo (isset($respuesta)) ? $respuesta : " "; ?></p>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success " id="registrar">Registrar</button>
      </div>
    </div>
  </div>
</div>
<!-- MODAL AGREGAR FINAL -->


<!-- MODAL EDITAR -->
<div class="modal fade" id="Editar" tabindex="-1">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h4 class="modal-title"> <strong>Editar Sede de envío</strong> </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body ">

        <form id = "editarform">
          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">

               
                <div class="form-group col-12 ">                          
                  <label class="col-form-label"> <strong>Nombre de la sede</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Ingrese el nombre de la sede de envío."><i class="bi bi-card-text"></i></button> 
                    <input class="form-control" id="sedeNombEditar" required="" placeholder="Nombre de la sede de envío">
                  </div>
                  <p style="color:#ff0000;margin-left: 10px;" id="error3"></p>

                </div>


                <div class="form-group col-12 ">                          
                    <label for="inputText" class="col-form-label"><strong>Telefono de la sede</strong></label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Seleccione una empresa de envío."><i class="bi bi-building"></i></button>
                      <input class="form-control" id="sedeTeleEditar" required="" placeholder="Telefono de la sede">
                    </div>
                    <p style="color:#ff0000;margin-left: 10px;" id="error2"></p>
                </div>


                <div class="form-group col-12 ">                       
                  <label class="col-form-label"> <strong>Direccion de la sede</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la ubicación de la sede de envío"><i class="bi bi-pin-map-fill"></i></button> 
                    <input class="form-control" id="ubicacionEdit" required="" placeholder="">
                  </div>
                  <p style="color:#ff0000;margin-left: 10px;" id="error4"></p>

                </div>

              </div>
            </div>
          </div>
         
        </form>

      </div>

      <div style="color:#ff0000;text-align: center;" id="errorEdit"></div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" id="editar">Actualizar</button>
      </div>
    </div>
  </div>
</div>
<!-- MODAL EDITAR FINAL --> 

<!-- MODAL BORRAR -->
<div class="modal fade" id="Borrar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="display: none; ">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Advertencia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Desea Borrar los Datos de la Sede de Envío?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cerrar</button>
        <button id="borrar" type="button" class="btn btn-danger">Borrar</button>
      </div>
    </div> 
  </div>
</div>
              