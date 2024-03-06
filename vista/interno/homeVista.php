<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <?php $VarComp->header(); ?>
    <link rel="stylesheet" href="assets/css/estiloInterno.css">

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
      <h1>Resumen</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
          <li class="breadcrumb-item active">Resumen</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item ventas" id="hoy">Hoy</a></li>
                    <li><a class="dropdown-item ventas" id="mensual">Mensual</a></li>
                    <li><a class="dropdown-item ventas" id="anual">Anual</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Ventas <span id="ventas">| Dia</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="valorV"></h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item compras" id="hoy">Hoy</a></li>
                    <li><a class="dropdown-item compras" id="mensual">Mensual</a></li>
                    <li><a class="dropdown-item compras" id="anual">Anual</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Compras <span id="compras">| Dia</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="valorC"></h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->


            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">


                <div class="card-body">
                  <h5 class="card-title">Productos<span></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-capsule-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="producto"></h6>
                      

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">


                <div class="card-body">
                  <h5 class="card-title">Usuarios</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-contacts-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="usuarios"></h6>
                      

                    </div> 
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">


                <div class="card-body">
                  <h5 class="card-title">Proveedores<span></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-bank-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="proveedores"></h6>
                      

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->


            <!-- Customers Card -->
            <div class="col-xxl-4 col-md-6">

              <div class="card info-card customers-card">

                <div class="card-body">
                  <h5 class="card-title">Clientes <span></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="clientes"></h6>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                

                <div class="card-body">
                  <h5 class="card-title text-center"><b>Gráfico de la semana actual</b></h5>

                  <!-- Line Chart -->
                  <canvas id="reportsChart"></canvas>

                  <!-- End Line Chart -->

                </div>

              </div>
            </div><!-- End Reports -->

           

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
      
      </div>
    </section>

  </main><!-- End #main -->

</body>
<?php $VarComp->js();?>

<!-- <script type="text/javascript" src="assets/js/notificaciones.js"></script> -->

<script src="assets/js/chart.min.js"></script>
<script type="text/javascript" src="assets/js/home.js"></script>
</html>