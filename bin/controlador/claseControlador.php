<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\clase as clase;

if (!isset($_SESSION['nivel'])) {
	die('<script> window.location = "login" </script>');
}

$objModel = new clase();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Clase'];

if (!isset($permiso['Consultar'])) die(`<script> window.location = "home" </script>`);

if (isset($_POST['getPermisos']) && $permiso['Consultar'] == 1) {
	die(json_encode($permiso));
}

if (isset($_POST["clase"]) && $permiso['Registrar'] == 1) {
	$objModel->getAgregarClase($_POST["clase"]);
}

if (isset($_POST["mostrar"]) && isset($_POST['bitacora']) && $permiso['Consultar'] == 1) {
	$objModel->mostrarClase();
}

if (isset($_POST["id"]) && isset($_POST["borrar"]) && $permiso['Eliminar'] == 1) {
	$objModel->getEliminar($_POST["id"]);
}

if (isset($_POST["idedit"]) && isset($_POST["item"]) && $permiso['Consultar'] == 1) {
	$res = $objModel->getItem($_POST["idedit"]);
	die(json_encode($res));
}

if (isset($_POST["claseEdit"]) && isset($_POST["idedit"]) && $permiso['Editar'] == 1) {
	$res = $objModel->getEditarClase($_POST["claseEdit"], $_POST["idedit"]);
	die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);


if (file_exists("vista/interno/productos/claseVista.php")) {
	require_once("vista/interno/productos/claseVista.php");
}
