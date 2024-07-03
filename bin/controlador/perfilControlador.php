<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\perfil as perfil;
use utils\JWTService;

$objModel = new perfil();

$JWToken = JWTService::validateSession();
if (!isset($_SESSION['nivel']) && !$JWToken) {
    die('<script> window.location = "?url=login" </script>');
}
$cedula = (isset($_SESSION['cedula'])) ? $_SESSION['cedula'] : $JWToken->cedula;
$nivel = (isset($_SESSION['nivel'])) ? $_SESSION['nivel'] : $JWToken->nivel;

$permisos = $objModel->getPermisosRol($nivel);

if (isset($cedula) && isset($_POST['mostrar'])) {
    $data = $objModel->mostrarDatos($cedula);
    die(json_encode($data));
}

if (isset($_POST['usuarios'], $_POST['lista'])) {
    $data = $objModel->mostrarUsuarios();
    die(json_encode($data));
}

if (isset($_POST['password'], $_POST['validarContraseña'])) {
    $data = $objModel->getValidarContraseña($_POST['password'], $cedula);
    die(json_encode($data));
}

if (isset($_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $cedula)) {

    $data = "";
    if (isset($_POST['borrar'])) {
        $data = $objModel->getEditar('', $_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $cedula, $_POST['borrar']);
    } elseif (isset($_FILES['foto'])) {
        $data = $objModel->getEditar($_FILES['foto'], $_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $cedula);
    }

    die(json_encode($data));
}

if (isset($cedula, $_POST['passwordAct'], $_POST['passwordNew'])) {
    $data = $objModel->getCambioContra($cedula, $_POST['passwordAct'], $_POST['passwordNew']);
    die(json_encode($data));
}

if (isset($_GET['validarCedula'], $_GET["cedula"])) {
    $res = $objModel->getValidarCedula($_GET['cedula'], $cedula);
    die(json_encode($res));
}

if (isset($_GET['validarCorreo'])) {
    $res = $objModel->getValidarCorreo($_GET['correo'], $cedula);
    die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/perfilVista.php")) {
    require_once("vista/interno/perfilVista.php");
}
