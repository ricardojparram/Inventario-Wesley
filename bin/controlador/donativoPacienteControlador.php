<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\ventas as ventas;

if (!isset($_SESSION['nivel'])) {
  die('<script> window.location = "?url=login" </script>');
}

$objModel = new ventas();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Ventas'];

if (!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`);

if (isset($_POST['notificacion'])) {
  $objModel->getNotificacion();
}

if (isset($_POST['getPermiso']) && $permiso['Consultar'] == 1) {
  die(json_encode($permiso));
}

if (isset($_POST['mostrar']) && isset($_POST['bitacora']) && $permiso['Consultar'] == 1) {
  $res = $objModel->getMostrarDonativosPacientes($_POST['bitacora']);
  die(json_encode($res));
}

if (isset($_POST['detalleD']) && isset($_POST['id'])) {
  $res = $objModel->getDetalleDonacion($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['selectPacientes'])) {
  $res = $objModel->selectPacientes();
  die(json_encode($res));
}

if (isset($_POST['selectSedes'])) {
  $res = $objModel->selectSedes();
  die(json_encode($res));
}

if (isset($_POST['selectProductos'])) {
  $res = $objModel->selectProductos();
  die(json_encode($res));
}

if (isset($_GET['producto']) && isset($_GET['filas'])) {
  $res = $objModel->detallesProductoFila($_GET['producto']);
  die(json_encode($res));
}

if (isset($_POST['cedulaPaciente']) && isset($_POST['beneficiario']) && isset($_POST['datos']) && $permiso['Registrar']) {
  $res = $objModel->getRegistrarDonacion($_POST['cedulaPaciente'], $_POST['beneficiario'], $_POST['datos']);
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


if (file_exists("vista/interno/donaciones/donativoPacienteVista.php")) {
  require_once("vista/interno/donaciones/donativoPacienteVista.php");
}
