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

if(isset($_POST['cedula'], $_POST['password'], $_POST['login'], $_POST['sede'])) {
    $res =  $objModel->getLoginSistema($_POST['cedula'], $_POST['password'], $_POST['sede']);
    die(json_encode($res));
}

$sedes = $objModel->getSedes();

$VarComp = new initcomponents();
$Nav = new tienda();

require_once("vista/sesion/loginVista.php");
