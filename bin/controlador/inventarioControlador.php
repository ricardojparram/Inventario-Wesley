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
$sede = (isset($_SESSION['id_sede'])) ? $_SESSION['id_sede'] : $JWToken->id_sede;
$cedula = (isset($_SESSION['cedula'])) ? $_SESSION['cedula'] : $JWToken->cedula;

$objModel = new inventario(['cedula' => $cedula, 'sede' => $sede]);
$permisos = $objModel->getPermisosRol($nivel);
$permiso = $permisos['Inventario'];

if (!isset($permiso["Consultar"])) {
    die('<script> window.location = "?url=login" </script>');
}

if (isset($_POST['getPermisos'], $permiso["Consultar"])) {
    die(json_encode($permiso));
}

if (isset($_GET['mostrar'], $_GET['bitacora'])) {
    $res = $objModel->mostrarInventario($_GET['bitacora']);
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
