<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\moneda as moneda;
  
  $objModel = new moneda();
  $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
  $permiso = $permisos['Moneda'];

  //$objModel->actualizarMoneda();

  if(!isset($_SESSION['nivel'])){
    die('<script> window.location = "?url=login" </script>');
  }

  if(!isset($permiso['Consultar'])) die('<script> window.location = "?url=home" </script>');

  if(isset($_POST['notificacion'])) {
    $objModel->getNotificacion();
  }

  if(isset($_POST['getPermisos']) && isset($permiso['Consultar'])){
    die(json_encode($permiso));
  }
  
  if (isset($_POST['datos']) && isset($permiso['Consultar'])) {
    ($_POST['bitacora'] == 'true')
      ? $objModel->getMoneda(true)
      : $objModel->getMoneda();
  }

  if (isset($_POST['moneda']) && isset($_POST['name']) && isset($permiso['Registrar'])) {
    $objModel->getAgregarMoneda($_POST['name']);
  }

  if (isset($_POST['edit']) && isset($_POST['id']) && isset($permiso['Editar'])) {
     $objModel->mostrarM($_POST['id']);
  }

  if (isset($_POST['nameEdit']) && $_POST['id'] && isset($permiso['Editar'])) {
    $objModel->getEditarM($_POST['nameEdit'], $_POST['id']);
  }

  if (isset($_POST['delete']) && isset($_POST['id']) && isset($permiso['Eliminar'])) {
    $objModel->getEliminarM($_POST['id']);
  }

  if (isset($_POST["mostrar"], $_POST['idHistory'],$permiso['Consultar'])) {
    $objModel->getMostrarCambio($_POST['idHistory']);
  }

  if (isset($_POST['select'])) {
    $objModel->SelectM();
  }

  if(isset($_POST["cambio"]) && isset($_POST["tipo"])&& isset($permiso['Registrar'])) {

    $objModel->getAgregarCambio($_POST["cambio"], $_POST["tipo"]);
  } 



  if (isset($_POST["borrar"]) && isset($_POST["id"]) && isset($permiso['Eliminar'])) {
    $objModel->getEliminarCambio($_POST["id"]);
  }

  if (isset($_POST["unico"]) && isset($_POST["editar"]) && isset($permiso['Editar'])) {

    
    $objModel->mostrarUnico($_POST["unico"]);
  }

   if(isset($_POST["cambioEdit"]) && isset($_POST["tipoEdit"]) && isset($_POST["unico"]) && isset($permiso['Editar'])) {

    $objModel->getEditarCambio($_POST["cambioEdit"], $_POST["tipoEdit"], $_POST["unico"]);  
    die("2");
  } 

  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);

  if(file_exists("vista/interno/configuraciones/monedaVista.php")){
    require_once("vista/interno/configuraciones/monedaVista.php");
  }


?>