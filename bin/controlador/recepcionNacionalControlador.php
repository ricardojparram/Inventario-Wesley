<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\recepcionNacional;

if (!isset($_SESSION['nivel'])) {
    die('<script> window.location = "?url=login" </script>');
}
if ($_SESSION['id_sede'] != 1) {
    die('<script> window.location = "?url=home" </script>');
}

$model = new recepcionNacional();
$permisos = $model->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Recepcion nacional'];

if (!isset($permiso["Consultar"])) {
    die('<script> window.location = "?url=home" </script>');
}

if (isset($_GET['getPermisos'], $permiso["Consultar"])) {
    die(json_encode($permiso));
}
$proveedores = $model->mostrarProveedores();

if (isset($_GET['mostrar'], $permiso["Consultar"])) {
    $res = $model->mostrarRecepciones($_GET['bitacora']);
    die(json_encode($res));
}

if (isset($_GET['detalle'], $_GET['id'], $permiso["Consultar"])) {
    $res = $model->getMostrarDetalle($_GET['id']);
    die(json_encode($res));
}

if (isset($_GET['select_producto'], $permiso["Consultar"])) {
    $res = $model->mostrarProductos();
    die(json_encode($res));
}

if (isset($_POST['registrar'], $_POST['proveedor'], $_POST['fecha'], $_POST['productos'], $permiso["Consultar"])) {
    $res = $model->getAgregarRecepcionNacional($_POST['proveedor'], $_POST['fecha'], $_POST['productos']);
    die(json_encode($res));
}

if (isset($_POST['eliminar'], $_POST["id"], $permiso['Eliminar'])) {
    $res = $model->getEliminarRecepcionNacional($_POST["id"]);
    die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/recepcionNacionalVista.php")) {
    require_once("vista/interno/recepcionNacionalVista.php");
} else {
    die("La vista no existe.");
}
