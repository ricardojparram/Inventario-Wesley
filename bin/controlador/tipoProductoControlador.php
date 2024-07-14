<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\tipoProducto as tipoProducto;

if (!isset($_SESSION['nivel'])) {
	die('<script> window.location = "login" </script>');
}

$objModel = new tipoProducto();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Tipo'];

if (!isset($permiso['Consultar'])) die('<script> window.location = "home" </script>');

if (isset($_POST['notificacion'])) {
	$objModel->getNotificacion();
}

if (isset($_POST['getPermisos']) && $permiso['Consultar'] == 1) {
	die(json_encode($permiso));
}

if (isset($_POST["tipoProducto"]) && isset($_POST['validarTipo']) && isset($_POST['id'])) {
	$res = $objModel->validarTipo($_POST["tipoProducto"], $_POST["id"]);
	die(json_encode($res));
}

if (isset($_POST["tipoProducto"]) && $permiso['Registrar'] == 1) {
	$objModel->getAgregarTipoProducto($_POST["tipoProducto"]);
}

if (isset($_POST["mostrar"]) && isset($_POST['bitacora']) && $permiso['Consultar'] == 1) {
	$objModel->mostrarTipoProducto();
}

if (isset($_POST["id"]) && isset($_POST["borrar"]) && $permiso['Eliminar'] == 1) {
	$objModel->getEliminar($_POST["id"]);
}

if (isset($_POST["idedit"]) && isset($_POST["item"]) && $permiso['Consultar'] == 1) {
	$res = $objModel->getItem($_POST["idedit"]);
	die(json_encode($res));
}

if (isset($_POST["tipoProductoEdit"]) && isset($_POST["idedit"]) && $permiso['Editar'] == 1) {
	$res = $objModel->getEditarTipoProducto($_POST["tipoProductoEdit"], $_POST["idedit"]);
	die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);


if (file_exists("vista/interno/productos/tipoProductoVista.php")) {
	require_once("vista/interno/productos/tipoProductoVista.php");
}
