<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventario</title>
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
          <li class="breadcrumb-item">
            <h1> Inventario de productos</h1>
          </li>
        </ol>
      </nav>

    </div>

    <div class="card">
      <div class="card-body">

        <div class="row">
          <div class="col-6">
            <h5 class="card-title"></h5>
          </div>
          <br>

          <!-- COMIENZO DE TABLA -->
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tableMostrar" width="100%" cellspacing="0">
              <thead>

                <tr>
                  <th scope="col">Usuario</th>
                  <th scope="col">Sede</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">Producto</th>
                  <th scope="col">Entrada</th>
                  <th scope="col">Salida</th>
                  <th scope="col">Tipo movimiento</th>
                  <th scope="col">Lote</th>
                  <th scope="col">Cantidad</th>
                </tr>
              </thead>


              <tbody id="tbody">


              </tbody>
            </table>
          </div>
          <!-- FINAL DE TABLA -->

        </div>
      </div>


  </main>


</body>

<?php $VarComp->js(); ?>

<script src="assets/js/historialInventario.js"></script>



</html>

<!-- TODOS LOS MODAL -->


<!-- MODAL BORRAR FINAL-->