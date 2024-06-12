<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\sede as sede;
use utils\JWTService;

$model = new sede();
if (isset($_GET['mostrar'], $_GET['bitacora'])) {
    $res = $model->getMostrarSede($_GET['bitacora']);
    die(json_encode($res));
}

$JWToken = JWTService::validateSession();
if (!isset($_SESSION['nivel']) && !$JWToken) {
    die('<script> window.location = "?url=login" </script>');
}

$nivel = (isset($_SESSION['nivel'])) ? $_SESSION['nivel'] : $JWToken->nivel;
$permisos = $model->getPermisosRol($nivel);
$permiso = $permisos['Sedes'];

if (!isset($permiso["Consultar"])) {
    die('<script> window.location = "?url=home" </script>');
}


if (isset($_POST['notificacion'])) {
    $objModel->getNotificacion();
}

if (isset($_POST['getPermisos'], $permiso['Consultar'])) {
    die(json_encode($permiso));
}



if (isset($_POST['registrar'], $_POST['nombre'], $_POST['telefono'], $_POST['direccion'], $permiso['Registrar'])) {
    $res = $model->getAgregarSede($_POST['nombre'], $_POST['telefono'], $_POST['direccion']);
    die(json_encode($res));
}

if (isset($_POST['select'], $_POST['id'], $permiso['Editar'])) {
    $respuesta = $model->mostrarSe($_POST['id']);
    die(json_encode($respuesta));
}

if (isset($_POST['editar'], $_POST['id'], $_POST['nombre'], $_POST['telefono'], $_POST['direccion'], $permiso['Editar'])) {
    $res = $model->getEditarSede($_POST['nombre'], $_POST['telefono'], $_POST['direccion'], $_POST['id']);
    die(json_encode($res));
}

if (isset($_POST['eliminar'], $_POST['id'], $permiso['Eliminar'])) {
    $res = $model->getElimarSede($_POST['id']);
    die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);


if (file_exists('vista/interno/sedeVista.php')) {
    require_once('vista/interno/sedeVista.php');
} else {
    die("La vista no existe.");
}
