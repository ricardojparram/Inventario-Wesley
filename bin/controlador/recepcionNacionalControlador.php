<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\recepcionNacional;

  if(!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

  $objModel = new recepcionNacional();
  $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
  $permiso = $permisos['Recepcion nacional'];

  if(!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

  if(isset($_POST['getPermisos'], $permiso["Consultar"])){
    die(json_encode($permiso));
  }

  if(isset($_POST['mostrar'], $permiso["Consultar"])){
    $res = $objModel->mostrarRecepciones($_POST['bitacora']);
    die(json_encode($res));
  }


  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);

  if(file_exists("vista/interno/productos/recepcionNacionalVista.php")){
    require_once("vista/interno/productos/recepcionNacionalVista.php");
  }else{
    die("La vista no existe.");
  }
  
?>