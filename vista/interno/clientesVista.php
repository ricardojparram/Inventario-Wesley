<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
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
      <h1>Clientes</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Clientes</li>
        </ol>
      </nav>

    </div>

  <div class="card">
            <div class="card-body">

              <div class="row">
                <div class="col-6">
                  <h5 class="card-title">Clientes Registrados</h5>
                </div>
                <div class="col-6 text-end mt-3">
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#basicModal" id="agregarModalButton">Agregar</button>
                </div>
              </div>

              

              <!-- Table with stripped rows -->
        
              <div class="table-responsive">
                <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
                  <thead>

                    <tr>
                      <th scope="col">Nombre</th>
                      <th scope="col">Apellido</th>
                      <th scope="col">Cedula</th>
                      <th scope="col">Direccion</th>
                      <th scope="col">Telefono</th>
                      <th scope="col">Correo</th>
                      <th scope="col">Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th scope="col">Nombre</th>
                      <th scope="col">Apellido</th>
                      <th scope="col">Cedula</th>
                      <th scope="col">Direccion</th>
                      <th scope="col">Telefono</th>
                      <th scope="col">Correo</th>
                      <th scope="col">Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody id="tbody">
                    
                    
                    
                  </tbody>
                </table>
              </div>
              <!-- End Table with stripped rows -->

            </div>
          </div>
          

          <!--Modal Registar-->

          <div class="modal fade" id="basicModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header alert alert-success">
                      <h5 class="modal-title"><strong>Registar Nuevo Cliente</strong></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                     <form class="clienRegis" method="POST">
                     <div class="row form-group col-md-12">
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Nombre</strong></label>
                        <div class="input-group ">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover"data-bs-placement="top" data-bs-content="Introduzca el nombre del cliente."><i class="bi bi-person-fill"></i></button>
                          <input type="text" class="form-control" placeholder="Nombre" id="nomClien">
                        </div>
                        <p class="m-0" id="errorNom" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Apellido</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el apellido del cliente."><i class="bi bi-people"></i></button>
                          <input type="text" class="form-control" placeholder="Apellido" id="apeClien">
                        </div>
                        <p class="m-0" id="errorApe" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>
                    <div class="row form-group col-md-12 mb-3">
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Cedula</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la cedula del cliente."><i class="bi bi-card-text"></i></button>
                          <input type="text" class="form-control" placeholder="1234567890" id="cedClien">
                        </div>
                        <p class="m-0" id="errorCed" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="col-lg-6" >
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Direccion</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion de la habitacion del cliente"><i class="bi bi-map"></i></button>
                          <input type="text" class="form-control" placeholder="Direccion" id="direcClien" name="direcClien">
                        </div>
                        <p class="m-0" id="errorDirec" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>
                      <div class="row">
                        <div class="col-sm-10 ">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck1" name="gridCheck1">
                            <label class="form-check-label" for="gridCheck1">
                              Informacion de Contacto
                            </label>
                          </div>
                        </div>
                      </div>
                    
                      <div class=" row form-group col-md-12 mb-3" id="divOcult" >
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Telefono</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el numero celular del cliente"><i class="bi bi-telephone"></i></button>
                          <input type="text" class="form-control" placeholder="1234567890" id="telClien" name="telClien">
                        </div>
                        <p class="m-0" id="errorTele" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Correo</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion de correo electronico"><i class="ri-at-line"></i></button>
                          <input type="email" class="form-control" placeholder="ejemplo@ejemplo.com" id="emailClien" name="emailClien">
                        </div>
                        <p class="m-0" id="errorEmail" style="color:#ff0000;text-align: center;"></p>
                        </div>
                      </div>
                      
                      <p id="error" style="color:#ff0000;text-align: center;"></p>
                    </div>
                    <div class="modal-footer">


                      <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarModal">Cerrar</button>
                      <button type="button" class="btn btn-success" id="enviar">Registrar</button>
                    </div>
                    </form>
                  </div>
                </div>
              </div>


              <!-- Modal Editar-->
              
              <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" >
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header alert alert-success">
                      <h5 class="modal-title"><strong>Editar Cliente</strong></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                     <form class="clienRegis" method="POST">
                      
                     <div class="row form-group col-md-12">
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Nombre</strong></label>
                        <div class="input-group ">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el nombre del cliente."><i class="bi bi-person-fill"></i></button>
                          <input type="text" class="form-control" placeholder="Nombre" id="nomClienEdit" name="nomClienEdit" value="">
                        </div>
                        <p class="m-0" id="errorNomEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Apellido</strong></label>
                        <div class="input-group ">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el apellido del cliente."><i class="bi bi-people"></i></button>
                          <input type="text" class="form-control" placeholder="Apellido" id="apeClienEdit" name="apeClienEdit" value="">
                        </div>
                        <p class="m-0" id="errorApeEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>
                    <div class="row form-group col-md-12 ">
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Cedula</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la cedula del cliente."><i class="bi bi-card-text"></i></button>
                          <input type="text" class="form-control" placeholder="1234567890" id="cedClienEdit" name="cedClienEdit" value="">
                        </div>
                        <p class="m-0" id="errorCedEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="col-lg-6" >
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Direccion</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion de la habitacion del cliente"><i class="bi bi-map"></i></button>
                          <input type="text" class="form-control" placeholder="Direccion" id="direcClienEdit" name="direcClienEdit" value="">
                        </div>
                        <p class="m-0" id="errorDirecEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                    </div>
                    <div class=" row form-group col-md-12">
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Telefono</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el numero celular del cliente"><i class="bi bi-telephone"></i></button>
                          <input type="text" class="form-control" placeholder="1234567890" id="telClienEdit" name="telClienEdit" value="">
                        </div>
                        <p class="m-0" id="errorTeleEdit" style="color:#ff0000;text-align: center;"></p>
                      </div>
                      <div class="col-lg-6">
                        <label for="inputText" class="col-sm-2 col-form-label"><strong>Correo</strong></label>
                        <div class="input-group">
                          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la direccion de correo electronico"><i class="ri-at-line"></i></button>
                          <input type="text" class="form-control" placeholder="ejemplo@ejemplo.com" id="emailClienEdit" name="emailClienEdit" value="">
                        </div>
                        <p class="m-0" id="errorEmailEdit" style="color:#ff0000;text-align: center;"></p>
                        </div>
                      </div>
                      <p id="error2" class="mt-2 mb-0" style="color:#ff0000;text-align: center;"></p>
                    </div>
                    <div class="modal-footer">
                      <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarModalEdit">Cerrar</button>
                      <button type="button" class="btn btn-success" id="enviarEdit">Actualizar</button>
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
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarModalDel">Cancelar</button>
                      <button type="button" class="btn btn-danger" id="delete">Borrar</button>
                    </div>
                  </div>
                </div>
              </div>


  </main>

</body>

  <?php $VarComp->js();?>
  <script src="assets/js/cliente.js"></script>
 
</html>