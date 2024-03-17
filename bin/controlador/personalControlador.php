<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
use modelo\personal as personal;

if (!isset($_SESSION['nivel'])) {
  die('<script> window.location = "?url=login" </script>');
}

$objModel = new personal();
$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
$permiso = $permisos['Personal'];

if (!isset($permiso['Consultar']))
  die('<script> window.location = "?url=home" </script>');

if (isset($_POST['notificacion'])) {
  $objModel->getNotificacion();
}

if (isset($_POST['getPermisos']) && isset($permiso['Consultar'])) {
  die(json_encode($permiso));
}

if (isset($_POST['mostrar']) && isset($permiso['Consultar'])) {
  ($_POST['bitacora'] == 'true')
    ? $objModel->mostrarClientes(true)
    : $objModel->mostrarClientes();
}

if (isset($_GET['validar'])) {
  $objModel->getValidarC($_GET['cedula'], $_GET['idVal']);
  //var_dump($_GET['cedula'], $_GET['idVal']);
  //die();
}

if (isset($_GET['validarE'])) {
  $objModel->getValidarE($_GET['correo'], $_GET['idVal']);
  // var_dump($_GET['correo'], $_GET['idVal']);
  // die();
}

if (isset($_POST['nomClien']) && isset($_POST['apeClien']) && isset($_POST['cedClien']) && isset($_POST['direcClien']) && isset($_POST['telClien']) && isset($_POST['emailClien']) && isset($permiso['Registrar'])) {
  $objModel->getRegistrarClientes($_POST['nomClien'], $_POST['apeClien'], $_POST['cedClien'], $_POST['direcClien'], $_POST['telClien'], $_POST['emailClien']);
}

if (isset($_POST['cedulaDel']) && isset($_POST['eliminar']) && isset($permiso['Eliminar'])) {
  $objModel->eliminarClientes($_POST['cedulaDel']);
}

if (isset($_POST['idCed']) && isset($_POST['unico']) && isset($permiso['Editar'])) {
  $objModel->unicoCliente($_POST['idCed']);
}

if (isset($_POST['nomEdit']) && isset($_POST['apeEdit']) && isset($_POST['cedEdit']) && isset($_POST['direcEdit']) && isset($_POST['celuEdit']) && isset($_POST['emailEdit']) && isset($_POST['idCed']) && isset($permiso['Editar'])) {
  $respuesta = $objModel->getEditar($_POST['nomEdit'], $_POST['apeEdit'], $_POST['cedEdit'], $_POST['direcEdit'], $_POST['celuEdit'], $_POST['emailEdit'], $_POST['idCed']);
}


$VarComp = new initcomponents();
$header = new header();
$menu = new menuLateral($permisos);

if (file_exists("vista/interno/personalVista.php")) {
  require_once("vista/interno/personalVista.php");
}

?>