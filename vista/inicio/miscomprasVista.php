<!DOCTYPE html>
<html lang="en" >
<head>
  <title>MediSalud C.A</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <?php $VarComp->header(); ?>
  <link rel="stylesheet" href="assets/css/tienda.css">
</head>

<body class="body vh-100 w-100" id="body">

  <header class="">

    <!-- Barra navegadora -->
   <?php $Nav->nav(); ?>


  </header>

  <main class="w-100 d-flex justify-content-center p-5 carritoMain" style="margin-top: 76px"> 



           <!-- Card Table-->

           <div class="card">
            <div class="card-body">
              <div class="row">
              <h5 class="card-title col-6 ml-3">Mis compras</h5>
              </div>
              <!-- Table Inicio-->
              
              <div class="table-responsive">
                <table class="table table-bordered" id="tableMostrar" width="100%" cellspacing="0">
                  <thead>

                    <tr>
                      <th scope="col">Cedula</th>
                      <th scope="col">Productos</th>
                      <th scope="col">Fecha y Hora</th>
                      <th scope="col">Metodo de Pago</th>
                      <th scope="col">Total divisa</th>
                      <th scope="col">Total Bs</th>
                    </tr>
                  </thead>

                  <tbody id = "tbody">
                    
                  </tbody>
                </table>
              </div>
            

            </div>
          </div>
           
      </div>
    </div>

 </main>
</body>
  
<!-- MODAL DE PRODUCTOS -->
<div class="modal fade" id="detalleTipoPago" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable modal-md">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h5 class="modal-title"><strong id="ventaNombreTipoPago"></strong></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-hover">
          <thead>
            <th>Tipo de Pago</th>
            <th>Cantidad</th>
          </thead>
          <tbody id="bodyDetalleTipo">
            
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

<!-- MODAL DE PRODUCTOS -->
<div class="modal fade" id="detalleVenta" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable modal-md">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h5 class="modal-title"><strong id="ventaNombre"></strong></h5>
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
        <button type="button" class="btn btn-success factura" data-bs-dismiss="modal" id=" ">Exportar Factura</button>
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal" id="cerrarDetalles">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- FINAL MODAL DE PRODUCTOS -->

<div class="modal fade" id="Agregar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header alert alert-success">
        <h3 class="modal-title"> <strong>Registrar Venta</strong> </h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body ">
        <form id = "agregarform">

          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">

                <div class="form-group col-md-6">                          
                  <label for="inputText" class="col-sm-3 col-form-label"><strong>Cliente</strong></label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Seleccione una cédula registrada en el sistema."><i class="bi bi-person-fill"></i></button>
                    <select class="form-control select2" placeholder="Cédula" id="cedula">
                      <option value="0" selected disabled>Clientes</option>
                      <?php if(isset($mostrarC)){
                        foreach($mostrarC as $data){
                          ?> 
                          <option value="<?php echo $data->cedula;?>" class="opcion"><?php echo $data->nombre;?> <?php echo $data->apellido;?> <?php echo $data->cedula;?></option>
                          <?php
                        }
                      }else{"";}?>
                    </select> 
                  </div>
                  <p class="error" style="color:#ff0000;text-align: center;" id="error1"></p>
                </div>

                <div class="form-group col-md-6">                          
                  <label class="col-form-label"> <strong>Moneda</strong> </label>
                  <div class="input-group">
                    <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Evaluara el Total al valor de la moneda Seleccionada"><i class="bi bi-currency-exchange"></i></button> 
                    <select class="form-select select2M" id="moneda">
                      <option selected disabled>Moneda</option>
                    </select>
                  </div>
                  <p class="error" style="color:#ff0000;text-align: center;" id="error5"></p>
                </div>

              </div>
            </div>
          </div> 

          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">



              <div class="form-group col-md-6">  
                <label class="col-form-label" for="config_iva"><strong>IVA</strong></label>
                <div class="input-group">
                  <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca un IVA para la venta">%</button> 
                  <input class="form-control iva" type="text" id="config_iva" value="16"/>
                </div>
                <p class="error" style="color:#ff0000;text-align: center;" id="error4"></p>
              </div>


              <div class="form-group col-md-6">                          
                <label class="col-form-label"> <strong>Monto</strong> </label>
                <div class="input-group">
                  <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Monto total de la venta"><i class="bi bi-cash"></i></button> 
                  <input type="number" class="form-control" disabled="disabled" id="monto" >
                </div>
                <p class="error" style="color:#ff0000;text-align: center;" id="error3"></p>
              </div>

            </div>
          </div>
        </div>
        
        <div class="row">

          <div class="form-group col-md-5 h-75">  
            <div class="container-fluid">
              <div class="row">
                <div class="table table-body-tipo form-group col-12">

                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Tipo Pago</th>
                        <th>Precio</th>
                      </tr>
                    </thead>
                    <tbody id="FILL">
                      <tr>
                        <td width="1%"><a class="removeRowPagoTipo a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
                        <td width='30%'> 
                          <select class="select-tipo select-asd" name="TipoPago">
                            <option></option>
                          </select>
                        </td>
                </div>
              </div>
            </div>

          </div>

        <div class="form-group col-md-7">  
          <div class="container-fluid">
            <div class="row">
              <div class="table table-body form-group col-12">

                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Producto</th>
                      <th>Cantidad</th>
                      <th>Precio</th>
                      <th>IVA</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody id="ASD">
                    <tr>
                      <td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
                      <td width='30%'> 
                        <select class="select-productos select-asd" name="productos">
                          <option></option>
                        </select>
                      </td>
                      <td width='10%' class="amount"><input class="select-asd stock" type="number" value=""/></td>
                      <td width='10%' class="rate"><input class="select-asd" type="number" disabled value="" /></td>
                      <td width='10%'class="tax"></td>
                      <td width='10%' class="sum"></td>
                    </tr>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>

      </div>

      <p class="error" style="color:#ff0000;text-align: center;" id="error"></p>

    

 
<?php $VarComp->js(); ?>
<script src="assets/js/chosen.jquery.min.js"></script>
<script src="assets/js/select2.full.min.js"></script>
<script src="assets/js/miscompras.js"></script>
</html>

             