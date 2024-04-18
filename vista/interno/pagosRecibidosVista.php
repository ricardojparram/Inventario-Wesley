<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos recibidos</title>
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
            <h1>Pagos recibidos</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Pagos recibidos</li>
                </ol>
            </nav>

        </div>

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <h5 class="card-title">Pagos recibidos registrados</h5>
                    </div>
                </div>


                <!-- Table with stripped rows -->

                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Nº Factura</th>
                                <th scope="col">Cedula</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Monto</th>
                                <th scope="col">Estado</th>
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
<script type="text/javascript" src="assets/js/pagosRecibidos.js"></script>

</html>
<!-- MODAL DE MOSTRAR CONFIRMAR PAGO -->
<div class="modal fade" id="ConfirmarPago" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-success">
                <h5 class="modal-title"><strong class="pago_titulo"></strong></h5>
                <h5 class="modal-title"><strong class="pago_status"></strong></h5>
                <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <table id="tabla_detalle" class="table table-hover  table-borderless">
                    <thead>
                        <th>Tipo de pago</th>
                        <th>Referencia</th>
                        <th>Monto</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class=" pt-2 px-5">
                    <span class="d-flex justify-content-end gap-2">
                        <p><b>Total Bs: </b></p>
                        <p class="text-end" id="monto_bs"></p>
                    </span>
                    <span class="d-flex justify-content-end gap-2">
                        <p><b>Total Divisa: </b></p>
                        <p class="text-end" id="monto_divisa"></p>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success respuesta" status="1" data-bs-dismiss="modal">Confirmar</button>
                <button type="button" class="btn btn-danger respuesta" status="2" data-bs-dismiss="modal">Rechazar</button>
                <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- FINAL MODAL DE CONFIRMAR PAGO -->