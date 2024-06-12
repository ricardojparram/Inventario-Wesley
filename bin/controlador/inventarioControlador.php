<?php


use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\inventario as inventario;
use utils\JWTService;

$JWToken = JWTService::validateSession();
if (!isset($_SESSION['nivel']) && !$JWToken) {
    die('<script> window.location = "?url=login" </script>');
}

$nivel = (isset($_SESSION['nivel'])) ? $_SESSION['nivel'] : $JWToken->nivel;
$objModel = new inventario();
$permisos = $objModel->getPermisosRol($nivel);
$permiso = $permisos['Inventario'];

if (!isset($permiso["Consultar"])) {
    die('<script> window.location = "?url=login" </script>');
}

//if(isset($_POST['notificacion'])) {
//$objModel->getNotificacion();
//}

if (isset($_POST['getPermisos'], $permiso["Consultar"])) {
    die(json_encode($permiso));
}

if (isset($_POST['mostrar'])) {
    $res = $objModel->mostrarInventario($_POST['bitacora']);
    die(json_encode($res));
}



$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/productos/inventarioVista.php")) {
    require_once("vista/interno/productos/inventarioVista.php");
} else {
    die("La vista no existe.");
}
