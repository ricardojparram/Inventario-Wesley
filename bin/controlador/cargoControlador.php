<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\cargo;

if (!isset($_SESSION['nivel'])) die('<script> window.location = "login" </script>');

$model = new cargo();

$permisos = $model->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Cargo'];

if (!isset($permiso["Consultar"])) die('<script> window.location = "home" </script>');

if (isset($_GET['getPermisos'], $permiso['Consultar'])) {
    die(json_encode($permiso));
}

if (isset($_GET['mostrar'], $_GET['bitacora'], $permiso['Consultar'])) {
    $res = $model->mostrarCargos($_GET['bitacora']);
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

if (isset($_POST['registrar'], $_POST['num_cargo'], $_POST['fecha'], $_POST['productos'], $permiso["Consultar"])) {
    $res = $model->getAgregarCargo($_POST['num_cargo'], $_POST['fecha'], $_POST['productos']);
    die(json_encode($res));
}

if (isset($_POST['eliminar'], $_POST["id"], $permiso['Eliminar'])) {
    $res = $model->getEliminarCargo($_POST["id"]);
    die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/cargoVista.php")) {
    require_once("vista/interno/cargoVista.php");
} else {
    die("La vista no existe.");
}
