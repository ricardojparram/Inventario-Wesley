<?php

namespace component;

class tienda
{
  public function Nav()
  {

    $adminDashboard = "";
    if (isset($_SESSION['nivel'])) {
      $adminDashboard = ($_SESSION['nivel'] != "4")
        ? '<li class="nav-item px-2"><a class="nav-link fw-medium" href="' . _URL_ . 'home">Admin</a></li>' : '';
    }
    $loginIcons = (!isset($_SESSION['cedula']))
      ? '<div class="mt-2 mt-lg-0">
                <a class="btn btn-sm btn-outline-success me-2" href="' . _URL_ . 'login">
                  <span class="ocultarlg fw-bold">Iniciar sesión </span><i class="bi bi-person-fill"></i>
                </a>
            </div>
            <div class="mt-2 mt-lg-0">
              <a class="btn btn-sm btn-outline-success" href="' . _URL_ . 'registro">
                <span class="ocultarlg fw-bold">Registrarse </span><i class="bi bi-person-lines-fill"></i>
              </a>
            </div>'
      : '<div class="m-0">
                <a class="text-success me-2" href="' . _URL_ . 'cerrar"><i class="bi bi-box-arrow-right fs-4"></i></a>
            </div>';
    $inicioLi = (!isset($_GET["url"])) ? '<li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="' . _URL_ . '">Inicio</a></li>' : '<li class="nav-item px-2"><a class="nav-link fw-medium" href="' . _URL_ . '">Inicio</a></li>';
    $nosotrosLi = (isset($_GET["url"]) && $_GET['url'] === 'nosotros') ? '<li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="' . _URL_ . 'nosotros">Nosotros</a></li>' : '<li class="nav-item px-2"><a class="nav-link fw-medium" href="' . _URL_ . 'nosotros">Nosotros</a></li>';
    $nav = '
        <nav class="navbar navbar-expand-lg navbar-light fixed-top d-block" id="navbar">
          <div class="container">
            <div>
              <a class="navbar-brand d-inline-flex" id="tituloNav" href="' . _URL_ . '">
                <img class="d-inline-block" src="assets/img/Logos Wesley/logoWesleyColor.png" alt="logo"  height="100px" />
                <h1 class="text-1000 fs-2 fw-bold mx-2 m-auto"></h1>
              </a>
            </div>

            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-4 mt-lg-0" id="navbarSupportedContent">

              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                ' . $inicioLi . '
                ' . $nosotrosLi . '
                ' . $adminDashboard . '
              </ul>
                ' . $loginIcons . '
              </div>

            </div>
          </div>
        </nav>

        ';
    echo $nav;
  }
}
