<?php

namespace component;

class header
{
    public function Header()
    {

        $header = '
    <header id="header" class="header fixed-top d-flex align-items-center">
<div class="d-flex align-items-center justify-content-between">
  <a href="?url=inicio" class="logo">
    <img src="assets/img/Logos Wesley/logo_wesley_con_titulo.svg" alt="Wesley">
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon notification_icon" href="#" data-bs-toggle="dropdown" data-bs-auto-close="false">
            <i class="bi bi-bell"></i>
            <span class="badge iconos badge-number contador"></span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header text-center">
              Usted tiene <span class="numNoti"></span> notificaciones
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-header text-start">
              Nuevas
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="item list-group">
              
            </li>


             <li class="itemVisto list-group">
              
            </li>


          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

    <li class="nav-item dropdown pe-3">

    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img class="fotoPerfil rounded-circle" src="'.$_SESSION['fotoPerfil'].'" alt="Profile">
        <span class="d-none d-md-block dropdown-toggle ps-2 nombreCompleto">'.$_SESSION["nombre"].' '.$_SESSION['apellido'].'</span>
      </a><!-- End Profile Iamge Icon -->


      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6 class="nombreCompleto">'.$_SESSION["nombre"].' '.$_SESSION['apellido'].'</h6>
          <span>'.$_SESSION["puesto"].'</span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="?url=perfil">
            <i class="bi bi-person"></i>
            <span>Mi perfil</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="?url=ayuda">
            <i class="bi bi-question-circle"></i>
            <span>¿Necesitas ayuda?</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="?url=cerrar">
            <i class="bi bi-box-arrow-right"></i>
            <span>Cerrar sesion</span>
          </a>
        </li>

      </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->

  </ul>
</nav><!-- End Icons Navigation -->

</header>
  ';

        echo $header;

    }
}
