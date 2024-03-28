<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\recepcion;

if (!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

$model = new recepcion();

$permisos = $model->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Recepcion'];

$sedes = $model->mostrarSedes();

if (!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

if (isset($_GET['getPermisos'], $permiso['Consultar'])) {
    die(json_encode($permiso));
}

if (isset($_GET['mostrar'], $_GET['bitacora'], $permiso['Consultar'])) {
    $res = $model->mostrarTransferencias($_GET['bitacora']);
    die(json_encode($res));
}

if (isset($_GET['datosTransferencia'], $_GET["id"], $permiso['Consultar'])) {
    $res = $model->getDatosTransferencia($_GET["id"]);
    die(json_encode($res));
}


$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);


if (isset($_GET['registrar'])) {
    require_once('vista/interno/recepcion/registrarRecepcionVista.php');
} else {
    require_once('vista/interno/recepcion/recepcionVista.php');
}
