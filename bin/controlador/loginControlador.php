<?php

use component\initcomponents as initcomponents;
use modelo\login as login;
use component\tienda as tienda;

$objModel = new login();

if(isset($_SESSION['nivel'])) {
    die('<script>window.location = "?url=inicio" </script>');
}

if(isset($_GET['cedula'], $_GET['validar'])) {
    $res = $objModel->getValidarCedula($_GET['cedula']);
    die(json_encode($res));
}

if(isset($_POST['cedula'], $_POST['password'], $_POST['login'])) {
    $res =  $objModel->getLoginSistema($_POST['cedula'], $_POST['password']);
    die(json_encode($res));
}

$VarComp = new initcomponents();
$Nav = new tienda();

require_once("vista/sesion/loginVista.php");

