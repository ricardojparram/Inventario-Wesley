<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituciones</title>
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
                        <h1> Gestionar Instituciones</h1>
                    </li>
                </ol>
            </nav>

        </div>

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <h5 class="card-title">Instituciones</h5>
                    </div>
                    <div class="col-6 text-end mt-3">
                        <button type="button" id="agregarModalButton" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#Agregar" <?= (isset($permiso['Registrar'])) ? "" : "disabled" ?>>
                            Agregar
                        </button>
                    </div>
                </div>

                <!-- COMIENZO DE TABLA -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tableMostrar" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Rif</th>
                                <th scope="col">Razón Social</th>
                                <th scope="col">Dirección</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Opciones</th>
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
<script src="assets/js/institucion.js"></script>

<!-- TODOS LOS MODAL -->

<!-- MODAL AGREGAR -->
<div class="modal fade " id="Agregar" tabindex="-1">
    <div class="modal-dialog modal-md ">
        <div class="modal-content">
            <div class="modal-header alert alert-success" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <h4 class="modal-title"> <strong>Registrar institucion</strong> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="agregarform">
                <div class="modal-body ">


                    <div class="form-group col-md-12">
                        <div class="container-fluid">
                            <div class="row">

                                <div class="form-group col-6">
                                    <label class="col-form-label"> <strong>RIF</strong> </label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el RIF de la institucion"><i class="bi bi-card-text"></i></button>
                                        <input class="form-control" id="rif" required="" placeholder="J-123123123">
                                    </div>
                                    <p class="m-0" id="errorRif" style="color:#ff0000;text-align: center;"></p>
                                </div>

                                <div class="form-group col-6">
                                    <label class="col-form-label"> <strong>Razón Social</strong> </label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el nombre de la institucion"><i class="bi bi-people-fill"></i></button>
                                        <input class="form-control" id="razon" placeholder="Razon">
                                    </div>
                                    <p class="m-0" id="errorRazon" style="color:#ff0000;text-align: center;"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="container-fluid">
                            <div class="row">

                                <div class="form-group col-6">
                                    <label class="col-form-label"><strong>Dirección</strong></label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion del institucion"><i class="bi bi-card-image"></i></button>
                                        <input class="form-control" id="direccion" required="" placeholder="Direccion">
                                    </div>
                                    <p class="m-0" id="errorDirec" style="color:#ff0000;text-align: center;"></p>
                                </div>
                                <div class="form-group col-6">
                                    <label class="col-form-label"><strong>Contacto</strong></label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion del institucion"><i class="bi bi-telephone"></i></button>
                                        <input class="form-control" id="contacto" required="" placeholder="Contacto">
                                    </div>
                                    <p class="m-0" id="errorContac" style="color:#ff0000;text-align: center;"></p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <p style="color:#ff0000;text-align: center;" id="errorR"><?php echo (isset($respuesta)) ? $respuesta : " "; ?></p>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" id="cerraR" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-registrar " id="registrar">Registrar</button>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- MODAL AGREGAR FINAL -->

<!-- MODAL EDITAR -->
<div class="modal fade" id="Editar" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header alert alert-success" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <h4 class="modal-title"> <strong>Editar Laboratorio</strong> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editarform">
                <div class="modal-body ">
                    <div class="form-group col-md-12">
                        <div class="container-fluid">
                            <div class="row">

                                <div class="form-group col-6">
                                    <label class="col-form-label"> <strong>RIF</strong> </label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el RIF de la institucion"><i class="bi bi-card-text"></i></button>
                                        <input class="form-control" id="rifEdit" required="" placeholder="J-123123123">
                                    </div>
                                    <p class="m-0" id="errorRifEdit" style="color:#ff0000;text-align: center;"></p>
                                </div>

                                <div class="form-group col-6">
                                    <label class="col-form-label"> <strong>Razón Social</strong> </label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el nombre de la institucion"><i class="bi bi-people-fill"></i></button>
                                        <input class="form-control" id="razonEdit" placeholder="Razon">
                                    </div>
                                    <p class="m-0" id="errorRazonEdit" style="color:#ff0000;text-align: center;"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="container-fluid">
                            <div class="row">

                                <div class="form-group col-6">
                                    <label class="col-form-label"><strong>Dirección</strong></label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion del institucion"><i class="bi bi-card-image"></i></button>
                                        <input class="form-control" id="direccionEdit" required="" placeholder="Direccion">
                                    </div>
                                    <p class="m-0" id="errorDirecEdit" style="color:#ff0000;text-align: center;"></p>
                                </div>
                                <div class="form-group col-6">
                                    <label class="col-form-label"><strong>Contacto</strong></label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion del institucion"><i class="bi bi-telephone"></i></button>
                                        <input class="form-control" id="contactoEdit" required="" placeholder="Contacto">
                                    </div>
                                    <p class="m-0" id="errorContacEdit" style="color:#ff0000;text-align: center;"></p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <p style="color:#ff0000;text-align: center;" id="errorEdit"></p>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" id="cerrarE" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-registrar" id="editar">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MODAL EDITAR FINAL -->

<!-- MODAL BORRAR -->
<div class="modal fade" id="Borrar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="display: none; ">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Advertencia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Desea Borrar los Datos de la Institucion?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cerrarB" data-bs-dismiss="modal">Cerrar</button>
        <button id="borrar" type="button" class="btn btn-danger">Borrar</button>
      </div>
    </div>
  </div>
</div>
<!-- MODAL BORRAR FINAL-->

</html>