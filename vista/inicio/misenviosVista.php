<!DOCTYPE html>
<html lang="en">

<head>
  <title>MediSalud C.A</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <?php $VarComp->header(); ?>
  <link rel="stylesheet" href="assets/css/tienda.css">
</head>

<body class="body" id="body">

  <header class="w-100 h-100">
    
  <!-- Barra navegadora -->
    <?php $Nav->nav(); ?>

  </header>

   <main class="w-100"> 
   <div id="carrusel" class=" d-flex justify-content-center align-items-center ">
          <div id="carouselExampleCaptions" class="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active bg-black" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2" class="bg-black"></button>
              <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3" class="bg-black"></button>
            </div>
            <div class="carousel-inner ">
             
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>

   <br><br><br><br><br>
 <h1 class="title-pedi" style="text-align: center;"> <font color="#233d20"><b>Mis envíos</b></font></h1>
<br><br>
  <div class="card">
            <div class="card-body">
              <div class="row">
             


      
              <!-- Table Inicio-->
              <div class="form-group col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered" id="tableMostrar" width="100%" cellspacing="0">
                  <thead>

                    <tr>
                      <th scope="col">Cedula</th>
                    
                      <th scope="col">Fecha y Hora</th>
                      <th scope="col">Metodo de Pago</th>
                      <th scope="col">Total divisa</th>
                      <th scope="col">Total Bs</th>
                      <th scope="col">Opciones</th>
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
  
  


  <?php $Car->car(); ?>

  <?php $VarComp->js() ?>
  <script src="assets/js/carrito.js"></script>
  <script src="assets/js/chosen.jquery.min.js"></script>
  <script src="assets/js/select2.full.min.js"></script>
  <script src="assets/js/misenvios.js"></script>

  <script>
    
    const myCarouselElement = document.querySelector('#carouselExampleCaptions')

    const carousel = new bootstrap.Carousel(myCarouselElement, {
      interval: 1000,
       touch: false
    })

  </script>
</body>

</html>

<!-- Modal eliminar producto del carrito -->
<div class="modal fade" id="delModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Desea eliminar el producto <b id="delProductTitle"></b> del carrito?</h5>
      </div>
      <div class="modal-footer" id="divEli">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarModalDel">Cancelar</button>
        <button type="button" class="btn btn-danger" id="delProductFromCar">Confirmar</button>
      </div>
    </div>
  </div>
</div>

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
