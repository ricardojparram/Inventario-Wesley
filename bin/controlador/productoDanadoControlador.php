<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\productoDanado as productoDanado;

  if(!isset($_SESSION['nivel'])){
    die('<script> window.location = "?url=login" </script>');
}

  $model = new productoDanado();
  $permisos = $model->getPermisosRol($_SESSION['nivel']);
  $permiso = $permisos['Producto dañado'];

  
if (!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

if (isset($_GET['getPermisos'], $permiso['Consultar'])) {
    die(json_encode($permiso));
}

if (isset($_GET['mostrar'], $_GET['bitacora'], $permiso['Consultar'])) {
    $res = $model->mostrarDescargos($_GET['bitacora']);
    die(json_encode($res));
}

if (isset($_GET['detalle'], $_GET['id'], $permiso["Consultar"])) {
    $res = $model->getMostrarDetalle($_GET['id']);
    die(json_encode($res));
}

if (isset($_GET['select_producto'], $permiso["Consultar"])) {
    $res = $model->mostrarProductos();
    die(json_encode($res));
}

if (isset($_POST['exportar'])){
  $res = $model->exportar();
  die(json_encode($res));
}



  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);

  if(file_exists("vista/interno/productos/productoDanadoVista.php")){
    require_once("vista/interno/productos/productoDanadoVista.php");
  }else{
    die("La vista no existe.");
  }
  
?>