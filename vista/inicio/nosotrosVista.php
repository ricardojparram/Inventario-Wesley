<!DOCTYPE html>
<html lang="en">

<head>
  <title>MediSalud C.A</title>
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
          <h1 class="fw-bold fs-3 titulos text-justify">En Farmacia Medisalud C.A, tu salud es nuestra prioridad.</h1>
          <p class="fw-semibold" style="font-size:16px">Llevamos más de 16 años enfocándonos en brindar una experiencia de compra personalizada, garantizando la calidad, seguridad y eficiencia operativa de nuestros productos. Nuestro equipo está capacitado para ofrecer asesoría farmacéutica y un trato personalizado a cada cliente. Te invitamos a visitarnos y experimentar la diferencia en nuestro enfoque hacia la atención al paciente y la promoción del bienestar en la comunidad.
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
                <p class="fw-semibold" style="font-size:16px">Tenemos como misión ser reconocidos por la alta calidad en la comercialización y distribución de productos farmacéuticos, ofreciendo los mejores precios y una de las mejores variedades de productos para el bienestar de la salud de todos los que lo requieran. Además, nos comprometemos a brindar una buena atención al cliente, asesoría farmacéutica, mejoramiento continuo, crecimiento del personal y estabilidad de la empresa. También nos enfocamos en fomentar el crecimiento y desarrollo de nuevas fuentes de empleo para los ciudadanos de la comunidad, y en ofrecer servicios de salud y farmacéuticos personalizados para satisfacer las necesidades de la comunidad.
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
