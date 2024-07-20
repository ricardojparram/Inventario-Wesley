<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\donativoPersonal as donativoPersonal;
use utils\JWTService;

$JWToken = JWTService::validateSession();
if (!isset($_SESSION['nivel']) && !$JWToken) {
  die('<script> window.location = "login" </script>');
}

$nivel = (isset($_SESSION['nivel'])) ? $_SESSION['nivel'] : $JWToken['nivel'];

$objModel = new donativoPersonal();
$permisos = $objModel->getPermisosRol($nivel);
$permiso = $permisos['Donativos personal'];

if (!isset($permiso['Consultar'])) die(`<script> window.location = "home" </script>`);


if (isset($_POST['getPermiso']) && $permiso['Consultar'] == 1) {
  die(json_encode($permiso));
}

if (isset($_POST['mostrar']) && isset($_POST['bitacora'])) {
  $res = $objModel->getMostrarDonativosPersonal($_POST['bitacora'] ,$_SESSION['id_sede']);
  die(json_encode($res));
}

if (isset($_GET['mostrar']) && isset($_GET['app'])) {
  $res = $objModel->getMostrarDonaciones();
  die(json_encode($res));
}

if (isset($_GET['detalleD']) && isset($_GET['id'])) {
  $res = $objModel->getDetalleDonacion($_GET['id']);
  die(json_encode($res));
}

if (isset($_POST['selectPersonal'])) {
  $res = $objModel->selectPersonal();
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

if (isset($_GET['cedula'], $_GET['tipo'])) {
  $res = $objModel->validarCedula($_GET['cedula']);
  die(json_encode($res));
}

if (isset($_POST['cedulaPersonal']) && isset($_POST['datos']) && $permiso['Registrar']) {
  $res = $objModel->getRegistrarDonacion($_POST['cedulaPersonal'], $_POST['datos']);
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


if (file_exists("vista/interno/donaciones/donativoPersonalVista.php")) {
  require_once("vista/interno/donaciones/donativoPersonalVista.php");
}
