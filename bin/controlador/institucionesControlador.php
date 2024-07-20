<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\instituciones as instituciones;

if (!isset($_SESSION['nivel'])) die('<script> window.location = "login" </script>');

$objModel = new instituciones();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Laboratorio'];

if (!isset($permiso["Consultar"])) die('<script> window.location = "home" </script>');

if (isset($_POST['getPermisos'], $permiso["Consultar"])) {
    die(json_encode($permiso));
}

if (isset($_POST['mostrar'], $permiso["Consultar"])) {
    $res = $objModel->getMostrarInstituciones($_POST['bitacora']);
    die(json_encode($res));
}

if (isset($_POST['rif'], $_POST['direccion'], $_POST['razon'], $_POST['contacto'], $permiso["Registrar"])) {
    $res = $objModel->getRegistrarInstitucion($_POST['rif'], $_POST['razon'], $_POST['direccion'], $_POST['contacto'], $permiso["Registrar"]);
    die(json_encode($res));
}

if (isset($_GET['rif'], $_GET['validar'], $_GET['edit'])) {
    $res = $objModel->getRif($_GET['rif'], $_GET['edit']);
    die(json_encode($res));
}

if (isset($_POST['select'], $_POST['cedulaId'], $permiso["Editar"])) {
    $res = $objModel->getItem($_POST['cedulaId']);
    die(json_encode($res));
}

if (isset($_POST['eliminar'], $permiso["Eliminar"])) {
    $res = $objModel->getEliminar($_POST['cedulaId']);
    die(json_encode($res));
}

if (isset($_POST['rifEdit'], $_POST['direccionEdit'], $_POST['razonEdit'], $_POST['contactoEdit'], $_POST['cedulaId'])) {
    if (!isset($permiso["Editar"]))
        die(json_encode($objModel->http_error(403, "Permiso denegado.")));

    $res = $objModel->getEditar($_POST['rifEdit'], $_POST['razonEdit'], $_POST['direccionEdit'], $_POST['contactoEdit'], $_POST['cedulaId']);
    die(json_encode($res));
}



$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/productos/institucionesVista.php")) {
    require_once("vista/interno/productos/institucionesVista.php");
} else {
    die("La vista no existe.");
}
