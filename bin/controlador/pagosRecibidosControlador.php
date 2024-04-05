<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\pagosRecibidos;

if (!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

$model = new pagosRecibidos();

$permisos = $model->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Pagos recibidos'];

if (!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

if (isset($_GET['getPermisos'], $permiso['Consultar'])) {
    die(json_encode($permiso));
}

if (isset($_GET['mostrar'], $_GET['bitacora'], $permiso['Consultar'])) {
    $res = $model->mostrarPagosRecibidos($_GET['bitacora']);
    die(json_encode($res));
}

if (isset($_GET['detalle'], $_GET['id_pago'], $permiso['Consultar'])) {
    $res = $model->getMostrarDetalle($_GET['id_pago']);
    die(json_encode($res));
}
if (isset($_POST['status'], $_POST['id_pago'])) {
    $res = $model->getConfirmarPago($_POST['status'], $_POST['id_pago']);
    die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/pagosRecibidosVista.php")) {
    require_once("vista/interno/pagosRecibidosVista.php");
} else {
    die("La vista no existe.");
}
