<!DOCTYPE html>
<html lang="en">

<head>
  <title>MediSalud C.A - Carrito de Compras</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <?php $VarComp->header(); ?>
  <link rel="stylesheet" href="assets/css/tienda.css">
</head>

<body class="body vh-100 w-100" id="body">

  <header class="">

    <!-- Barra navegadora -->
    <?php $tiendaComp->nav(); ?>

  </header>

  <main class="w-100 d-flex justify-content-center p-5 carritoMain" style="margin-top: 76px"> 

    <div class="page-container">
      <div class="pagetitle">
        <h1>Carrito de compras</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Artículos agregados para comprar</li>
          </ol>
        </nav>
      </div>
      <div class="cardContainer">
        <div class="card">
          <div class="card-body">
            <div class="row pt-4 px-4">
              <div class="col-6 p-0 fs-4">
                <a class="carritoButton regresar" href="?url=inicio"><i class="bi bi-arrow-bar-left fs-3"></i></a>
              </div>

              <div class="col-6 p-0 fs-4 text-end">
                <a class="carritoButton vaciar"><i class="bi bi-cart-x fs-3"></i></a>
              </div>
            </div>
            <div class="row justify-content-center p-4 carrito-container">

            </div>
          </div>
        </div>
        <div class="card cardTotal">
          <div class="card-body p-4">
            <h4>Precio total del carrito</h4>
            <h3>Bs. <span id="precioTotal"></span></h3>
            <h3 class="text-muted" >$. <span id="precioDolar"></span></h3>

            
            <button class="btn btn-success" id="realizarFacturacion"><i class="bi bi-cart-check-fill"></i> Realizar facturación</button>
          </div>
        </div>
      </div>

    </div>


  </main>

  <?php $footer->footer(); ?>


  <?php $VarComp->js() ?>
  <script src="assets/js/carrito.js"></script>
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

<div class="modal fade" id="vaciarCarritoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="staticBackdropLabel">¿Estás seguro?</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Se vaciará su carrito por completo.</h5>
      </div>
      <div class="modal-footer" id="divEli">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarModalDel">Cancelar</button>
        <button type="button" class="btn btn-danger" id="vaciarCarritoConfirm">Confirmar</button>
      </div>
    </div>
  </div>
</div>