<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\transferencia;

// echo password_hash('123123123', PASSWORD_BCRYPT);
// die();
//
if (!isset($_SESSION['nivel'])) {
    die('<script> window.location = "?url=login" </script>');
}

$model = new transferencia();
$permisos = $model->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Transferencia'];

if (!isset($permiso["Consultar"])) {
    die('<script> window.location = "?url=home" </script>');
}

$sedes = $model->mostrarSedes();

if (isset($_GET['getPermisos'], $permiso['Consultar'])) {
    die(json_encode($permiso));
}

if (isset($_POST['notificacion'])) {
    $model->getNotificacion();
}


if (isset($_GET['detalle'], $_GET["id_transferencia"], $permiso['Consultar'])) {
    $res = $model->getMostrarDetalle($_GET["id_transferencia"]);
    die(json_encode($res));
}

if($_SESSION['id_sede'] != 1) {
    die('<script> window.location = "?url=home" </script>');
}


if (isset($_GET['select_producto'])) {
    $res = $model->mostrarProductos();
    die(json_encode($res));
}

if (isset($_GET['producto_inventario'], $permiso['Consultar'])) {
    $res = $model->getMostrarProductoInventario($_GET['producto_inventario']);
    die(json_encode($res));
}

if (isset($_GET['mostrar'], $_GET['bitacora'], $permiso['Consultar'])) {
    $res = $model->mostrarTransferencias($_GET['bitacora']);
    die(json_encode($res));
}

if (isset($_POST['registrar'], $_POST["sede"], $_POST['fecha'], $_POST["productos"], $permiso['Registrar'])) {
    $res = $model->getAgregarTransferencia($_POST["sede"], $_POST['fecha'], $_POST["productos"]);
    die(json_encode($res));
}

if (isset($_POST['eliminar'], $_POST["id"], $permiso['Registrar'])) {
    $res = $model->getEliminarTransferencia($_POST["id"]);
    die(json_encode($res));
}


$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);


if (file_exists('vista/interno/transferenciaVista.php')) {
    require_once('vista/interno/transferenciaVista.php');
} else {
    die("La vista no existe.");
}
