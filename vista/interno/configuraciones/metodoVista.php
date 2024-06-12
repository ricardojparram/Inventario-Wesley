<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Método</title>
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
          <li class="breadcrumb-item"><h1>Gestionar método de pago</h1></li>
        </ol>
      </nav>

    </div>

    <div class="card">
      <div class="card-body">

        <div class="row">
          <div class="col-6">
            <h5 class="card-title">Método de pago</h5>
          </div>

          <div class="col-6 text-end mt-3">
            <button type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#registrarModal">Agregar</button>

          </div>
        </div>


        <div class="table-responsive">
          <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
            <thead>

              <tr>
                <th scope="col">Tipos de pago</th>

                <th scope="col">Opciones</th>

              </tr>
            </thead>
            <tbody id ="tbody">
             
           
        </tbody>
      </table>
    </div>
    <!-- End Table with stripped rows -->

  </div>
</div>


</main>


s
<!-- Modal Registrar-->
<div class="modal fade" id="registrarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header alert alert-success">
        <h3 class="modal-title"> <strong>Registrar Método de Pago</strong> </h3>
        <button id="cerrar" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="modal-body ">
          <form class="user">
            <div class="form-group col-md-11">  
              <div class="container-fluid">
                <div class="row">

                 <div class="form-group col-lg-7">
                  <label class="col-form-label"><strong>Método de Pago*</strong></label> 
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El metodo de Pago debe tener como mínimo 4 carácteres"><i class="bi bi-credit-card"></i></button> 
                    <input id="tipo" class="form-control" required="" placeholder="Método de Pago" >
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
          

        </div>
        <p id="error" class="error" style="color:#ff0000;text-align: center;"></p>
        <div class="modal-footer">
          <button type="reset" id="close" id="cerrarRegis" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" id="enviar" class="btn btn-registrar">Registrar</button>

        </div>

      </div>
    </form>
  </div>
</div>
</div>



<!-- Modal Editar-->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
 <div class="modal-dialog modal-lg">
  <div class="modal-content">

    <div class="modal-header alert alert-success">
      <h3 class="modal-title"> <strong>Editar Método de Pago</strong> </h3>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">

      <div class="modal-body ">
        <form>
          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">

               <div class="form-group col-lg-6">
                <label class="col-form-label"> <strong>Método de Pago*</strong> </label>
                <div class="input-group">

                  <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=""><i class="bi bi-credit-card"></i></button> 

                  <input id="tipoEdit" class="form-control" required="" >

                </div>
              </div>
            </div>
          </div>
        </div>

       </div>
        <p id="error2" class="error" style="color:#ff0000;text-align: center;"></p>
        <div class="modal-footer">
          <button type="reset" id="closeEdit" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" id="enviarEdit"class="btn btn-registrar">Actualizar</button>
        </div>

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
      </div>
      <div class="modal-footer">
        <button id="closeModal" type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>

        <button id="deletes" type="button" class="btn btn-danger">Borrar</button>
      </div>
    </div>
  </div>
</div>

<?php $VarComp->js(); ?>   
<script src="assets/js/metodo.js"></script> 

<!-- Development version -->
<script src="assets/js/popper.js"></script>

<!-- Production version -->
<script src="assets/js/popper.min.js"></script>



</body>

</html>