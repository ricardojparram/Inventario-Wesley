<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donativos Intituciones</title>
    <?php $VarComp->header(); ?>
    <link rel="stylesheet" href="assets/css/estiloInterno.css">
    <link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/css/chosen.min.css">
    <link rel="stylesheet" href="assets/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="assets/css/select2-bootstrap-5-theme.rtl.min.css">
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
      <h1>Donativos por Instituciones</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Donaciones para las Instituciones</li>
        </ol>
      </nav>

    </div>

  <div class="card">
            <div class="card-body">

              <div class="row">
                <div class="col-6">
                  <h5 class="card-title">Donaciones Registradas</h5>
                </div>
                <div class="col-6 text-end mt-3">
                  <button type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#agregar" id="agregarModalButton">Agregar</button>
                </div>
              </div>

              
              <!-- Table with stripped rows -->
        
              <div class="table-responsive">
                <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
                  <thead>

                    <tr>
                      <th scope="col">Institución</th>
                      <th scope="col">Productos</th>
                      <th scope="col">Fecha</th>
                      <th scope="col">Beneficiario</th>
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

          <!-- Modal delete-->

          <div class="modal fade" id="borrar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                  <button type="button" class="btn btn-danger" id="delete">Anular</button>
                </div>
              </div>
            </div>
          </div>


          <!-- MODAL DE PRODUCTOS -->
          <div class="modal fade" id="detalleDonacion" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-md">
              <div class="modal-content">
                <div class="modal-header alert alert-success">
                  <h5 class="modal-title"><strong id="DonacionTitle"></strong></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <table class="table table-hover">
                    <thead>
                      <th>Producto</th>
                      <th>Cantidad</th>
                    </thead>
                    <tbody id="bodyDetalle">

                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal" id="cerrarDetalles">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- FINAL MODAL DE PRODUCTOS -->

                  <!-- MODAL REGISTRAR DONACION -->
          <div class="modal fade" id="agregar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header alert alert-success">
                  <h3 class="modal-title"> <strong>Registrar Donacion por Institución</strong> </h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                  <form id = "agregarform">

                    <div class="form-group col-md-12">  
                      <div class="container-fluid">
                        <div class="row">

                          <div class="form-group col-md-6">                          
                            <label for="inputText" class="col-sm-3 col-form-label"><strong>Instituciones</strong></label>
                            <div class="input-group">
                              <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Seleccione una cédula registrada en el sistema."><i class="bi bi-person-fill"></i></button>
                              <select class="form-control select2" id="rif">
                                <option value="0" selected disabled>Rif Instituciones</option>
                              </select> 
                            </div>
                            <p class="error" style="color:#ff0000;text-align: center;" id="error1"></p>
                          </div>

                          <div class="form-group col-md-6">                          
                            <label class="col-form-label"> <strong>Beneficiario</strong> </label>
                            <div class="input-group">
                              <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Ingrese el beneficiario"><i class="bi bi-cash"></i></button> 
                              <input type="text" class="form-control" disabled id="beneficiario" >
                            </div>
                            <p class="error" style="color:#ff0000;text-align: center;" id="error2"></p>
                          </div>

                        </div>
                      </div>
                    </div> 

                    <div class="row">

                      <div class="form-group col-md my-3">  
                        <div class="container-fluid">
                          <div class="row">
                            <div class="table-responsive-md table-body form-group col-12">

                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th></th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                  </tr>
                                </thead>
                                <tbody id="ASD">
                                  <tr>
                                    <td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
                                    <td width='25%'> 
                                      <select class="select-productos select-asd" name="productos">
                                        <option></option>
                                      </select>
                                    </td>
                                    <td width='15%' class="cantidad"><input class="select-asd stock" type="number" value=""/></td>
                                  </tr>
                                </tbody>
                              </table>

                              <p class="filaProductos error" style="color:#ff0000;text-align: center;"></p>

                              <a class="newRow a-asd" href="#"><i class="bi bi-plus-circle-fill"></i> Nueva fila</a> <br>
                              <div class="text-end pt-4">
                                <p class="text-end" id="montos"></p>
                                <p class="text-end" id="montos2"></p>
                                <p class="text-end"id="cambio"></p>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>

                    </div>

                    <p id="pValid" class="error" style="color:#ff0000;text-align: center;"></p>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary cerrar" id="cerrar" data-bs-dismiss="modal">Cancelar</button>
                      <button type="submit" class="btn btn-registrar " id="registrar">Registrar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          

  </main>

</body>

<?php $VarComp->js();?>
<script src="assets/js/chosen.jquery.min.js"></script>
<script src="assets/js/select2.full.min.js"></script>
<script src="assets/js/donativoInstituciones.js"></script>
 
</html>