<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\usuarios as usuarios;

$objModel = new usuarios();
$mostrarN = $objModel->mostrarRol();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);

$permiso = $permisos['Usuarios'];

if (!isset($_SESSION['nivel'])) {
	die('<script> window.location = "?url=login" </script>');
}

if (!isset($permiso["Consultar"]))
	die('<script> window.location = "?url=home" </script>');

if (isset($_POST['getPermisos'], $permiso["Consultar"])) {
	die(json_encode($permiso));
}

if (isset($_GET['validar'])) {
	$res = $objModel->getValidarC($_GET['cedula'], $_GET['idVal']);
	die(json_encode($res));
}

if (isset($_GET['validarE'])) {
	$res = $objModel->getValidarE($_GET['correo'], $_GET['idVal']);
	die(json_encode($res));
}

if (isset($_POST['mostrar'], $permiso["Consultar"])) {
	$res = $objModel->getMostrarUsuario($_POST['bitacora']);
	die(json_encode($res));
}

if (isset($_POST['cedula']) && isset($_POST['name']) && isset($_POST['apellido']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['tipoUsuario']) && isset($permiso["Registrar"])) {
	$res = $objModel->getAgregarUsuario($_POST['cedula'], $_POST['name'], $_POST['apellido'], $_POST['email'], $_POST['password'], $_POST['tipoUsuario']);
	die(json_encode($res));
}

if (isset($_POST['eliminar']) && isset($_POST['cedulaDel']) && isset($permiso["Eliminar"])) {
	$res = $objModel->getEliminar($_POST['cedulaDel']);
	die(json_encode($res));
}

if (isset($_POST['select']) && isset($_POST['id']) && isset($permiso["Editar"])) {
	$res = $objModel->getUnico($_POST['id']);
	die(json_encode($res));
}

if (isset($_POST['cedulaEdit']) && isset($_POST['nameEdit']) && isset($_POST['apellidoEdit']) && isset($_POST['emailEdit']) && isset($_POST['passwordEdit']) && isset($_POST['tipoUsuarioEdit']) && isset($_POST['id']) && isset($permiso["Editar"])) {
	$res = $objModel->getEditar($_POST['cedulaEdit'], $_POST['nameEdit'], $_POST['apellidoEdit'], $_POST['emailEdit'], $_POST['passwordEdit'], $_POST['tipoUsuarioEdit'], $_POST['id']);
	die(json_encode($res));
}

if (isset($_GET['cedula'], $_GET['persona'])){
	$res = $objModel->getPersonal($_GET['cedula']);
	die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/usuarioVista.php")) {
	require_once("vista/interno/usuarioVista.php");
}
