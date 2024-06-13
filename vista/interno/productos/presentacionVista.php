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
                 
                </div>

                <div class="col-6 text-end mt-3">
                  <button type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#Agregar">Agregar</button>
                </div>
              </div>

              <!-- COMIENZO DE TABLA -->
         <div class="table-responsive">
                <table class="table table-bordered table-hover text-center" id="tablas" width="100%" cellspacing="0">
                  <thead class="text-center">

                    <tr>
                      <th scope="col" class="text-center">#</th>
                      <th scope="col" class="text-center">Cantidad</th>
                      <th scope="col" class="text-center">Medida</th>
                      <th scope="col" class="text-center">Peso</th>
                      <th scope="col" class="text-center">Opciones</th>

                    
                    </tr>
                  </thead>
              
                <tbody id="tbody" class="text-center">

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
  <div class="modal-dialog modal-lg ">
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

                <div class="form-group col-lg-4">
                 <label class="col-form-label"> <strong>Medida</strong> </label>
                  <div class="input-group">
                  <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bxs-capsule"></i></button>
                  <select class="form-control" aria-label="Default select example" id="medida">
                  <option selected disabled>Seleccionar medida</option>
                  <?php if(isset($mostrarMedida)){
                    foreach($mostrarMedida as $data){
                  ?> 
                  <option value="<?php echo $data->id_medida; ?>" class="opcion"><?php echo $data->nombre; ?></option>
                  <?php
                    }
                  }else{"";}?>

                  </select>
                  </div>
                  <p class="error" id="error" style="color: red"></p>  
                </div>


                <div class="form-group col-4">                          
                  <label class="col-form-label"> <strong>Cantidad</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la cantidad"><i class="bi bi-people-fill"></i></button> 
                    <input class="form-control"  id="cantidad" placeholder="">
                  </div>
                </div>

                <div class="form-group col-4">                          
                  <label class="col-form-label"> <strong>Peso</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la cantidad"><i class="bi bi-people-fill"></i></button> 
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
          <button type="submit" class="btn btn-registrar " id="registrar">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- MODAL AGREGAR FINAL -->


<!-- MODAL EDITAR -->
<div class="modal fade" id="editModal" tabindex="-1">
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

                 <div class="form-group col-lg-4">
                       <label class="col-form-label"> <strong>Medida</strong> </label>
                        <div class="input-group">
                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bxs-capsule"></i></button>
                        <select class="form-control" aria-label="Default select example" id="medidas">
                      
                        <?php if(isset($mostrarMedida)){
                          foreach($mostrarMedida as $data){
                        ?> 
                        <option value="<?php echo $data->id_medida; ?>" class="opcion" ><?php echo $data->nombre; ?></option>
                        <?php
                          }
                        }else{"";}?>

                        </select>
                        </div>
                        <p class="error" id="error" style="color: red"></p>  
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
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el peso"><i class="bi bi-people-fill"></i></button> 
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
          <button id="editarP" type="submit" class="btn btn-registrar" >Editar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- MODAL EDITAR FINAL --> 

<!-- MODAL BORRAR -->
   <div class="modal fade" id="delModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button id="cerrar">
              </div>
              <div class="modal-body">
                <h5>El tipo de presentacion serán eliminados del sistema</h5>
              </div>
              <div class="modal-footer">
                <button id="cerrar" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="borrar" type="submit" class="btn btn-danger">Borrar</button>
              </div>
            </div>
          </div>
        </div>
              <!-- MODAL BORRAR FINAL-->