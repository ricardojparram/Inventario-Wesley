<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\moneda as moneda;

$objModel = new moneda();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Moneda'];

//$objModel->actualizarMoneda();

if (!isset($_SESSION['nivel'])) {
  die('<script> window.location = "login" </script>');
}

if (!isset($permiso['Consultar'])) die('<script> window.location = "home" </script>');

// if(isset($_POST['notificacion'])) {
//   $objModel->getNotificacion();
// }

if (isset($_POST['getPermisos']) && isset($permiso['Consultar'])) {
  die(json_encode($permiso));
}

if (isset($_POST['datos']) && isset($permiso['Consultar'])) {
  $res = $objModel->getMoneda($_POST['bitacora']);
  die(json_encode($res));
}

if (isset($_POST['moneda']) && isset($_POST['name']) && isset($permiso['Registrar'])) {
  $res = $objModel->getAgregarMoneda($_POST['name']);
  die(json_encode($res));
}

if (isset($_POST['edit']) && isset($_POST['id']) && isset($permiso['Editar'])) {
  $res = $objModel->mostrarM($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['nameEdit']) && $_POST['id'] && isset($permiso['Editar'])) {
  $res = $objModel->getEditarM($_POST['nameEdit'], $_POST['id']);
  die(json_encode($res));
}

if (isset($_POST['delete']) && isset($_POST['id']) && isset($permiso['Eliminar'])) {
  $res = $objModel->getEliminarM($_POST['id']);
  die(json_encode($res));
}

if (isset($_POST["mostrar"], $_POST['idHistory'], $permiso['Consultar'])) {
  $res = $objModel->getMostrarCambio($_POST['idHistory']);
  die(json_encode($res));
}

if (isset($_POST['select'])) {
  $res = $objModel->SelectM();
  die(json_encode($res));
}

if (isset($_POST["cambio"]) && isset($_POST["tipo"]) && isset($permiso['Registrar'])) {
  $res = $objModel->getAgregarCambio($_POST["cambio"], $_POST["tipo"]);
  die(json_encode($res));
}

if (isset($_POST["borrar"]) && isset($_POST["id"]) && isset($permiso['Eliminar'])) {
  $res = $objModel->getEliminarCambio($_POST["id"]);
  die(json_encode($res));
}

if (isset($_POST["unico"]) && isset($_POST["editar"]) && isset($permiso['Editar'])) {
  $res = $objModel->mostrarUnico($_POST["unico"]);
  die(json_encode($res));
}

if (isset($_POST["cambioEdit"]) && isset($_POST["tipoEdit"]) && isset($_POST["unico"]) && isset($permiso['Editar'])) {
  $res = $objModel->getEditarCambio($_POST["cambioEdit"], $_POST["tipoEdit"], $_POST["unico"]);
  die(json_encode($res));
}

if (isset($_GET["id"], $_GET["nomMoneda"], $_GET["validar"])) {
  $res = $objModel->getValidarMon($_GET["id"], $_GET["nomMoneda"]);
  die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/configuraciones/monedaVista.php")) {
  require_once("vista/interno/configuraciones/monedaVista.php");
}
