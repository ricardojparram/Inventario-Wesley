<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presentación</title>
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
      
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><h1> Gestionar Presentación</h1></li>
        </ol>
      </nav>

    </div>
    
  <div class="card">
            <div class="card-body">
            
              <div class="row">
                <div class="col-6">
                  <h5 class="card-title">Presentación</h5>
                </div>

                <div class="col-6 text-end mt-3">
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#Agregar">Agregar</button>
                </div>
              </div>


              <!-- COMIENZO DE TABLA -->
         <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tableMostrar" width="100%" cellspacing="0">
                  <thead>

                    <tr>
                      <th scope="col">Cant</th>
                      <th scope="col">U.Medida</th>
                      <th scope="col">Peso</th>
                      <th scope="col">Opciones</th>

                    
                    </tr>
                  </thead>
              
                
              <tbody id = "tbody">
                
                    
                  </tbody>
                </table>
              </div>
              <!-- FINAL DE TABLA -->

            </div>
          </div>


  </main>


</body>

  <?php $VarComp->js(); ?>

  <script src="assets/js/presentacion.js"></script> 


 
</html>

<!-- TODOS LOS MODAL -->

<!-- MODAL AGERGAR -->
<div class="modal fade " id="Agregar" tabindex="-1">
  <div class="modal-dialog modal-xs ">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h4 class="modal-title"> <strong>Registrar Presentación</strong> </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body ">

        <form id = "agregarform">

          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">

                <div class="form-group col-4">                          
                  <label class="col-form-label"> <strong>U. Medida</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el RIF del proveedor"><i class="bi bi-card-text"></i></button> 
                    <input class="form-control" id="medida" required="" placeholder="">
                  </div>
                </div>

                <div class="form-group col-4">                          
                  <label class="col-form-label"> <strong>Cantidad</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca razon social"><i class="bi bi-people-fill"></i></button> 
                    <input class="form-control"  id="cantidad" placeholder="">
                  </div>
                </div>

                <div class="form-group col-4">                          
                  <label class="col-form-label"> <strong>Peso</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca razon social"><i class="bi bi-people-fill"></i></button> 
                    <input class="form-control"  id="peso" placeholder="">
                  </div>
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
<div class="modal fade" id="Editar" tabindex="-1">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h4 class="modal-title"> <strong>Editar Presentación</strong> </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body ">

        <form id = "editarform">

          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">

               <div class="form-group col-4">                          
                  <label class="col-form-label"> <strong>U. Medida</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el RIF del proveedor"><i class="bi bi-card-text"></i></button> 
                    <input class="form-control" id="medEdit" required="" placeholder="">
                  </div>
                </div>

                <div class="form-group col-4">                          
                  <label class="col-form-label"> <strong>Cantidad</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca razon social"><i class="bi bi-people-fill"></i></button> 
                    <input class="form-control"  id="cantEdit" placeholder="">
                  </div>
                </div>

                <div class="form-group col-4">                          
                  <label class="col-form-label"> <strong>Peso</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca razon social"><i class="bi bi-people-fill"></i></button> 
                    <input class="form-control"  id="pesEdit" placeholder="">
                  </div>
                </div>

              </div>
            </div>
          </div>

          
          
          
        </div>

        <div style="color:#ff0000;text-align: center;" id="errorEdit"></div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success" id="editar">Editar</button>
        </div>
      </form>
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
        ¿Desea borrar los datos de la presentación?
      </div>
      <div class="modal-footer">
       
        <button id="borrar" type="button" class="btn btn-danger">Borrar</button>
      </div>
    </div> 
  </div>
</div>
              <!-- MODAL BORRAR FINAL-->