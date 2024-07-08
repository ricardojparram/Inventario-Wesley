<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\mantenimiento as mantenimiento;

$objModel = new mantenimiento();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);

$permiso = $permisos['Mantenimiento'];  

if (!isset($_SESSION['nivel'])) {
	die('<script> window.location = "?url=login" </script>');
}

if (!isset($permiso["Consultar"]))
	die('<script> window.location = "?url=home" </script>');

if (isset($_POST['exportar'])) {
    $res = $objModel->getRespaldo();
    die(json_encode($res));
}

if(isset($_POST['mostrar']) && isset($permiso['Consultar'])){
	$res = $objModel->getHistorial();
	die(json_encode($res));
}


$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/mantenimientoVista.php")) {
	require_once("vista/interno/mantenimientoVista.php");
}  

?>