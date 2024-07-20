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
  <section class="d-flex justify-content-center align-items-center vh-100">
    <div class="container container-md row">
      <div class="col-12 col-md-6 row align-items-center">
        <div class="w-100">
          <h1 class="fw-bold fs-3 titulos text-justify">Fundación Centro Médico Wesley.</h1>
          <p class="fw-semibold descripcion" style="font-size:16px">Nuestra institución se dedica a ofrecer un servicio integral de salud con excelencia y una profunda vocación de servicio. Contamos con un equipo de profesionales altamente cualificados en cada una de nuestras áreas de atención, garantizando la calidad y la autosustentabilidad económica de nuestro centro. Aspiramos a ser la primera institución de salud integral en Venezuela, destacándonos por nuestro alto nivel de excelencia en servicios médicos y transformando positivamente la imagen de la atención hospitalaria en el país.
          </p>

        </div>
      </div>
      <div class="col-12 col-md-6 row align-items-center justify-content-center">
        <img class="img-fluid" src="assets/img/nosotros_svg_1.svg" alt="Imagen vectorizada de farmacia">
      </div>

    </div>
  </section>

  <main class="w-100">


    <!-- ======= Services Section ======= -->
    <section class="d-flex justify-content-center align-items-center vh-100">
      <div class="container container-md row">

        <div class="col-12 col-md-6 row align-items-center justify-content-center">
          <img class="img-fluid" src="assets/img/nosotros_svg_2.svg" alt="Imagen vectorizada de farmacia">
        </div>

        <div class="col-12 col-md-6 row align-items-center">
          <div class="w-100">
            <h1 class="fw-bold fs-3 titulos text-justify">Nuestra misión y visión.</h1>
            <p class="fw-semibold descripcion" style="font-size:16px">Brindar a nuestros pacientes un servicio integral de salud con excelencia y vocación de servicio, con un equipo de profesionales de alto estándar en cada una de las áreas a brindar y con óptima autosustentabilidad económica de nuestra institución. Nuestra visión es ser la primera institución de salud integral con alto nivel de excelencia en servicios médicos, transformando la imagen de la atención hospitalaria en Venezuela.
            </p>

          </div>
        </div>

      </div>

    </section>
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

            <div class="col-md-6 icon-box">
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

  </main>

  <?php $footer->footer(); ?>

  <?php $VarComp->js() ?>
  <script src="assets/js/inicio.js"></script>

</body>

</html>