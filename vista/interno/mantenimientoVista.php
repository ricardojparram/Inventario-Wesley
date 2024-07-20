<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento</title>
    <?php $VarComp->header(); ?>
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
            <h1>Mantenimiento</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Mantenimiento</li>
                </ol>
            </nav>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <h5 class="card-title">Registro de Exportacion</h5>
                    </div>
                    <div class="col-6 text-end mt-3">
                        <button type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#modal" id="agregarModalButton">Exportacion</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <th scope="col">Usuario</th>
                                <th scope="col">Descripcion</th>
                                <th scope="col">Fecha</th>
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
     <!-- Modal Eliminar-->
     <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>¿Desea Exportar la Base de Datos?</h5>
                    <p class="m-0" id="error" style="color:#ff0000;text-align: center;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrar">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="exportar">Exportar</button>
                </div>
            </div>
        </div>
    </div>
</body>
<?php $VarComp->js(); ?>
<script src="assets/js/mantenimiento.js"></script>

</html>