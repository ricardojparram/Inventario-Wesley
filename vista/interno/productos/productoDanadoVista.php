<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Producto Dañado</title>
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
            <h1>Descargo</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Descargo</li>
                </ol>
            </nav>

        </div>

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <h5 class="card-title">Descargos registrados</h5>
                    </div>

                    <div class="col-6 text-end mt-3">
                        <button type="button" id="Exportar" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportar">Exportar</button>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabla" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <th scope="col">Descargo</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>


                        <tbody id="tbody">

                        </tbody>
                    </table>
                </div>

            </div>
        </div>


    </main>

</body>

<?php $VarComp->js(); ?>

<script src="assets/js/chosen.jquery.min.js"></script>
<script src="assets/js/productoDanado.js"></script>

</html>

<!-- TODOS LOS MODAL -->

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


