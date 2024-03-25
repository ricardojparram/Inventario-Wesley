<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transferencia</title>
  <?php $VarComp->header(); ?>
  <link rel="stylesheet" href="assets/css/estiloInterno.css">
  <link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="assets/css/chosen.min.css">
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
      <h1>Transferencia</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Transferencia entres sedes</li>
        </ol>
      </nav>

    </div>

    <div class="card">
      <div class="card-body">

        <div class="row">
          <div class="col-6">
            <h5 class="card-title">Transferencia Registradas</h5>
          </div>
          <div class="col-6 text-end mt-3">
            <button type="button" class="btn btn-success agregar" data-bs-target="#Agregar" data-bs-toggle="modal">Agregar</button>
          </div>
        </div>


        <!-- Table with stripped rows -->

        <div class="table-responsive">
          <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th scope="col">Transferencia</th>
                <th scope="col">Sede</th>
                <th scope="col">Fecha</th>
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
<script src="assets/js/chosen.jquery.min.js"></script>
<script src="assets/js/select2.full.min.js"></script>
<script type="text/javascript" src="assets/js/transferencia.js"></script>

</html>
<!-- MODAL DE MOSTRAR DETALLES -->
<div class="modal fade" id="Detalle" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h5 class="modal-title"><strong class="detalle_titulo"></strong></h5>
        <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <table id="tabla_detalle" class="table table-hover">
          <thead>
            <th>Lote</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Vencimiento</th>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- FINAL MODAL DE MOSTRAR DETALLES -->

<!-- INICIO MODAL DE AGREGAR -->

<div class="modal fade" id="Agregar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h3 class="modal-title"> <strong>Registrar Transferencia</strong> </h3>
        <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
      </div>

      <div class="modal-body ">
        <form id="agregarform">

          <div class="form-group col-md-12">
            <div class="container-fluid">
              <div class="row">

                <div class="form-group col-md-6">
                  <label for="sede" class="col-sm-3 col-form-label"><strong>Sede</strong></label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Seleccione la sede que recibirá los productos."><i class="bi bi-person-fill"></i></button>
                    <select class="form-control select2" placeholder="Sede de recepcion" id="sede">
                      <option value="0" selected disabled>Sede de recepción</option>
                      <?php
                      if (isset($sedes)) {
                        foreach ($sedes as $sede) {
                      ?>
                          <option value="<?= $sede->id_sede; ?>" class="opcion"><?= $sede->nombre; ?></option>
                      <?php
                        }
                      }
                      ?>

                    </select>
                  </div>
                  <p class="error" style="color:#ff0000;text-align: center;" id="error1"></p>
                </div>

                <div class="form-group col-md-6">
                  <label class="col-form-label" for="fecha"><strong>Fecha</strong></label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Fecha en la que se hace la transferencia"><i class="bi bi-calendar2-date"></i></button>
                    <input class="form-control" type="date" id="fecha" />
                  </div>
                  <p class="error" style="color:#ff0000;text-align: center;" id="error4"></p>
                </div>

              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group my-3 ">
              <div class="container-fluid">
                <div class="row">
                  <div class="table table-body-tipo form-group col-12">

                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Producto</th>
                          <th>Cantidad</th>
                        </tr>
                      </thead>
                      <tbody id="tablaSeleccionarProductos">
                        <tr>
                          <td width="1%"><a class="eliminarFila a-asd" role="button"><i class="bi bi-trash-fill"></i></a></td>
                          <td width='30%'>
                            <select class="select-productos select-asd" name="producto">
                              <option></option>
                            </select>
                          </td>
                          <td class="cantidad"><input class="select-asd" type="number" value="" /></td>
                        </tr>

                      </tbody>
                    </table>

                    <p class="filaTipoPago error" style="color:#ff0000;text-align: center;"></p>
                    <a class="agregarFila a-asd" role="button"></i> Nueva fila</a> <br>

                  </div>
                </div>
              </div>

            </div>

          </div>

          <p id="pValid" class="error" style="color:#ff0000;text-align: center;"></p>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary cerrar" id="cerrar" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success " id="registrar">Registrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- FINAL MODAL DE AGREGAR -->