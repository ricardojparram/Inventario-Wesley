<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\perfil as perfil;
use utils\JWTService;


$JWToken = JWTService::validateSession();
if (!isset($_SESSION['nivel']) && !$JWToken) {
    die('<script> window.location = "?url=login" </script>');
}
$session = (isset($_SESSION['nivel'])) ? $_SESSION : $JWToken;

$objModel = new perfil($session);
$permisos = $objModel->getPermisosRol($session['nivel']);


if (isset($session['cedula']) && isset($_POST['mostrar'])) {
    $data = $objModel->mostrarDatos();
    die(json_encode($data));
}

if (isset($_POST['usuarios'], $_POST['lista'])) {
    $data = $objModel->mostrarUsuarios();
    die(json_encode($data));
}

if (isset($_POST['password'], $_POST['validarContraseña'], $session['cedula'])) {
    $data = $objModel->getValidarContraseña($_POST['password']);
    die(json_encode($data));
}

if (isset($_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $session['cedula'])) {
    $data = match (true) {
        isset($_POST['borrar']) => $objModel->getEditar('', $_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $session['cedula'], $_POST['borrar']),
        isset($_FILES['foto']) => $objModel->getEditar($_FILES['foto'], $_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $session['cedula']),
        isset($_POST['app']) => $objModel->getEditar('', $_POST['nombre'], $_POST['apellido'], $session['cedula'], $_POST['email'], $session['cedula'])
    };

    die(json_encode($data));
}

if (isset($session['cedula'], $_POST['passwordAct'], $_POST['passwordNew'])) {
    $data = $objModel->getCambioContra($session['cedula'], $_POST['passwordAct'], $_POST['passwordNew']);
    die(json_encode($data));
}

if (isset($session['cedula'], $_POST['data'], $_POST['passwordNew'])) {
    $data = $objModel->getCambioContra($session['cedula'], ['passwordAct' => $_POST['passwordAct'], 'passwordNew' => $_POST['passwordNew']]);
    die(json_encode($data));
}

if (isset($_GET['validarCedula'], $_GET["cedula"])) {
    $res = $objModel->getValidarCedula($_GET['cedula'], $session['cedula']);
    die(json_encode($res));
}

if (isset($_GET['validarCorreo'])) {
    $res = $objModel->getValidarCorreo($_GET['correo'], $session['cedula']);
    die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/perfilVista.php")) {
    require_once("vista/interno/perfilVista.php");
}
