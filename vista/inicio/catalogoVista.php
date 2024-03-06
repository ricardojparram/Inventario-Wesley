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

  <main class="parent" style="margin-top: 76px; min-height: 500px;"> 
    <!-- <section class="col-12 p-5"> -->
      <!-- <header>
        <h1>Catálogo</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Catálogo</li>
            <li class="breadcrumb-item active" aria-current="page">Analgésicos</li>
          </ol>
        </nav>
      </header> -->
    <!-- </section> -->
    <nav class="filtros px-5 py-4">
      <div class="row">
        <div class="col-12 col-md-10 d-flex row">
          <h5 class="w-auto col-12 col-md-2">Categorías: </h5>
          <div class="categorias_catalogo col-12 col-md-10"></div>
        </div>
        <div class="col-12 col-md-2">
          <input type="search" class="form-control buscarProducto" placeholder="Buscar...">
        </div>

      </div>
    </nav>
    <section class="col-12">
      <div class="row mx-auto align-items-center catalogoProductos" style="padding: 0 25px">

      </div>
    </section>

  </main>

  <?php $footer->footer(); ?>

  <?php $tiendaComp->car(); ?>

  <?php $VarComp->js() ?>
  <script src="assets/js/carrito.js"></script>
  <script src="assets/js/catalogo.js"></script>

</body>

</html>

<!-- Modal para confimar la elimińación de un producto del carrito -->
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

<!-- Modal para añadir productos al carrito -->
<div class="modal fade" id="AñadirCarritoModal" style="transition: all 0.4s ease-in-out 0s;" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-8">
        <div class="position-absolute top-0 end-0 me-3 mt-3">
          <button type="button" class="btn-close cerrar" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="row">
          <div class="col-lg-6 row justify-content-center align-items-center">
            <!-- img slide -->
              <img class=".img-fluid producto_imagen_modal" src="assets/img/productos/producto_imagen.png" alt="producto_imagen">
          </div>
          <div class="col-lg-6">
            <div class="ps-lg-8 mt-6 mt-lg-0">
              <a href="#!" class="mb-4 d-block tipo_medicamento">Antiinflamatorio</a>
              <h2 class="mb-1 h1 nombre_medicamento">Diclofenac potásico</h2>
              <h6 class="descripcion_medicamento"></h6>
              <div class="mb-4">
                <small class="text-warning">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-half"></i></small><a href="#" class="ms-2"></a>
              </div>
              <div class="fs-4 precios">
                <span class="fw-bold text-dark ">Bs. <span class="precio_bs">120.00</span></span>
                <span class="text-muted" >$. <span class="precio_dolar">  </span></span>
              </div>
              <hr class="my-6">
              <div class="opciones row">
                <div class="input-group cantidad_producto col-6" style="max-width: 180px;">
                  <button class="btn btn-light menos"><i class="bi bi-dash-lg"></i></button>
                  <input type="number" id="cantidad_añadir_producto" min="1" max="50" value="1" class="form-control form-input">
                  <button class=" btn btn-light mas"><i class="bi bi-plus-lg"></i></button>
                </div>
                <div class="invalid-tooltip-prod col-6" style="">Cantidad no disponible.</div>
              </div>
              <div class="mt-3 row justify-content-start g-2 align-items-center">

                <div class="col-lg-4 col-md-5 col-6 d-grid">
                  <button type="button" id="añadir_al_carrito" class="btn btn-success">
                    Agregar al carrito <i class="bi bi-cart-plus-fill"></i>
                  </button>
                </div>
              </div>
              <hr class="my-6">
              <div>
                <table class="table table-borderless">
                  <tbody>
                    <tr>
                      <td>Código de producto:</td>
                      <td class="codigo_producto"></td>
                    </tr>
                    <tr>
                      <td>Tipo:</td>
                      <td class="tipo_producto"></td>
                    </tr>
                    <tr>
                      <td>Contraindicaciones:</td>
                      <td class="contraindicaciones">
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>