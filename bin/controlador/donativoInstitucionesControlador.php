<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\donativoInstituciones as donativoInstituciones;

if (!isset($_SESSION['nivel'])) {
  die('<script> window.location = "login" </script>');
}

$objModel = new donativoInstituciones();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Donativos instituciones'];

if (!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`);

if (isset($_POST['getPermiso']) && $permiso['Consultar'] == 1) {
  die(json_encode($permiso));
}

if (isset($_POST['mostrar']) && isset($_POST['bitacora']) && $permiso['Consultar'] == 1) {
  $res = $objModel->getMostrarDonativosInstituciones($_POST['bitacora']);
  die(json_encode($res));
}

if (isset($_POST['detalleD']) && isset($_POST['id'])) {
  $res = $objModel->getDetalleDonacion($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['selectInstituciones'])) {
  $res = $objModel->selectInstituciones();
  die(json_encode($res));
}

if (isset($_POST['selectProductos'])) {
  $res = $objModel->selectProductos($_SESSION['id_sede']);
  die(json_encode($res));
}

if (isset($_GET['producto']) && isset($_GET['filas'])) {
  $res = $objModel->detallesProductoFila($_GET['producto']);
  die(json_encode($res));
}

if (isset($_GET['rif'], $_GET['tipo'])) {
  $res = $objModel->validarRif($_GET['rif']);
  die(json_encode($res));
}

if (isset($_POST['rifInstitucion']) && isset($_POST['datos']) && $permiso['Registrar']) {
  $res = $objModel->getRegistrarDonacion($_POST['rifInstitucion'], $_POST['datos']);
  die(json_encode($res));
}

if (isset($_POST['validarE']) && isset($_POST['id'])) {
  $res = $objModel->validarExistencia($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['eliminar']) && isset($_POST['id']) && $permiso['Eliminar']) {
  $res = $objModel->getEliminarDonacion($_POST['id']);
  die(json_encode($res));
}


$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);


if (file_exists("vista/interno/donaciones/donativoInstitucionesVista.php")) {
  require_once("vista/interno/donaciones/donativoInstitucionesVista.php");
}
