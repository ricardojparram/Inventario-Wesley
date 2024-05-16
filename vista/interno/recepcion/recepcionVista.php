<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recepcion</title>
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
            <h1>Recepcion</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Recepcion</li>
                </ol>
            </nav>

        </div>

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <h5 class="card-title">Recepciones registradas</h5>
                    </div>
                    <div class="col-6 text-end mt-3">
                        <a href="?url=recepcion&registrar" class="btn btn-success agregar">Agregar</a>
                    </div>
                </div>


                <!-- Table with stripped rows -->

                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Recepcion</th>
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
<script type="text/javascript" src="assets/js/recepcion/recepcion.js"></script>

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

<!-- INICIO MODAL DE ELIMINAR -->
<div class="modal fade" id="Eliminar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Los datos serán anulados del sistema.</h5>
            </div>
            <div class="modal-footer">
                <button id="close" type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="anular">Anular</button>
            </div>
        </div>
    </div>
</div>

<!-- FINAL MODAL ELIMINAR -->
