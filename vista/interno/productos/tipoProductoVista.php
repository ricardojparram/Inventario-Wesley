<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tipos de productos</title>
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
          <li class="breadcrumb-item"><h1>Tipos de productos</h1></li>
        </ol>
      </nav>

    </div>

    <div class="card">
      <div class="card-body">

        <div class="row">
          <div class="col-6">
            <h5 class="card-title">Nombre  de producto</h5>
          </div>

          <div class="col-6 text-end mt-3">
            <button type="button" class="btn btn-registrar agregarModal" data-bs-toggle="modal" data-bs-target="#basicModal">Agregar</button>
          </div>
        </div>

        <!-- Modal Registrar-->

        <div class="modal fade" id="basicModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-xm">
            <div class="modal-content">
              <div class="modal-header alert alert-success">
                <h4 class="modal-title"> <strong>Registrar Nombre de producto</strong> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body ">
                <form>
                  <div class="form-group col-md-12">  
                    <div class="container-fluid">
                      <div class="row">
                        <div class="form-group col-lg-8">
                          <label class="col-form-label"> <strong>Nombre de producto</strong> </label>
                          <input id="tipoProducto" type="text" class="form-control" required="" placeholder="Tipo de producto">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                 <p id="error" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta))? $respuesta : " " ?></p>
                <div class="modal-footer">
                  <button id="close" id="cerrarRegist" type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button id="enviar" type="submit" class="btn btn-registrar">Registrar</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal Editar-->

        <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-xm">
            <div class="modal-content">
              <div class="modal-header alert alert-success">
                <h4 class="modal-title"> <strong>Editar Nombre de producto</strong> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body ">
                <form>
                  <div class="form-group col-md-12">  
                    <div class="container-fluid">
                      <div class="row">
                        <div class="form-group col-lg-8">
                          <label class="col-form-label"> <strong>Nombre producto</strong> </label>
                          <input id="tipoProductoEdit"type="text" class="form-control" required="" >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <p id="error2" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta))? $respuesta : " " ?></p>
                <div class="modal-footer">
                  <button id="cerraar" type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button id="enviarEdit" type="submit" class="btn btn-registrar">Actualizar</button>
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
                <button id="cerrar" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="borrar" type="submit" class="btn btn-danger">Borrar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th scope="col">Nombrte de producto</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>


            <tbody id="tbody">

            </tbody>
          </table>
        </div>
        <!-- End Table with stripped rows -->

      </div>
    </div>


  </main>


</body>

<?php $VarComp->js(); ?>
<script type="text/javascript" src="assets/js/tipoProducto.js"></script>


</html>