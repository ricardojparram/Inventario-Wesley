<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal</title>
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
            <h1>Personal</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Gestionar Personal</li>
                </ol>
            </nav>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <h5 class="card-title">Personal Registrado</h5>
                    </div>
                    <div class="col-6 text-end mt-3">
                        <button type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#basicModal" id="agregarModalButton">Agregar</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <th scope="col">N° Documento</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Apellido</th>
                                <th scope="col">T. Empleado</th>
                                <th scope="col">Sede</th>
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
    <!-- Modal Registrar-->
    <div class="modal fade modal-lg" id="basicModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header alert alert-success">
                    <h5 class="modal-title"><strong>Agregar Nuevo Personal</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Nombre</strong></label>
                                <div class="input-group ">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-person-fill"></i></button>
                                    <input type="text" class="form-control" placeholder="Nombre" id="nom">
                                </div>
                                <p class="m-0" id="errorNom" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Apellido</strong></label>
                                <div class="input-group">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El apellido debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-people-fill"></i></button>
                                    <input type="text" class="form-control" placeholder="Apellido" id="ape">
                                </div>
                                <p class="m-0" id="errorApe" style="color:#ff0000;text-align: center;"></p>
                            </div>
                        </div>
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>N° Documento</strong></label>
                                <div class="input-group ">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Elija el tipo de nacionalidad (V, E) e introduzca el numero de documento."><i class="bi bi-card-text"></i></button>
                                    <select class="input-group-text" id="preDocument">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                    </select>
                                    <input type="text" class="form-control" placeholder="N° Documento" id="cedu">
                                </div>
                                <p class="m-0" id="errorCedu" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Nacimiento</strong></label>
                                <div class="input-group">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la fecha de nacimiento"><i class="bi bi-hourglass"></i></button>
                                    <input type="date" class="form-control" id="edad">
                                </div>
                                <p class="m-0" id="errorEdad" style="color:#ff0000;text-align: center;"></p>
                            </div>
                        </div>
                        <div class="row mb-1 col-md-12">
                            <label for="inputText" class="col-form-label"><strong>Direccion</strong></label>
                            <div class="input-group">
                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion de la habitacion"><i class="bi bi-map"></i></button>
                                <input type="text" class="form-control" id="direc" placeholder="Direccion">
                            </div>
                            <p class="m-0" id="errorDirec" style="color:#ff0000;text-align: center;"></p>
                        </div>
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Telefono</strong></label>
                                <div class="input-group ">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el numero del celular"><i class="bi bi-telephone"></i></button>
                                    <input type="text" class="form-control" placeholder="1234567890" id="tele">
                                </div>
                                <p class="m-0" id="errorTele" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Correo</strong></label>
                                <div class="input-group">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion de correo electronico"><i class="ri-at-line"></i></button>
                                    <input type="text" class="form-control" placeholder="ejemplo@ejemplo.com" id="email">
                                </div>
                                <p class="m-0" id="errorEmail" style="color:#ff0000;text-align: center;"></p>
                            </div>
                        </div>
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Tipo de Empleado</strong></label>
                                <div class="input-group ">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Elija un tipo para asignar al empleado"><i class="bi bi-sort-up"></i></button>
                                    <select class="form-select" id="tipo">
                                        <option selected disabled>Tipo</option>
                                        <?php if (isset($mostrarT)) {
                                            foreach ($mostrarT as $tipo) {
                                        ?>
                                                <option value="<?php echo $tipo->tipo_em; ?>" class="opcion"><?php echo $tipo->nombre_e; ?></option>
                                        <?php
                                            }
                                        } else {
                                            "";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <p class="m-0" id="errorTipo" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Sede</strong></label>
                                <div class="input-group">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Elija una sede para asignar al empleado"><i class="bi bi-pin-map"></i></button>
                                    <select class="form-select" id="sede">
                                        <option selected disabled>Sede</option>
                                        <?php if (isset($mostrarS)) {
                                            foreach ($mostrarS as $sede) {
                                        ?>
                                                <option value="<?php echo $sede->id_sede; ?>" class="opcion"><?php echo $sede->nombre; ?></option>
                                        <?php
                                            }
                                        } else {
                                            "";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <p class="m-0" id="errorSede" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <p id="errorRegis" style="color:#ff0000;text-align: center;"></p>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarRegis">Cerrar</button>
                    <button type="button" class="btn btn-registrar" id="enviar">Registrar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--- Modal de Editar -->
    <div class="modal fade modal-lg" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header alert alert-success">
                    <h5 class="modal-title"><strong>Editar Personal</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Nombre</strong></label>
                                <div class="input-group ">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-person-fill"></i></button>
                                    <input type="text" class="form-control" placeholder="Nombre" id="nomEdit">
                                </div>
                                <p class="m-0" id="errorNomEdit" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Apellido</strong></label>
                                <div class="input-group">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-people-fill"></i></button>
                                    <input type="text" class="form-control" placeholder="Apellido" id="apeEdit">
                                </div>
                                <p class="m-0" id="errorApeEdit" style="color:#ff0000;text-align: center;"></p>
                            </div>
                        </div>
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>N° Documento</strong></label>
                                <div class="input-group ">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Elija el tipo de nacionalidad (V, E) e introduzca el numero de documento."><i class="bi bi-card-text"></i></button>
                                    <select class="input-group-text " id="preDocumentEdit">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                    </select>
                                    <input type="text" class="form-control" placeholder="N° Documento" id="ceduEdit">
                                </div>
                                <p class="m-0" id="errorCeduEdit" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Nacimiento</strong></label>
                                <div class="input-group">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la fecha de nacimiento"><i class="bi bi-hourglass"></i></button>
                                    <input type="date" class="form-control" id="edadEdit">
                                </div>
                                <p class="m-0" id="errorEdadEdit" style="color:#ff0000;text-align: center;"></p>
                            </div>
                        </div>
                        <div class="row mb-1 col-md-12">
                            <label for="inputText" class="col-form-label"><strong>Direccion</strong></label>
                            <div class="input-group">
                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion de la habitacion"><i class="bi bi-map"></i></button>
                                <input type="text" class="form-control" id="direcEdit" placeholder="Direccion">
                            </div>
                            <p class="m-0" id="errorDirecEdit" style="color:#ff0000;text-align: center;"></p>
                        </div>
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Telefono</strong></label>
                                <div class="input-group ">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el numero del celular"><i class="bi bi-telephone"></i></button>
                                    <input type="text" class="form-control" placeholder="1234567890" id="teleEdit">
                                </div>
                                <p class="m-0" id="errorTeleEdit" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Correo</strong></label>
                                <div class="input-group">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion de correo electronico"><i class="ri-at-line"></i></button>
                                    <input type="text" class="form-control" placeholder="ejemplo@ejemplo.com" id="emailEdit">
                                </div>
                                <p class="m-0" id="errorEmailEdit" style="color:#ff0000;text-align: center;"></p>
                            </div>
                        </div>
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Tipo de Empleado</strong></label>
                                <div class="input-group ">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Elija un tipo para asignar al empleado"><i class="bi bi-sort-up"></i></button>
                                    <select class="form-select" id="tipoEdit">
                                        <option selected disabled>Tipo</option>
                                        <?php if (isset($mostrarT)) {
                                            foreach ($mostrarT as $tipo) {
                                        ?>
                                                <option value="<?php echo $tipo->tipo_em; ?>" class="opcion"><?php echo $tipo->nombre_e; ?></option>
                                        <?php
                                            }
                                        } else {
                                            "";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <p class="m-0" id="errorTipoEdit" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Sede</strong></label>
                                <div class="input-group">
                                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Elija una sede para asignar al empleado"><i class="bi bi-pin-map"></i></button>
                                    <select class="form-select" id="sedeEdit">
                                        <option selected disabled>Sede</option>
                                        <?php if (isset($mostrarS)) {
                                            foreach ($mostrarS as $sede) {
                                        ?>
                                                <option value="<?php echo $sede->id_sede; ?>" class="opcion"><?php echo $sede->nombre; ?></option>
                                        <?php
                                            }
                                        } else {
                                            "";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <p class="m-0" id="errorSedeEdit" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <p id="errorEdit" style="color:#ff0000;text-align: center;"></p>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarEdit">Cerrar</button>
                    <button type="button" class="btn btn-registrar" id="editar">Actualizar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar-->
    <div class="modal fade" id="delModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Los datos serán eliminados completamente del sistema</h5>
                    <p class="m-0" id="errorDel" style="color:#ff0000;text-align: center;"></p>
                </div>
                <div class="modal-footer" id="divEli">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarDel">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="delete">Borrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--- Modal de Datos  -->
    <div class="modal fade modal-lg" id="datosModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header alert alert-success">
                    <h5 class="modal-title"><strong>Datos Personal</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Nombre</strong></label>
                                <div class="input-group ">
                                    <p class="m-0" id="nomDatos"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Apellido</strong></label>
                                <div class="input-group">
                                    <p class="m-0" id="apeDatos"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>N° Documento</strong></label>
                                <div class="input-group ">
                                    <p class="m-0" id="ceduDatos"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Edad</strong></label>
                                <div class="input-group">
                                    <p class="m-0" id="edadDatos"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1 col-md-12">
                            <label for="inputText" class="col-form-label"><strong>Direccion</strong></label>
                            <div class="input-group">
                                <p class="m-0" id="direcDatos"></p>
                            </div>
                        </div>
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Telefono</strong></label>
                                <div class="input-group ">
                                    <p class="m-0" id="teleDatos"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Correo</strong></label>
                                <div class="input-group">
                                    <p class="m-0" id="emailDatos"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group col-md-12">
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Tipo de Empleado</strong></label>
                                <div class="input-group ">
                                    <p class="m-0" id="tipoDatos"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="inputText" class="col-form-label"><strong>Sede</strong></label>
                                <div class="input-group">
                                    <p class="m-0" id="sedeDatos"></p>
                                </div>
                            </div>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarDatos">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</body>
<?php $VarComp->js(); ?>
<script src="assets/js/personal.js"></script>

</html>