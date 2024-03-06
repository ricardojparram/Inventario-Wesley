<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
   <?php $VarComp->header(); ?>
   <link rel="stylesheet" href="assets/css/cropper.css"/>
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
          <li class="breadcrumb-item"><h1>Productos</h1></li>
        </ol>
      </nav>

    </div>

    <div class="card">
      <div class="card-body">
        
        <div class="row">
          <div class="col-6">
            <h5 class="card-title">Listado de Productos</h5>
          </div>

          <div class="col-6 text-end mt-3">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#basicModal">Agregar</button>
          </div>
        </div>



        <div class="table-responsive">
          <table class="table table-bordered" id="tableMostrar" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th scope="col">Descripción</th>
                <th scope="col">Cant.</th>
                <th scope="col">P.Venta</th>
                <th scope="col">Tipo</th>
                <th scope="col">Vencimiento</th>
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

   <!-- End Main-->

  <!-- Modal Registrar-->

      <div class="modal fade" id="basicModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header alert alert-success">
              <h3 class="modal-title"> <strong>Registrar Producto</strong> </h3>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body ">
              <form id="agregarform">

                <div class="form-group col-md-12">  
                  <div class="container-fluid">
                    <div class="row">



                      <div class="form-group col-lg-4">
                        <label class="col-form-label"> <strong>Código del producto</strong> </label>
                        <div class="input-group">
                         <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="ri-capsule-fill"></i></button> 
                         <input id="codigo" class="form-control" placeholder="código">
                       </div>
                      <p class="error" id="error" style="color: red"></p>
                     </div>

                     <div class="form-group col-lg-4">
                        <label class="col-form-label"> <strong>Descripción del Producto</strong> </label>
                        <div class="input-group">
                         <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="ri-capsule-fill"></i></button> 
                         <input id="descripcion" class="form-control" placeholder="descripcion del producto">
                       </div>
                      <p class="error" id="error3" style="color: red"></p>
                     </div>


                     <div class="form-group col-lg-4">
                      <label class="col-form-label"> <strong>Vencimiento</strong> </label>
                      <div class="input-group">
                       <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=" Descripción "><i class="bi bi-calendar"></i></button> 
                       <input type="date" id="fecha" class="form-control">
                     </div>
                    <p class="error" id="error4" style="color: red"></p> 
                   </div>

                 </div>
               </div>
             </div>


             <div class="form-group col-md-12">  
               <div class="container-fluid">
                 <div class="row">

                  <div class="form-group col-lg-4">
                    <label class="col-form-label"> <strong>Composición del producto</strong> </label>
                    <div class="input-group">
                     <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="ri-capsule-line"></i></button>
                     <input class="form-control" id="composición" placeholder="Composición del producto">
                   </div>
                   <p class="error" id="error5" style="color: red"></p> 
                 </div>

                 <div class="form-group col-lg-4">
                  <label class="col-form-label"> <strong>Posología</strong> </label>
                  <div class="input-group">
                   <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bi bi-clock"></i></button>
                   <input class="form-control" id="posologia" placeholder="posologia">
                 </div> 
                 <p class="error" id="error6" style="color: red"></p> 
               </div>

               <div class="form-group col-lg-4">
                <label class="col-form-label"> <strong>Ubicación</strong> </label>
                <div class="input-group">
                 <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class=" ri-map-2-line"></i></button>
                 <select class="form-control" aria-label="Default select example" id="ubicación">
                  <option value="" selected="" >Seleccione una opción</option>
                  <option value="Pasillo 1">Pasillo 1</option>
                  <option value="Pasillo 2">Pasillo 2</option>
                  <option value="Pasillo 3">Pasillo 3</option>
                  <option value="Pasillo 3">Pasillo 4</option>

                </select>
              </div>
              <p class="error" id="error7" style="color: red"></p> 
            </div>

          </div>
        </div>
      </div>

      <div class="form-group col-md-12">  
        <div class="container-fluid">
          <div class="row">

            <div class="form-group col-lg-3">
              <label class="col-form-label"> <strong>Laboratorio</strong></label>
              <div class="input-group">
               <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class=" ri-flask-fill"></i></button>
               <select class="form-control" aria-label="Default select example" id="laboratorio">
                <option selected disabled>Seleccione una opción</option>
                <?php if(isset($mostraLab)){
                 foreach($mostraLab as $data){
                   ?> 
                   <option value="<?php echo $data->cod_lab; ?>" class="opcion"><?php echo $data->razon_social; ?></option>
                   <?php
                 }
               }else{"";}?>

             </select>
           </div>
           <p class="error" id="error8" style="color: red"></p> 
         </div>


         <div class="form-group col-lg-3">
          <label class="col-form-label"> <strong>Presentación</strong> </label>
          <div class="input-group">
           <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bxs-capsule"></i></button>
           <select class="form-control" aria-label="Default select example" id="presentación">
             <option selected disabled>Seleccione una opción</option>
             <?php if(isset($mostraPres)){
               foreach($mostraPres as $data){
                 ?> 
                 <option value="<?php echo $data->cod_pres; ?>" class="opcion"><?php echo $data->cantidad; echo $data->medida; ?></option>
                 <?php
               }
             }else{"";}?>

           </select>
         </div> 
         <p class="error" id="error9" style="color: red"></p> 
       </div>

       <div class="form-group col-lg-3">
        <label class="col-form-label"> <strong>Tipo de producto</strong> </label>
        <div class="input-group">
          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx bxs-bong"></i></button>
          <select class="form-control" aria-label="Default select example" id="tipoP">
            <option selected disabled>Seleccione una opción</option>
            <?php if(isset($mostraTipo)){
             foreach($mostraTipo as $data){
               ?> 
               <option value="<?php echo $data->cod_tipo; ?>" class="opcion"><?php echo $data->des_tipo; ?></option>
               <?php
             }
           }else{"";}?>

         </select>
       </div>
       <p class="error" id="error10" style="color: red"></p> 
     </div>

     <div class="form-group col-lg-3">
       <label class="col-form-label"> <strong>Clase</strong> </label>
       <div class="input-group">
        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bxs-capsule"></i></button>
        <select class="form-control" aria-label="Default select example" id="clase">
          <option selected disabled>Seleccione una opción</option>
          <?php if(isset($mostrarClase)){
           foreach($mostrarClase as $data){
             ?> 
             <option value="<?php echo $data->cod_clase; ?>" class="opcion"><?php echo $data->des_clase; ?></option>
             <?php
           }
         }else{"";}?>

       </select>
     </div>
     <p class="error" id="error11" style="color: red"></p>  
    </div>

    </div>
    </div>
    </div>


    <div class="form-group col-md-12">  
      <div class="container-fluid">
        <div class="row">



          <div class="form-group col-lg-4">
            <label class="col-form-label"><strong>Contraindicaciones</strong></label>
            <div class="input-group">
             <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bx-no-entry"></i></button>
             <input class="form-control" id="contraIn" placeholder="text">
           </div>
           <p class="error" id="error12" style="color: red"></p> 
         </div>

         <div class="form-group col-lg-4">
          <label class="col-form-label"> <strong>Cantidad</strong> </label>
          <div class="input-group">
           <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bi bi-sort-up"></i></button>
           <input type="number" class="form-control" id="cantidad" placeholder="cantidad">
         </div>
         <p class="error" id="error13" style="color: red"></p> 
       </div> 

       <div class="form-group col-lg-4">
         <label class="col-form-label"> <strong> Precio de venta</strong></label>
         <div class="input-group">
          <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bi bi-cash-coin"></i></button>
          <input  class="form-control" id="precioV" placeholder="Precio de venta">
        </div>
        <p class="error" id="error14" style="color: red"></p> 
      </div>
      </div>
  </div>
</div>

       
        


    </div>
    <p id="error" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta))? $respuesta : " " ?></p>
    <div class="modal-footer">
      <button id="cerrar" type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
      <button id="boton" type="button" class="btn btn-success">Registrar</button>
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

      <div class="modal-body ">
        <form id="editarform">

          <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">
                <div class="form-group col-lg-4">
                  <label class="col-form-label"> <strong>Código del producto</strong> </label>
                  <div class="input-group">
                   <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="ri-capsule-fill"></i></button> 
                   <input id="codigoEd" class="form-control" placeholder="codigo">
                 </div>
                 <p class="error" id="errorE2" style="color: red"></p>
               </div>

               <div class="form-group col-lg-4">
                <label class="col-form-label"> <strong>Descripción del Producto</strong> </label>
                <div class="input-group">
                 <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="ri-capsule-fill"></i></button> 
                 <input name="descripcionEd" id="descripcionEd" class="form-control" placeholder="descripcion del producto">
               </div>
               <p class="error" id="errorE3" style="color: red"></p> 
             </div>


             <div class="form-group col-lg-4">
              <label class="col-form-label"> <strong>Vencimiento</strong> </label>
              <div class="input-group">
               <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=" Descripción "><i class="bi bi-calendar"></i></button> 
               <input type="date" name="fechaEd" id="fechaEd" class="form-control">
             </div>
             <p class="error" id="errorE4" style="color: red"></p> 
           </div>

           <div class="form-group col-md-12">  
            <div class="container-fluid">
              <div class="row">



                <div class="form-group col-lg-4">
                  <label class="col-form-label"> <strong>Composición del producto</strong> </label>
                  <div class="input-group">
                   <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="ri-capsule-line"></i></button>
                   <input class="form-control" name="composicionEd" id="composicionEd" placeholder="Composición del producto">
                 </div>
                 <p class="error" id="errorE5" style="color: red"></p> 
               </div>

               <div class="form-group col-lg-4">
                <label class="col-form-label"> <strong>Posología</strong> </label>
                <div class="input-group">
                 <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bi bi-clock"></i></button>
                 <input class="form-control" name="posologiaEd" id="posologiaEd" placeholder="posologia">
               </div> 
               <p class="error" id="errorE6" style="color: red"></p> 
             </div>

             <div class="form-group col-lg-4">
              <label class="col-form-label"> <strong>Ubicación</strong> </label>
              <div class="input-group">
               <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class=" ri-map-2-line"></i></button>
               <select class="form-control" aria-label="Default select example" id="ubicaciónEd">
                <option value="" selected="" >Seleccione una opción</option>
                <option value="Pasillo 1">Pasillo 1</option>
                <option value="Pasillo 2">Pasillo 2</option>
                <option value="Pasillo 3">Pasillo 3</option>

              </select>
            </div>
            <p class="error" id="errorE7" style="color: red"></p> 
          </div>

        </div>
      </div>
    </div>



    <div class="form-group col-md-12">  
      <div class="container-fluid">
        <div class="row">

          <div class="form-group col-lg-3">
            <label class="col-form-label"> <strong>Laboratorio</strong> </label>
            <div class="input-group">
             <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class=" ri-flask-fill"></i></button>
             <select class="form-control" aria-label="Default select example" name="laboratorioEd" id="laboratorioEd">
              <option selected disabled>Seleccione una opción</option>
              <?php if(isset($mostraLab)){
               foreach($mostraLab as $data){
                 ?> 
                 <option value="<?php echo $data->cod_lab; ?>" class="opcion"><?php echo $data->razon_social; ?></option>
                 <?php
               }
             }else{"";}?>

           </select>
         </div>
         <p class="error" id="errorE8" style="color: red"></p> 
       </div>

       <div class="form-group col-lg-3">
        <label class="col-form-label"> <strong>Presentación</strong> </label>
        <div class="input-group">
         <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bxs-capsule"></i></button>
         <select class="form-control" aria-label="Default select example" name="presentaciónEd" id="presentaciónEd">
           <option selected disabled>Seleccione una opción</option>
           <?php if(isset($mostraPres)){
             foreach($mostraPres as $data){
               ?> 
               <option value="<?php echo $data->cod_pres; ?>" class="opcion"><?php echo $data->cantidad; echo $data->medida; ?></option>
               <?php
             }
           }else{"";}?>

         </select>
       </div>
       <p class="error" id="errorE9" style="color: red"></p>  
     </div>

     <div class="form-group col-lg-3">
      <label class="col-form-label"> <strong>Tipo de producto</strong> </label>
      <div class="input-group">
        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx bxs-bong"></i></button>
        <select class="form-control" aria-label="Default select example" name="tipoEd" id="tipoEd">
          <option selected disabled>Seleccione una opción</option>
          <?php if(isset($mostraTipo)){
           foreach($mostraTipo as $data){
             ?> 
             <option value="<?php echo $data->cod_tipo; ?>" class="opcion"><?php echo $data->des_tipo; ?></option>
             <?php
           }
         }else{"";}?>

       </select>
     </div>
     <p class="error" id="errorE10" style="color: red"></p> 
   </div>

   <div class="form-group col-lg-3">
    <label class="col-form-label"> <strong>Clase</strong> </label>
    <div class="input-group">
     <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bxs-capsule"></i></button>
     <select class="form-control" aria-label="Default select example" id="claseEd">
       <option selected disabled>Seleccione una opción</option>
       <?php if(isset($mostrarClase)){
         foreach($mostrarClase as $data){
           ?> 
           <option value="<?php echo $data->cod_clase; ?>" class="opcion"><?php echo $data->des_clase; ?></option>
           <?php
         }
       }else{"";}?>

     </select>
   </div>
   <p class="error" id="errorE11" style="color: red"></p>  
 </div>


</div>
</div>
</div>


<div class="form-group col-md-12">  
  <div class="container-fluid">
    <div class="row">

      <div class="form-group col-lg-4">
        <label class="col-form-label"><strong>Contraindicaciones</strong></label>
        <div class="input-group">
         <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bx  bx-no-entry"></i></button>
         <input class="form-control" name="contraInEd" id="contraInEd" placeholder="text">
       </div>
       <p class="error" id="errorE12" style="color: red"></p> 
     </div>

     <div class="form-group col-lg-4">
      <label class="col-form-label"> <strong>Cantidad</strong> </label>
      <div class="input-group">
       <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bi bi-sort-up"></i></button>
       <input type="number" class="form-control" name="cantidadEd" id="cantidadEd" placeholder="cantidad">
     </div>
     <p class="error" id="errorE13" style="color: red"></p> 
   </div> 

   <div class="form-group col-lg-4">
     <label class="col-form-label"> <strong> Precio de venta</strong></label>
     <div class="input-group">
      <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="  Descripción "><i class="bi bi-cash-coin"></i></button>
      <input  class="form-control" name="VentaEd" id="VentaEd" placeholder="Precio de venta">
    </div>
    <p class="error" id="errorE14" style="color: red"></p> 
  </div>

</div>
</div>
</div>




</div>
</div>
</form>
</div>

<p id="error" class="error" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta))? $respuesta : " " ?></p>
<div class="modal-footer">
  <button id="Cancelar" type="reset" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
  <button id="actualizar" type="button" class="btn btn-success">Actualizar</button>
</div>

</div>
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
        <h5>Los datos serán anulados del sistema.</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal" id="close">Cancelar</button>
        <button type="button" class="btn btn-danger" id="delete">Anular</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal delete Final-->

</body>
<?php $VarComp->js();?>
<script src="assets/js/cropper.min.js"></script>
<script src="assets/js/producto.js"></script>
</html>

<!-- Modal cambiar imagen -->
<div class="modal fade" id="infoImg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-50" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
          
        <p id="error" class="error" style="color:#ff0000;text-align: center;"><?php echo (isset($respuesta))? $respuesta : " " ?></p>

        <div class="modal-footer">
          <button id="Cancelar" type="reset" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cancelar</button>
          <button id="actualizarImg" type="button" class="btn btn-success">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Final modal editar imagen -->

<!-- Modal croppear imagen -->
<div class="modal fade" id="fotoModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" >
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
</div>
<!-- Final modal croppear imagen -->