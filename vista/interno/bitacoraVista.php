<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitacora</title>
    <?php $VarComp->header(); ?>
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

	<main class="main" id="main">
		<div class="pagetitle">
      		<h1>Bitacora</h1>
      		<nav>
        		<ol class="breadcrumb">
          			<li class="breadcrumb-item">Registro de Movimientos</li>
        		</ol>
      		</nav>

    	</div>	

    	<div class="card">
            <div class="card-body">
            	<div class="row">
                	<div class="col-6">
                		<h5 class="card-title">Registros</h5>
                	</div>
                	<div class="table-responsive">
                    <table class="table table-hover" id="tabla" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th scope="col">Usuario</th>
                          <th scope="col">Descripcion</th>
                          <th scope="col">Fecha</th>
                        </tr>
                      </thead>
                      <tbody>
                    
                      </tbody>
                    </table>
              </div>
            	</div>
        	</div>
        </div>
  	<main>

</body>

  <?php $VarComp->js(); ?>
  <script src="assets/js/bitacora.js"></script>
 
</html>