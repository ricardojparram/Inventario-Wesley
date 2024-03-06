<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Envíos</title>
  <?php $comp->header(); ?>
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
    <h1>Envios</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Comprobación de envíos para las ventas online</li>
      </ol>
    </nav>

  </div>

  <div class="card">
    <div class="card-body">

      <div class="row">
        <div class="col-6">
          <h5 class="card-title">Listado de envíos</h5>
        </div>



        <!-- Table with stripped rows -->

        <div class="table-responsive">
          <table class="table table-bordered " id="tablaEnvios" width="100%" cellspacing="0">
            <thead>

              <tr>
                <th scope="col">N° Envío</th>
                <th scope="col">Cédula cliente</th>
                <th scope="col">Nombre cliente</th>
                <th scope="col">Sede de envío</th>
                <th scope="col">Fecha de envío</th>
                <th scope="col">Fecha de entrega</th>
                <th scope="col">Estado</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <!-- End Table with stripped rows -->

        <!-- Modal comprobar envio -->
        <div class="modal fade" id="comprobacion" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"><strong>Estado de la comprobación</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">

                <section class="row justify-content-center asignacionDeEstado">

                  <span class="col-4 d-flex justify-content-center">
                    <input type="radio" checked value=3 class="btn-check" name="options-outlined" id="dark-outlined">
                    <label class="btn btn-outline-dark" for="dark-outlined">En proceso</label>
                  </span>
                  <span class="col-4 d-flex justify-content-center">
                    <input type="radio" value=2 class="btn-check" name="options-outlined" id="warning-outlined">
                    <label class="btn btn-outline-warning" for="warning-outlined">En camino</label>
                  </span>
                  <span class="col-4 d-flex justify-content-center">
                    <input type="radio" value=1 class="btn-check" name="options-outlined" id="success-outlined">
                    <label class="btn btn-outline-success" for="success-outlined">Entregado</label>
                  </span>
                  
                </section>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" id="enviarEstadoDeEnvio">Actualizar</button>
              </div>
            </div>
          </div>
        </div>

  </main>

</body>

<?php $comp->js(); ?>
<script type="text/javascript" src="assets/js/envios.js"></script>

</html>
