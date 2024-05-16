<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descargo</title>
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
                        <button type="button" id="agregarModal" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#Agregar">Agregar</button>
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
<script src="assets/js/descargo.js"></script>

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

<!-- INICIO MODAL DE AGREGAR -->

<div class="modal fade" id="Agregar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-success">
                <h3 class="modal-title"> <strong>Registrar Descargo</strong> </h3>
                <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
            </div>

      <form id="agregarform" enctype='multipart/form-data'>
            <div class="modal-body ">

                    <div class="form-group col-md-12">
                        <div class="container-fluid">
                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label for="sede" class="col-sm-6 col-form-label"><strong>Número de Descargo</strong></label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el número de Descargo."><i class="bi bi-person-fill"></i></button>
                                        <input class="form-control" type="text" id="num_descargo" name="num_descargo" />
                                    </div>
                                    <p class="error" style="color:#ff0000;text-align: center;" id="error1"></p>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-form-label" for="fecha"><strong>Fecha</strong></label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Fecha en la que se hace la transferencia"><i class="bi bi-calendar2-date"></i></button>
                                        <input class="form-control" disabled type="date" id="fecha" name="fecha" />
                                    </div>
                                    <p class="error" style="color:#ff0000;text-align: center;" id="error2"></p>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label class="col-form-label" for="formFile"><strong>Imagenes adjuntadas</strong></label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Fecha en la que se hace la transferencia"><i class="bi bi-images"></i></button>
                                        <input class="form-control custom-file-input" multiple type="file" id="formFile" name="img[]">
                                    </div>
                                    <p class="error" style="color:#ff0000;text-align: center;" id="error2"></p>
                                </div>
                                <label class="custom-file-label col-lg-6 d-flex align-items-center" for="formFile"></label>

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
                                                    <th>Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaSeleccionarProductos">
                                                <tr>
                                                    <td width="1%"><a class="eliminarFila a-asd" role="button"><i class="bi bi-trash-fill"></i></a></td>
                                                    <td width='30%' class="position-relative">
                                                        <select class="select-productos select-asd" name="producto">
                                                            <option></option>
                                                        </select>
                                                        <span class="d-none floating-error">error</span>
                                                    </td>
                                                    <td class="cantidad position-relative">
                                                        <input class="select-asd" type="text" value="" />
                                                        <span class="d-none floating-error">error</span>
                                                    </td>
                                                    <td class="descripcion position-relative">
                                                      <input class="select-asd" type="text" value="" />
                                                      <span class="d-none floating-error">error</span>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                        <p class="filaTipoPago error" id="error" style="color:#ff0000;text-align: center;"></p>
                                        <a class="agregarFila a-asd" role="button"></i> Nueva fila</a> <br>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <p id="error_productos" class="error" style="color:#ff0000;text-align: center;"></p>
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
