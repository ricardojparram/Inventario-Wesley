<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\perfil as perfil;



$objModel = new perfil();

if (!isset($_SESSION['nivel'])) {
  die('<script> window.location = "?url=login" </script>');
}

// if (isset($_POST['notificacion'])) {
//   $objModel->getNotificacion();
// }

$permisos = $objModel->getPermisosRol($_SESSION['nivel']);

if (isset($_SESSION['cedula']) && isset($_POST['mostrar'])) {
  $data = $objModel->mostrarDatos($_SESSION['cedula']);
  die(json_encode($data));
}

if (isset($_POST['usuarios'], $_POST['lista'])) {
  $data = $objModel->mostrarUsuarios();
  die(json_encode($data));
}

if (isset($_POST['password'], $_POST['validarContraseña'])) {
  $data = $objModel->getValidarContraseña($_POST['password'], $_SESSION['cedula']);
  die(json_encode($data));
}

if (isset($_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $_SESSION['cedula'])) {

  $data = "";
  if (isset($_POST['borrar']))
    $data = $objModel->getEditar('', $_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $_SESSION['cedula'], $_POST['borrar']);
  else if (isset($_FILES['foto']))
    $data = $objModel->getEditar($_FILES['foto'], $_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $_SESSION['cedula']);

  die(json_encode($data));
}

if (isset($_SESSION['cedula'], $_POST['passwordAct'], $_POST['passwordNew'])) {
  $data = $objModel->getCambioContra($_SESSION['cedula'], $_POST['passwordAct'], $_POST['passwordNew']);
  die(json_encode($data));
}

if (isset($_GET['validarCedula'], $_GET["cedula"])) {
  $res = $objModel->getValidarCedula($_GET['cedula'], $_SESSION['cedula']);
  die(json_encode($res));
}

if (isset($_GET['validarCorreo'])) {
  $res = $objModel->getValidarCorreo($_GET['correo'], $_SESSION['cedula']);
  die(json_encode($res));
}

$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/perfilVista.php")) {
  require_once("vista/interno/perfilVista.php");
}
