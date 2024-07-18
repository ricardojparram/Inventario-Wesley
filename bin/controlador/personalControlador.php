<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\personal as personal;
use utils\JWTService;

$JWToken = JWTService::validateSession();
if (!isset($_SESSION['nivel']) && !$JWToken) {
	die('<script> window.location = "?url=login" </script>');
}


$objModel = new personal();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Personal'];
$mostrarT = $objModel->mostrarTipo();
$mostrarS = $objModel->mostrarSede();


if (isset($_POST['getPermisos'], $permiso["Consultar"])) {
	die(json_encode($permiso));
}
if (!isset($permiso["Consultar"]))
	die('<script> window.location = "?url=home" </script>');

if (!isset($permiso["Consultar"]))
	die('<script> window.location = "home" </script>');

if (isset($_POST['getPermisos'], $permiso["Consultar"])) {
	die(json_encode($permiso));
}

if (isset($_POST['mostrar'], $_POST['bitacora'])) {
	$res = $objModel->getMostrarPersonal($_POST['bitacora']);
	die(json_encode($res));
}

if (isset($_GET['mostrar'], $_GET['bitacora'])) {
	$res = $objModel->getMostrarPersonal($_GET['bitacora']);
	die(json_encode($res));
}

if (isset($_POST['dniEdit'], $_POST['nameEdit'], $_POST['lastNameEdit'], $_POST['emailEdit'], $_POST['ageEdit'], $_POST['adressEdit'], $_POST["phoneEdit"], $_POST['sedeEdit'], $_POST['tipoEdit'], $_POST['cedulaId'])) {
	$res = $objModel->getEditarPersonal($_POST['dniEdit'], $_POST['nameEdit'], $_POST['lastNameEdit'], $_POST['emailEdit'], $_POST['ageEdit'], $_POST['adressEdit'], $_POST["phoneEdit"], $_POST['sedeEdit'], $_POST['tipoEdit'], $_POST['cedulaId']);
	die(json_encode($res));
}

if (isset($_POST['mostrar'])) {
	$res = $objModel->getMostrarPersonal();
	die(json_encode($res));
}

if (isset($_POST['select'], $_POST['cedulaId'])) {
	$res = $objModel->getUnico($_POST['cedulaId']);
	die(json_encode($res));
}

if (isset($_POST['eliminar'], $_POST['cedulaId'])) {
	$res = $objModel->getEliminarPersonal($_POST['cedulaId']);
	die(json_encode($res));
}

if (isset($_GET['validar'])) {
	$res = $objModel->getValidarC($_GET['cedula'], $_GET['idVal']);
	die(json_encode($res));
}

if (isset($_GET['validarE'])) {
	$res = $objModel->getValidarE($_GET['correo'], $_GET['idVal']);
	die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/personalVista.php")) {
	require_once("vista/interno/personalVista.php");
}
