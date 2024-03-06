<?php

    namespace component;

    class tienda{

      public function Nav(){

        $adminDashboard = "";
        $misCompras = "";
        if(isset($_SESSION['nivel'])){
          $misCompras = '<li class="nav-item px-2"><a class="nav-link fw-medium" href="?url=miscompras">Mis compras</a></li>';
          $adminDashboard = ($_SESSION['nivel'] != "4") 
            ? '<li class="nav-item px-2"><a class="nav-link fw-medium" href="?url=home">Admin</a></li>' : '';
        }
        $loginIcons = (!isset($_SESSION['cedula']))
          ? '<div class="mt-2 mt-lg-0">
                <a class="btn btn-sm btn-outline-success me-2" href="?url=login">
                  <span class="ocultarlg fw-bold">Iniciar sesión </span><i class="bi bi-person-fill"></i>
                </a>
            </div>
            <div class="mt-2 mt-lg-0">
              <a class="btn btn-sm btn-outline-success" href="?url=registro">
                <span class="ocultarlg fw-bold">Registrarse </span><i class="bi bi-person-lines-fill"></i>
              </a>
            </div>'
          : '<div class="m-0">
                <a class="text-success me-2" href="?url=cerrar"><i class="bi bi-box-arrow-right fs-4"></i></a>
            </div>';
        $inicioLi = ($_GET["url"] === "inicio") ? '<li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="?url=inicio">Inicio</a></li>' : '<li class="nav-item px-2"><a class="nav-link fw-medium" href="?url=inicio">Inicio</a></li>';
        $nosotrosLi = ($_GET['url'] === 'nosotros') ? '<li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="?url=nosotros">Nosotros</a></li>' : '<li class="nav-item px-2"><a class="nav-link fw-medium" href="?url=nosotros">Nosotros</a></li>';
        $catalogoLi = ($_GET['url'] === 'catalogo') ? '<li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="?url=catalogo">Catálogo</a></li>' : '<li class="nav-item px-2"><a class="nav-link fw-medium" href="?url=catalogo">Catálogo</a></li>';
        // $contactanosLi = ($_GET['url'] === 'contactanos') ? '<li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="?url=contactanos">Contactanos</a></li>' : '<li class="nav-item px-2"><a class="nav-link fw-medium" href="?url=contactanos">Contactanos</a></li>';
        $nav = '
        <nav class="navbar navbar-expand-lg navbar-light fixed-top d-block" id="navbar">
          <div class="container">
            <div>
              <a class="navbar-brand d-inline-flex" id="tituloNav" href="?url=inicio">
                <img class="d-inline-block" src="assets/img/Logo_Medi.png" alt="logo" width="50px" height="50px" />
                <h1 class="text-1000 fs-2 fw-bold mx-2 m-auto">Medisalud</h1>
              </a>
            </div>

            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-4 mt-lg-0" id="navbarSupportedContent">

              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                '.$inicioLi.'
                '.$nosotrosLi.'
                '.$catalogoLi.'
                '.$misCompras.'
                '.$adminDashboard.'
              </ul>

              <div class="mx-2 mx-lg-0 sec">
                <div class="m-0 ">
                  <a class="text-success icon-car me-4" href="?url=carrito">
                    <em class="ocultarlg">Carrito </em>
                    <span class="bi bi-cart4 fs-4"></span>
                    <span id="carrito_badge" class="badge badge-light icon-badge">0</span>
                  </a>
                </div>
                '.$loginIcons.'
              </div>

            </div>
          </div>
        </nav>

        ';
        echo $nav;
      }

      public function Car(){
        $car = '
            <!-- Boton para Carrito Desplegable -->
            <button id="carRight" class="btn fs-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
              <i class="bi bi-cart4"></i>
            </button>
          
            <!--Mini menu para Carrito-->
            <section class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
              <header class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Carrito de Compras</h5>
                <button type="button" class="btn-close"  data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </header>
              <main class="offcanvas-body">
                <div class="row justify-content-center p-4 carrito-container"></div>
              </main>
            </section>
          <!-- End Car Dropdown -->';
        echo $car;
      }

  }

?>