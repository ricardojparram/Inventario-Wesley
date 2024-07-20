<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use component\footer as footer;
use modelo\home as home;
use utils\JWTService;

$JWToken = JWTService::validateSession();

$nivel = (isset($_SESSION['nivel'])) ? $_SESSION['nivel'] : $JWToken['nivel'];

if (isset($nivel)) {
  if ($nivel == 4) {
    die('<script> window.location = "login" </script>');
  }
} else {
  die('<script> window.location = "login" </script>');
}


$objModel = new home();
$permisos = $objModel->getPermisosRol($nivel);

if (isset($_GET['clien'])) {
 $res = $objModel->mostrarPersonal();
 die(json_encode($res));
}

if (isset($_POST['grafico'])) {
  $objModel->getGrafico();
}

if (isset($_POST['ventas']) && isset($_POST['opcionV'])) {
  $objModel->getVentas($_POST['opcionV']);
}

if (isset($_POST['compras']) && isset($_POST['opcionC'])) {
  $objModel->getCompras($_POST['opcionC']);
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);
$footer = new footer();

if (file_exists("vista/interno/homeVista.php")) {
  require_once("vista/interno/homeVista.php");
}
