<?php


use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\historialInventario as inventario;
use utils\JWTService;

$JWToken = JWTService::validateSession();
if (!isset($_SESSION['nivel']) && !$JWToken) {
    die('<script> window.location = "?url=login" </script>');
}

$nivel = (isset($_SESSION['nivel'])) ? $_SESSION['nivel'] : $JWToken['nivel'];
$objModel = new inventario();
$permisos = $objModel->getPermisosRol($nivel);
$permiso = $permisos['Inventario'];

if (!isset($permiso["Consultar"])) {
    die('<script> window.location = "?url=login" </script>');
}

if (isset($_GET['mostrar'], $_GET['bitacora'])) {
    $res = $objModel->mostrarHistorialInventario($_GET['bitacora']);
    die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/productos/historialInventarioVista.php")) {
    require_once("vista/interno/productos/historialInventarioVista.php");
} else {
    die("La vista no existe.");
}
