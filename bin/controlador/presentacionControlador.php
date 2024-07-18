<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\presentacion as presentacion;

if (!isset($_SESSION['nivel'])) {
    die('<script> window.location = "login" </script>');
}

$objModel = new presentacion();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Presentacion'];

if (isset($_POST['mostrars'])) {
    $objModel->mostrarPresentacionAjax();
}

$mostrarMedida = $objModel->mostrarMedida();

if (isset($_POST['med'])  && isset($_POST['cant']) && isset($_POST['pes'])) {
    $respuesta = $objModel->getDatosPres($_POST['med'], $_POST['cant'], $_POST['pes']);
}

if (isset($_POST['select'])) {
    $objModel->getPres($_POST['id']);
}

if (isset($_POST['medEdit']) && isset($_POST['cantEdit']) && isset($_POST['pesEdit'])) {
    $respuesta = $objModel->getEditar($_POST['medEdit'], $_POST['cantEdit'], $_POST['pesEdit'], $_POST['id']);
}

if (isset($_POST['borrar'])  && isset($permiso['Eliminar'])) {
    $objModel->getEliminar($_POST['id']);
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/productos/presentacionVista.php")) {
    require_once("vista/interno/productos/presentacionVista.php");
}
