<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use component\footer as footer;
use modelo\tipoEmpleado as tipoEmpleado;


$objModel = new tipoEmpleado();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Tipo empleado'];

if (!isset($_SESSION['nivel'])) {
  die('<script> window.location = "login" </script>');
}

if (!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`);

if (isset($_POST['getPermiso']) && $permiso['Consultar'] == 1) {
  die(json_encode($permiso));
}

if (isset($_POST['mostrar']) && isset($_POST['bitacora'])) {
  $res = $objModel->getMostrarEmpleado($_POST['bitacora']);
  die(json_encode($res));
}

if (isset($_POST['tipoEmpleado']) && isset($_POST['validarTipoEmpleado']) && isset($_POST['id'])) {
  $res = $objModel->validarTipoEmpleado($_POST['tipoEmpleado'], $_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['validarE']) && isset($_POST['id'])) {
  $res = $objModel->validarExistencia($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['tipoEmpleado']) && $permiso['Registrar'] == 1) {
  $res = $objModel->getRegistrarEmpleado($_POST['tipoEmpleado']);
  die(json_encode($res));
}

if (isset($_POST['id']) && isset($_POST['mostrarEdit']) && $permiso['Editar'] == 1) {
  $res = $objModel->getMostrarEdit($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['id']) && isset($_POST['tipoEmpleadoEdit']) && $permiso['Editar'] == 1) {
  $res = $objModel->getEditarEmpleado($_POST['tipoEmpleadoEdit'], $_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['eliminar']) && isset($_POST['id'])) {
  $res = $objModel->getEliminarEmpleado($_POST['id']);
  die(json_encode($res));
}


$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);
$footer = new footer();

if (file_exists('vista/interno/tipoEmpleadoVista.php')) {
  require_once('vista/interno/tipoEmpleadoVista.php');
}
