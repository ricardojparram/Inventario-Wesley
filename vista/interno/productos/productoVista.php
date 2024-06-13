<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Producto</title>
  <?php $VarComp->header(); ?>
  <link rel="stylesheet" href="assets/css/cropper.css" />
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

  <!-- MAIN -->

  <main class="main" id="main">
    <div class="pagetitle">

      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <h1>Productos</h1>
          </li>
        </ol>
      </nav>

    </div>

    <div class="card">
      <div class="card-body">

        <div class="row">
          <div class="col-6">

          </div>

          <div class="col-6 text-end mt-3">
            <button type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#basicModal">Agregar</button>
          </div>
        </div>



        <div class="table-responsive">
          <table class="table table-bordered" id="tableMostrar" width="100%" cellspacing="0">
            <thead class="text-center">
              <tr>
                <th scope="col" class="text-center">Código</th>
                <th scope="col" class="text-center">Nombre</th>
                <th scope="col" class="text-center">Presentación</th>
                <th scope="col" class="text-center">Opciones</th>

              </tr>
            </thead>

            <tbody id="tbody" class="text-center">



            </tbody>
          </table>
        </div>
        <!-- End Table with stripped rows -->

      </div>
    </div>
  </main>

  <!-- End Main-->

  <!-- Modal Registrar-->

  <div class="modal fade" id="basicModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header alert alert-success">
          <h3 class="modal-title"> <strong>Registrar Producto</strong> </h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="agregarform">
          <div class="modal-body ">

            <div class="form-group col-md-12">
              <div class="container-fluid">
                <div class="row">



                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Código del producto</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="ri-capsule-fill"></i></button>
                      <input id="cod_producto" class="form-control" placeholder="">
                    </div>
                    <p class="error" id="error1" style="color: red"></p>
                  </div>

                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Nombre del Producto</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class=" ri-map-2-line"></i></button>
                      <select class="form-control" aria-label="Default select example" id="tipoprod">
                        <option value="" selected="">Seleccione una opción</option>
                        <?php if (isset($mostrarTipoPro)) : ?>
                          <?php foreach ($mostrarTipoPro as $data) : ?>
                            <option value="<?php echo $data->id_tipoprod; ?>" class="opcion"><?php echo $data->nombrepro; ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <p class="error" id="error2" style="color: red"></p>
                  </div>


                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Presentación</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Descripción "><i class="bx  bxs-capsule"></i></button>
                      <select class="form-control" aria-label="Default select example" id="presentacion">
                        <option selected disabled>Seleccione una opción</option>
                        <?php if (isset($mostraPres)) : ?>
                          <?php foreach ($mostraPres as $data) : ?>
                            <option value="<?= $data->cod_pres; ?>" class="opcion"><?= $data->presentacion ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <p class="error" id="error3" style="color: red"></p>
                  </div>
                </div>
              </div>

            </div>


            <div class="form-group col-md-12">
              <div class="container-fluid">
                <div class="row">
                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Laboratorio</strong></label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Descripción "><i class=" ri-flask-fill"></i></button>
                      <select class="form-control" aria-label="Default select example" id="laboratorio">
                        <option selected value="">Seleccione una opción</option>
                        <?php if (isset($mostraLab)) : ?>
                          <?php foreach ($mostraLab as $data) : ?>
                            <option value="<?php echo $data->rif_laboratorio; ?>" class="opcion"><?php echo $data->razon_social; ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <p class="error" id="error4" style="color: red"></p>
                  </div>

                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Tipo de producto</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx bxs-bong"></i></button>
                      <select class="form-control" aria-label="Default select example" id="tipoP">
                        <option selected disabled>Seleccione una opción</option>
                        <?php if (isset($mostraTipo)) : ?>
                          <?php foreach ($mostraTipo as $data) : ?>
                            <option value="<?php echo $data->id_tipo; ?>" class="opcion"><?php echo $data->nombre_t; ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <p class="error" id="error5" style="color: red"></p>
                  </div>

                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Clase</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bxs-capsule"></i></button>
                      <select class="form-control" aria-label="Default select example" id="clase">
                        <option selected disabled>Seleccione una opción</option>
                        <?php if (isset($mostrarClase)) : ?>
                          <?php foreach ($mostrarClase as $data) : ?>
                            <option value="<?php echo $data->id_clase; ?>" class="opcion"><?php echo $data->nombre_c; ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <p class="error" id="error6" style="color: red"></p>
                  </div>

                </div>
              </div>
            </div>

            <div class="form-group col-md-12">
              <div class="container-fluid">
                <div class="row">


                  <div class="form-group col-lg-6">
                    <label class="col-form-label"> <strong>Composición del producto</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="ri-capsule-line"></i></button>
                      <textarea class="form-control" id="composicion"></textarea>
                    </div>
                    <p class="error" id="error7" style="color: red"></p>
                  </div>



                  <div class="form-group col-lg-6">
                    <label class="col-form-label"> <strong>Posología</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bi bi-clock"></i></button>
                      <textarea class="form-control" id="posologia" placeholder=""></textarea>
                    </div>
                    <p class="error" id="error8" style="color: red"></p>
                  </div>



                </div>
              </div>
            </div>

            <div class="form-group col-md-12">
              <div class="container-fluid">
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label class="col-form-label"><strong>Contraindicaciones</strong></label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bx-no-entry"></i></button>
                      <textarea class="form-control" id="contraIn" placeholder=""></textarea>
                    </div>
                    <p class="error" id="error9" style="color: red"></p>
                  </div>

                </div>
              </div>
            </div>


          </div>
          <p id="error" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta)) ? $respuesta : " " ?></p>
          <div class="modal-footer">
            <button id="cerrar" type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
            <button id="boton" type="submit" class="btn btn-registrar">Registrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Editar-->


  <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header alert alert-success">
          <h3 class="modal-title"> <strong>Editar Producto</strong> </h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="editarform">
          <div class="modal-body ">

            <div class="form-group col-md-12">
              <div class="container-fluid">
                <div class="row">



                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Código del producto</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Descripción"><i class="ri-capsule-fill"></i></button>
                      <input id="cod_productoEd" class="form-control" placeholder="código">
                    </div>
                    <p class="error" id="errorE1" style="color: red"></p>
                  </div>

                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Nombre del Producto</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class=" ri-map-2-line"></i></button>
                      <select class="form-control" aria-label="Default select example" id="tipoprodEd">
                        <option value="" selected="">Seleccione una opción</option>
                        <?php if (isset($mostrarTipoPro)) : ?>
                          <?php foreach ($mostrarTipoPro as $data) : ?>
                            <option value="<?php echo $data->id_tipoprod; ?>" class="opcion"><?php echo $data->nombrepro; ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <p class="error" id="errorE2" style="color: red"></p>
                  </div>


                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Presentación</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bxs-capsule"></i></button>
                      <select class="form-control" aria-label="Default select example" id="presentacionEd">
                        <option selected disabled>Seleccione una opción</option>
                        <?php if (isset($mostraPres)) : ?>
                          <?php foreach ($mostraPres as $data) : ?>
                            <option value="<?= $data->cod_pres; ?>" class="opcion"><?= $data->presentacion; ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <p class="error" id="errorE3" style="color: red"></p>
                  </div>
                </div>
              </div>

            </div>


            <div class="form-group col-md-12">
              <div class="container-fluid">
                <div class="row">
                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Laboratorio</strong></label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Descripción"><i class="ri-flask-fill"></i></button>
                      <select class="form-control" aria-label="Default select example" id="laboratorioEd">
                        <option selected value="">Seleccione una opción</option>
                        <?php if (isset($mostraLab)) : ?>
                          <?php foreach ($mostraLab as $data) : ?>
                            <option value="<?php echo $data->rif_laboratorio; ?>" class="opcion"><?php echo $data->razon_social; ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>


                      </select>
                    </div>
                    <p class="error" id="errorE4" style="color: red"></p>
                  </div>

                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Tipo de producto</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx bxs-bong"></i></button>
                      <select class="form-control" aria-label="Default select example" id="tipoEd">
                        <option selected disabled>Seleccione una opción</option>
                        <?php if (isset($mostraTipo)) : ?>
                          <?php foreach ($mostraTipo as $data) : ?>
                            <option value="<?php echo $data->id_tipo; ?>" class="opcion"><?php echo $data->nombre_t; ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <p class="error" id="errorE5" style="color: red"></p>
                  </div>

                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Clase</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bxs-capsule"></i></button>
                      <select class="form-control" aria-label="Default select example" id="claseEd">
                        <option selected disabled>Seleccione una opción</option>
                        <?php if (isset($mostrarClase)) : ?>
                          <?php foreach ($mostrarClase as $data) : ?>
                            <option value="<?php echo $data->id_clase; ?>" class="opcion"><?php echo $data->nombre_c; ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <p class="error" id="errorE6" style="color: red"></p>
                  </div>

                </div>
              </div>
            </div>

            <div class="form-group col-md-12">
              <div class="container-fluid">
                <div class="row">




                  <div class="form-group col-lg-6">
                    <label class="col-form-label"> <strong>Composición del producto</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="ri-capsule-line"></i></button>
                      <textarea class="form-control" id="composicionEd" placeholder="Composición del producto"></textarea>
                    </div>
                    <p class="error" id="errorE7" style="color: red"></p>
                  </div>



                  <div class="form-group col-lg-6">
                    <label class="col-form-label"> <strong>Posología</strong> </label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bi bi-clock"></i></button>
                      <textarea class="form-control" id="posologiaEd" placeholder="posologia"></textarea>
                    </div>
                    <p class="error" id="errorE8" style="color: red"></p>
                  </div>



                </div>
              </div>
            </div>

            <div class="form-group col-md-12">
              <div class="container-fluid">
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label class="col-form-label"><strong>Contraindicaciones</strong></label>
                    <div class="input-group">
                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bx-no-entry"></i></button>
                      <textarea class="form-control" id="contraInEd" placeholder=""></textarea>
                    </div>
                    <p class="error" id="errorE9" style="color: red"></p>
                  </div>

                </div>
              </div>
            </div>


          </div>
          <p id="error" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta)) ? $respuesta : " " ?></p>
          <div class="modal-footer">
            <button id="Cancelar" type="reset" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
            <button id="actualizar" type="submit" class="btn btn-registrar">Actualizar</button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Modal Editar Final-->

  <!-- Modal delete-->

  <div class="modal fade" id="delModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5>Los datos del producto serán eliminados del sistema.</h5>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal" id="close">Cancelar</button>
          <button type="button" class="btn btn-danger" id="delete">Eliminar</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal delete Final-->

</body>
<?php $VarComp->js(); ?>
<script src="assets/js/cropper.min.js"></script>
<script src="assets/js/producto.js"></script>

</html>

<!-- Modal cambiar imagen -->
<!--<div class="modal fade" id="infoImg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-50" aria-labelledby="staticBackdropLabel" aria-hidden="true">
 <div class="modal-dialog modal-md ">
  <div class="modal-content">
    <div class="modal-header alert alert-success">
      <h3 class="modal-title"> <strong>Imagen del producto</strong> </h3>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body ">
      <form id="editarImagen" enctype="multipart/form-data" class="row">


        <div class="w-100 col-lg-5 col-md-4 col-sm-4 col-xs-3 d-flex align-items-center justify-content-center">
            <div class="w-75" style="height: 350px;" >
              <img class="fotoProducto w-100" id="imgEditar" src="" alt="Foto del Producto">
            </div>
        </div>

        <div>
          <label class="col-md-4 col-lg-3 col-form-label"><strong>Imagen</strong></label>
          <input type="file" class="form-control" id="img" name="img" placeholder="img">
        </div>
        <div class="col-12 mt-2">
          <a href="#" class="btn btn-danger" id="borrarFoto" title="Eliminar foto del producto">Eliminar <i class="bi bi-trash"></i></a>
        </div>
          
        <p id="error" class="error" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta)) ? $respuesta : " " ?></p>

        <div class="modal-footer">
          <button id="Cancelar" type="reset" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
          <button id="actualizarImg" type="button" class="btn btn-success">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>-->
<!-- Final modal editar imagen -->

<!-- Modal croppear imagen -->
<!--<div class="modal fade" id="fotoModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h5 class="modal-title"><strong>Recortar Foto de Producto</strong></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="row ">
          <div class="col-md-6 mx-auto text-center" id="imgContainer">
            <div class="">
              <img id="imgModal" class="img-fluid w-100 h-auto" src="#">
            </div>
          </div>        
        </div>

      </div>

      <div class="modal-footer">
        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarModal">Cancelar</button>
        <button type="button" class="btn btn-success" id="aceptarCroppedImg">Aceptar</button>
      </div>
    </div>
  </div>
</div>-->
<!-- Final modal croppear imagen -->