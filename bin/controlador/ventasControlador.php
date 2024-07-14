<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\ventas as ventas;

if (!isset($_SESSION['nivel'])) {
  die('<script> window.location = "login" </script>');
}

$objModel = new ventas();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Ventas'];

if (!isset($permiso['Consultar'])) die(`<script> window.location = "home" </script>`);

if (isset($_POST['notificacion'])) {
  $objModel->getNotificacion();
}

if (isset($_POST['getPermiso']) && $permiso['Consultar'] == 1) {
  die(json_encode($permiso));
}

if (isset($_POST['mostrar'], $_POST['bitacora']) && $permiso['Consultar']) {
  $res = $objModel->getMostrarVentas($_POST['bitacora']);
  die(json_encode($res));
}

if (isset($_POST['detalleProductos'], $_POST['id'])) {
  $res = $objModel->detalleProductos($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['detalleTipo'], $_POST['id'])) {
  $res = $objModel->detalleTipo($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['selectCliente'])) {
  $res = $objModel->getMostrarClientes();
  die(json_encode($res));
}

if (isset($_POST['valorDolar'])) {
  $res = $objModel->valorDolar();
  die(json_encode($res));
}

if (isset($_POST['selectProductos'])) {
  $res = $objModel->selectProductos();
  die(json_encode($res));
}

if (isset($_POST['selectTipoPago'])) {
  $res = $objModel->selectTipoPago();
  die(json_encode($res));
}

if (isset($_GET['producto'], $_GET['filas'])) {
  $res = $objModel->detallesProductoFila($_GET['producto']);
  die(json_encode($res));
}

if (isset($_GET['cedula'], $_GET['tipo'])) {
  $res = $objModel->validarCedula($_GET['cedula'], $_GET['tipo']);
  die(json_encode($res));
}

if (isset($_POST['cedula'], $_POST['tipoCliente'], $_POST['montoTotal'], $_POST['totalDolares'], $_POST['datosProducto'], $_POST['datosTipoPago'], $permiso['Registrar'])) {

  $res = $objModel->getRegistrarVenta($_POST['cedula'], $_POST['tipoCliente'], $_POST['montoTotal'], $_POST['totalDolares'], $_POST['datosProducto'], $_POST['datosTipoPago']);
  die(json_encode($res));
}

if (isset($_POST['anular'], $_POST['id'])) {
  $res = $objModel->getAnularVenta($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['ticket'], $_POST['id'])) {
  $res = $objModel->ExportarTicket($_POST['id']);
  die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);


if (file_exists("vista/interno/ventasVista.php")) {
  require_once("vista/interno/ventasVista.php");
}
