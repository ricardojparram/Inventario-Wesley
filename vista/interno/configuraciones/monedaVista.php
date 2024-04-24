<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Moneda</title>
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
            <h1>Moneda</h1>
          </li>
        </ol>
      </nav>

    </div>

    <div class="card">
      <div class="card-body">

        <div class="row">
          <div class="col-6">
            <h5 class="card-title">Tipos de moneda</h5>
          </div>

          <div class="col-6 text-end mt-3">
            <button id="agregarMoneda" type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#registrarMoneda">Agregar</button>

          </div>
        </div>

        <div class="table-responsive">


          <table class="table table-bordered" id="tabla2" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th scope="col">Moneda</th>
                <th scope="col">Cambio</th>
                <th scope="col">Fecha</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th scope="col">Moneda</th>
                <th scope="col">Cambio</th>
                <th scope="col">Fecha</th>
                <th scope="col">Opciones</th>
              </tr>
            </tfoot>
            <tbody id="tbody1">

            </tbody>
          </table>
        </div>
        <!-- End Table with stripped rows -->

      </div>
    </div>





  </main>
  <!-- Modal Registrar Moneda-->
  <div class="modal fade" id="registrarMoneda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header alert alert-success">
          <h3 class="modal-title"> <strong>Registrar Moneda</strong> </h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="user" id="registrarM">
          <div class="modal-body">
            <div class="modal-body ">
              <div class="form-group col-md-11">
                <div class="container-fluid">
                  <div class="row">
                    <div class="form-group col-lg">
                      <label class="col-form-label"> <strong>Nombre de Moneda</strong> </label>
                      <div class="input-group">
                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=""><i class="bi bi-currency-exchange"></i></button>
                        <input id="moneda" class="form-control" required="" placeholder="Moneda">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <p id="ms" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta)) ? $respuesta : " " ?></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" id="cerrarMonR" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" id="registrar" class="btn btn-registrar">Registrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Modal Actualizar Moneda-->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header alert alert-success">
          <h3 class="modal-title"> <strong>Actualizar Moneda</strong> </h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="user" id="editarMon">
          <div class="modal-body">


            <div class="modal-body ">
              <div class="form-group col-md-11">
                <div class="container-fluid">
                  <div class="row">

                    <div class="form-group col-lg">
                      <label class="col-form-label"> <strong>Nombre de Moneda</strong> </label>

                      <div class="input-group">

                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=""><i class="bi bi-currency-exchange"></i></button>

                        <input type="text" id="editMon" class="form-control" placeholder="Moneda">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <p id="ms2" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta)) ? $respuesta : " " ?></p>
            </div>
            <div class="modal-footer">
              <button type="reset" id="cerrarMonA" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" id="editarM" class="btn btn-registrar">Actualizar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>



  <!-- Modal Eliminar Moneda-->

  <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5>Los datos serán eliminados del sistema</h5>
        </div>
        <div class="modal-footer">
          <button id="cerrarMonE" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button id="eliminar" type="button" class="btn btn-danger">Borrar</button>
        </div>
      </div>
    </div>
  </div>



  <!-- Modal Registrar Cambio-->
  <div class="modal fade z-1" id="registrarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header alert alert-success">
          <h3 class="modal-title"> <strong>Registrar cambio de moneda</strong> </h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body ">
          <form class="user" id="registrarC">
            <div class="form-group col-md-11">
              <div class="container-fluid">
                <div class="row">

                  <div class="form-group col-lg-4">
                    <label class="col-form-label"><strong>Nombre de Moneda</strong></label>

                    <div class="input-group">

                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=""><i class="bi bi-currency-exchange"></i></button>
                      <select disabled id="selectMoneda" class="form-select selectM">
                        <option selected disabled>Moneda</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-lg-7">
                    <label class="col-form-label"> <strong>Al cambio</strong> </label>

                    <div class="input-group">

                      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=""><i class="bi bi-cash-coin"></i></button>
                      <input id="cambio" class="form-control" required="" placeholder="123,45">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <p id="error" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta)) ? $respuesta : " " ?></p>
            <div class="modal-footer">
              <button type="reset" id="close" class="btn btn-secondary" data-bs-target="#editHistory" data-bs-toggle="modal">Cerrar</button>
              <button type="submit" id="enviar" class="btn btn-registrar">Registrar</button>
            </div>
          </form>


        </div>
      </div>
    </div>
  </div>



  <!-- Modal Editar cambio-->

  <div class="modal fade z-1" id="editarModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header alert alert-success">
          <h3 class="modal-title"> <strong> tipo de moneda</strong> </h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="user" id="editarCambio">
          <div class="modal-body">
            <div class="modal-body ">

              <div class="form-group col-md-11">
                <div class="container-fluid">
                  <div class="row">
                    <div class="form-group col-lg-4">
                      <label class="col-form-label"> <strong>Nombre de Moneda</strong> </label>
                      <div class="input-group">
                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=""><i class="bi bi-currency-exchange"></i></button>
                        <select disabled id="monedaEdit" class="form-select selectM">
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-lg-6">
                      <label class="col-form-label"><strong>Al cambio</strong></label>
                      <div class="input-group">
                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=""><i class="bi bi-cash-coin"></i></button>
                        <input id="cambioEdit" class="form-control" required="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <p id="error2" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta)) ? $respuesta : " " ?></p>
            </div>
            <div class="modal-footer">
              <button type="reset" id="closeEdit" class="btn btn-secondary" data-bs-target="#editHistory" data-bs-toggle="modal">Cerrar</button>
              <button type="submit" id="enviarEdit" class="btn btn-registrar">Actualizar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Eliminar Cambio-->

  <div class="modal fade z-1" id="delModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5>Los datos serán eliminados completamente del sistema</h5>
        </div>
        <div class="modal-footer">
          <button id="closeDel" type="button" class="btn btn-secondary" data-bs-target="#editHistory" data-bs-toggle="modal">Cancelar</button>
          <button id="delete" type="button" class="btn btn-danger">Borrar</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal con Historial de Cambios -->
  <div class="modal fade" id="editHistory" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header alert alert-success">
          <h3 class="modal-title"> <strong>Gestionar Cambio</strong> </h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
              <h3 class="modal-title"> <strong id="nomMoneda"></strong> </h3>
            </div>

            <div class="col-6 text-end">
              <button id="agregarMoneda" type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#registrarModal">Agregar</button>

            </div>
          </div>

          <div class="table-responsive">

            <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
              <thead>
                <tr>

                  <th scope="col">Al cambio</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">Opciones</th>
                </tr>
              </thead>
              <tfoot>
                <tr>

                  <th scope="col">Al cambio</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">Opciones</th>
                </tr>
              </tfoot>
              <tbody id="tbody">

              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarHistory">Cerrar</button>
        </div>
      </div>
    </div>
  </div>



  <?php $VarComp->js(); ?>
  <script src="assets/js/moneda.js"></script>

  <!-- Development version -->
  <script src="assets/js/popper.js"></script>

  <!-- Production version -->
  <script src="assets/js/popper.min.js"></script>





</body>

</html>