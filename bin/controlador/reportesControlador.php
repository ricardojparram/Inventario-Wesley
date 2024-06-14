<?php


use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\reportes as reportes;

if (!isset($_SESSION['nivel'])) {
    die('<script> window.location = "?url=login" </script>');
}

$objModel = new reportes();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Reportes'];

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (!isset($permiso["Consultar"])) {
    die('<script> window.location = "?url=home" </script>');
}
$tipo_reporte = $objModel->tiposDeReporte();

if (isset($_POST['getPermisos'], $permiso['Consultar'])) {
    die(json_encode($permiso));
}


if (isset($_POST['mostrar'], $_POST['tipo'], $_POST['fechaInicio'], $_POST['fechaFinal'], $permiso["Consultar"])) {
    $res = $objModel->getMostrarReporte($_POST['tipo'], $_POST['fechaInicio'], $_POST['fechaFinal']);
    die(json_encode($res));
}

if (isset($_POST['exportar'], $_POST['tipo'], $_POST['fechaInicio'], $_POST['fechaFinal'], $_POST['grafico'], $permiso["Exportar reporte"])) {
    $res = $objModel->getExportar($_POST['tipo'], $_POST['fechaInicio'], $_POST['fechaFinal'], $_POST['grafico']);
    die(json_encode($res));
}

if (file_exists("vista/interno/reportesVista.php")) {
    require_once("vista/interno/reportesVista.php");
} else {
    die('La vista no existe.');
}
