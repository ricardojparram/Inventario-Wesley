<!DOCTYPE html>
<html lang="en">

<head>
  <title>Centro Médico Wesley</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <?php $VarComp->header(); ?>
  <link rel="stylesheet" href="assets/css/tienda.css">
</head>

<body>

  <header class="w-100 h-100">

    <!-- Barra navegadora -->
    <?php $tiendaComp->nav(); ?>

  </header>
  <section class="presentacion d-flex justify-content-center align-items-center">
    <div class="container container-md row text-white">
      <div class="col-12 col-md-6 row align-items-center">
        <div>
          <h1 class="fw-bold">Fundación Centro Médico Wesley</h1>
          <p class="fs-6" style="text-align: justify;"><strong>Somos un centro médico dedicado a brindar atención de calidad a nuestros clientes, con un enfoque en la comodidad y la conveniencia. Nos enorgullecemos de ofrecer una amplia gama de servicios médicos, desde consultas regulares hasta procedimientos especializados, todo en un ambiente cálido y acogedor.</strong></p>
          <span class="row gap-4 align-items-center justify-content-center">
            <a href="nosotros" class="btn btn-light col-6">Más informacion</a>
          </span>

        </div>
      </div>
      <div class="col-12 col-md-6 row align-items-center justify-content-center pt-5">
        <img class="img-fluid " src="assets/img/presentacion-imagen.svg" alt="Imagen vectorizada de farmacia">
      </div>

    </div>
  </section>

  <main class="w-100">


    <!-- ======= Services Section ======= -->

    <section class="row gap-4 servicios mt-5">
      <header>
        <h2 class="text-center fs-2 fw-bold titulos">Servicios</h2>
        <p class="text-center fs-5 fw-bold">Ofrecemos una amplia gama de productos, incluyendo las siguientes presentaciones.</p>
      </header>

      <div class="row">

        <div class="col-xl-4 text-center">
          <img src="assets/img/servicios-img.svg" class="img-fluid p-4" alt="">
        </div>

        <div class="col-xl-8 d-flex content">
          <div class="row align-self-center gy-4">

            <div class="col-md-6 icon-box" data-aos="fade-up">
              <i class="ri-capsule-fill"></i>
              <div>
                <h4>Tabletas y Cápsulas</h4>
                <p>Son formas sólidas de administración de medicamentos que contienen una dosis precisa de ingredientes activos. Son convenientes y efectivas, ya que se pueden tragar fácilmente y se absorben rápidamente en el torrente sanguíneo.</p>
              </div>
            </div>

            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
              <i class="ri-medicine-bottle-fill"></i>
              <div>
                <h4>Jarabes</h4>
                <p>Son medicamentos líquidos que contienen ingredientes activos en una base de agua y azúcar. Son una forma conveniente de administrar medicamentos, especialmente para niños, ya que son fáciles de tragar y suelen tener un buen sabor.</p>
              </div>
            </div>

            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
              <i class="ri-syringe-fill"></i>
              <div>
                <h4>Inyecciones</h4>
                <p>Son una forma líquida de medicamentos administrados directamente al cuerpo a través de una aguja y una jeringa. Son una forma rápida y efectiva de administrar medicamentos, pero pueden llegar a ser dolorosas.</p>
              </div>
            </div>

            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
              <i class="ri-hand-sanitizer-fill"></i>
              <div>
                <h4>Loción</h4>
                <p>Es una forma líquida de medicamento que se aplica sobre la piel para tratar afecciones dermatológicas. Éstos medicamentos son efectivos, ya que pueden llegar a las capas profundas de la piel cuando ésta las absorbe.</p>
              </div>
            </div>

          </div>
        </div>

      </div>

    </section>
    <!-- ====== Service section end -->

    <!-- ====== Productos section ====== -->
    <section class="container-fluid my-4 p-0">
      <div class="productos-head w-100">
        <header class="text-white py-3">
          <h2 class="text-center fs-2 p-3 mt-3 fw-bold">Productos</h3>
            <p class="fs-5 text-center fw-bold">Éstos son los distintos tipos de medicamentos que tenemos a la venta.</p>
        </header>
        <div class="categorias">
          <div id="categoriaSlider" class="carousel slide carousel-fade">
            <div class="carousel-inner">



            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#categoriaSlider" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#categoriaSlider" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>

        </div>
      </div>
      <!--- Productos listados -->
      <div class="productos-populares">
        <h3 class="w-100 text-center fs-3 fw-bold mb-4">Los productos más comprados en nuestra tienda</h3>
        <div class="row mx-auto justify-content-center align-items-center" id="catalogo">

        </div>
      </div>
      <!--- Productos listados end -->

      <!--- Botón de ir al catálogo -->
      <div class="w-100 mt-4 mb-5 d-flex justify-content-center">
        <a class="btn btn-lg btn-outline-success ir_catalogo" href="catalogo"><strong>Ir al catálogo </strong><span><i class="bi bi-arrow-right"></i></span></a>
      </div>
      <!--- Botón de ir al catálogo end -->

    </section>
    <!-- ====== Productos section end ====== -->

  </main>

  <?php $footer->Footer(); ?>

  <?php $VarComp->js() ?>
  <script src="assets/js/inicio.js"></script>
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
                &nbsp;&nbsp;
                <span class="text-muted"><span class="precio_dolar"></span>$</span>
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