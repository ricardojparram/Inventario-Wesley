<?php

namespace component;

use modelo\sede;

class header
{
  public function Header()
  {
    $model = new sede;
    $sedes = $model->getMostrarSede();

    $liSedes = "";
    foreach ($sedes as $row) {
      $checked = ($_SESSION['id_sede'] == $row->id_sede) ? 'checked' : "";
      $id = 'sede_' . $row->id_sede;
      $liSedes .= '
      <li class="dropdown-item d-flex align-items-center gap-2">
        <input class="form-check-input" type="radio" name="cambiarSede" id="' . $id . '" value="' . $row->id_sede . '" ' . $checked . '>
        <label class="form-check-label" for="' . $id . '" style="width: 100% !important;" >
          ' . $row->nombre . '
        </label>
      </li>
      
      <li>
        <hr class="dropdown-divider">
      </li>
      ';
    }

    $header = '
    <header id="header" class="header fixed-top d-flex align-items-center">
      <div class="d-flex align-items-center justify-content-between">
        <a href="?url=inicio" class="logo d-none d-sm-block">
          <img src="assets/img/Logos Wesley/logo_wesley_con_titulo.svg" alt="Wesley">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
      </div><!-- End Logo -->

      <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

          <li class="nav-item dropdown">

            <a class="nav-link nav-profile d-flex align-items-center pe-2" href="#" data-bs-toggle="dropdown" data-bs-auto-close="false">
              <i class="bi bi-hospital nav-icon me-0"></i>
              <span class="d-none d-md-block dropdown-toggle ps-2"></span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
              <form method="POST" action="?url=sede" id="changeSede">
              ' . $liSedes . '
              </form>
              <li class="dropdown-item d-flex align-items-center">
                <input type="submit" form="changeSede" id="changeSedeSubmit" class="form-control" value="Cambiar sede"/>
              </li>
            </ul>
          </li>

          <li class="nav-item dropdown">

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
              <img class="fotoPerfil rounded-circle" src="' . $_SESSION['fotoPerfil'] . '" alt="Profile">
              <span class="d-none d-md-block dropdown-toggle ps-2 nombreCompleto">' . $_SESSION["nombre"] . ' ' . $_SESSION['apellido'] . '</span>
            </a><!-- End Profile Iamge Icon -->


            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
              <li class="dropdown-header">
                <h6 class="nombreCompleto">' . $_SESSION["nombre"] . ' ' . $_SESSION['apellido'] . '</h6>
                <span>' . $_SESSION["puesto"] . '</span></br>
                <span>' . $_SESSION["sede"] . '</span>
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

    <!-- MODAL Notification -->
    <div class="modal fade" id="notificacion" tabindex="-1" aria-labelledby="notificacionLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="notificacionLabel">Notificación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="titulo"></p>
            <p class="fecha"></p>
            <p class="mensaje" style="text-align: justify"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary cerrar" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

  ';

    echo $header;
  }
}
