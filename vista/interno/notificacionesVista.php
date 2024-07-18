<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notificaciones</title>
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
      <h1>Notificaciones</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Entrada de las notificaciones</li>
        </ol>
      </nav>

    </div>

    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-6">
            <h5 class="card-title">Notificaciones</h5>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table class="table table-hover" id="tabla" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th scope="col">Titulo</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Aquí se llenarán las filas de la tabla dinámicamente -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <main>

</body>

<?php $VarComp->js(); ?>
<script src="assets/js/notificaciones.js"></script>

</html>