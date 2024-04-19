<?php

namespace component;

class tienda
{
    public function Nav()
    {

        $adminDashboard = "";
        if(isset($_SESSION['nivel'])) {
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
        $nav = '
        <nav class="navbar navbar-expand-lg navbar-light fixed-top d-block" id="navbar">
          <div class="container">
            <div>
              <a class="navbar-brand d-inline-flex" id="tituloNav" href="?url=inicio">
                <img class="d-inline-block" src="assets/img/Logos Wesley/logoWesleyColor.png" alt="logo"  height="100px" />
                <h1 class="text-1000 fs-2 fw-bold mx-2 m-auto"></h1>
              </a>
            </div>

            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-4 mt-lg-0" id="navbarSupportedContent">

              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                '.$inicioLi.'
                '.$nosotrosLi.'
                '.$adminDashboard.'
              </ul>
                '.$loginIcons.'
              </div>

            </div>
          </div>
        </nav>

        ';
        echo $nav;
    }


}

