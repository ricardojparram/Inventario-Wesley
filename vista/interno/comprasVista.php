<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras</title>
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


      <main id="main" class="main">

        <div class="pagetitle">
          <h1>Compras</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Compras</li>
            </ol>
          </nav>
        </div>


        <section class="section">
          <div class="row">
           <div class="col-lg-12">


             <div class="card">
              <div class="card-body">

                <div class="row">
                  <div class="col-6">
                    <h5 class="card-title">Compras</h5>
                  </div>

                  <div class="col-6 text-end mt-3">
                    <button type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#Agregar">Agregar</button>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-bordered table-hover" id='tableMostrar' width="100%" cellspacing="0">
                    <thead>

                      <tr>
                        <th scope="col">Orden Compra</th>
                        <th scope="col">Proveedor</th>
                        <th scope="col">Productos</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Total Bs</th>
                        <th width="1%" scope="col">Opciones</th>
                      </tr>

                    </thead>

                    <tbody id='tbody'>

                    </tbody>
                  </table>
                </div>
                

              </div>
            </div>

          </div>
        </div>
      </section>
    </main>

    </body>

    <!-- MODAL DE REGISTRAR -->

<div class="modal fade" id="Agregar" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h4 class="modal-title"> <strong>Registrar Compra</strong> </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body ">

        <form id = "agregarform">

          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">

                <div class="form-group col-6">                          
                  <label class="col-form-label"> <strong>Proveedor</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el proveedor"><i class="bi bi-card-image"></i></button> 
                    <select class="form-control"  id="proveedor"> 
                      <option></option>
                      <?php if(isset($proveedores)){
                        foreach($proveedores as $data){
                         ?> 
                         <option value="<?php echo $data->rif_proveedor; ?>" class="opcion"><?php echo $data->razon_social; ?></option>
                         <?php
                       }
                     }else{"";}?>
                    </select>
                  </div>
                  <p class="error" style="color:#ff0000;text-align: center;" id="error1"></p>
                </div>




                <div class="form-group col-6">                          
                  <label class="col-form-label"> <strong>Orden Compra</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el orden de compra correspondiente"><i class="bi bi-envelope"></i></button> 
                    <input class="form-control"  id="orden" placeholder="">
                  </div>
                  <p class="error" style="color:#ff0000;text-align: center;" id="error2"></p>
                </div>

              </div>
            </div>
          </div>



          
          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">

                <div class="form-group col-6">                          
                  <label class="col-form-label"> <strong>Fecha</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca la fecha de la compra"><i class="bi bi-calendar"></i></button> 
                    <input type="date"class="form-control"  id="fecha" required="" placeholder="">
                  </div>
                  <p class="error" style="color:#ff0000;text-align: center;" id="error3"></p>
                </div>




                <div class="form-group col-6">                          
                  <label class="col-form-label"> <strong>Monto</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el monto total de la compra"><i class="bi bi-cash"></i></button> 
                    <input type="number" class="form-control" disabled="disabled" id="monto" >
                  </div>
                  <p class="error" style="color:#ff0000;text-align: center;" id="error4"></p>
                </div>

              </div>
            </div>
          </div>

          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">
                <div class="table-body table-responsive-sm form-group col-12">

                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Vencimiento</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody id="ASD">
                      <tr>
                        <td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
                        <td width='20%'> 
                          <select class="select-productos select-asd" name="productos">
                            <option></option>
                          </select>
                        </td>
                        <td width='10%' class="lote"><input class="select-asd" type="number" value=""/></td>
                        <td width='10%' class="cantidad"><input class="select-asd" type="number" value=""/></td>
                        <td width='10%' class="precio"><input class="select-asd" type="number" value="" /></td>
                        <td width='10%'class="vencimiento"><input class="select-asd" type='text' /></td>
                        <td width='10%' class="total"></td>
                      </tr>
                    </tbody>
                  </table>
                  <p class="filaProductos error" style="color:#ff0000;text-align: center;"></p>
                  <a class="newRow a-asd" href="#"><i class="bi bi-plus-circle-fill"></i> Nueva fila</a> <br>
                  <br>
                </div>
                <div class="text-end">
                  <p id="lote"></p> 
                  <p id="monto"></p>
                  <p id="cambio"></p>
                </div>
              </div>
            </div>
          </div>

        </div>

        <p style="color:#ff0000;text-align: center;" id="error"></p>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cerrar" id="cancelar" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-registrar " id="registrar">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- FINAL DE MODAL DE REGISTRAR -->


<!-- MODAL DE ELIMINAR -->

<div class="modal fade" id="Borrar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Los datos serán eliminados del sistema.</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="borrar">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<!-- FINAL DE MODAL DE ELIMINAR -->

<!-- MODAL DE PRODUCTOS -->
<div class="modal fade" id="detalleCompra" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable modal-md">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h5 class="modal-title"><strong id="compraNombre"></strong></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-hover">
          <thead>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
          </thead>
          <tbody id="bodyDetalle">
            
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal" id="cerrarDetalles">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- FINAL MODAL DE PRODUCTOS -->

<?php $VarComp->js();?>
<script src="assets/js/chosen.jquery.min.js"></script>
<script src="assets/js/compras.js"></script>
</html>


