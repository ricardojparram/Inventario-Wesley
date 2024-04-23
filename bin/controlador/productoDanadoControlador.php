<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\productoDanado as productoDanado;

  if(!isset($_SESSION['nivel'])){
    die('<script> window.location = "?url=login" </script>');
}

  $objModel = new productoDanado();
  $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
  $permiso = $permisos['Producto dañado'];

  








  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);

  if(file_exists("vista/interno/productos/productoDañadoVista.php")){
    require_once("vista/interno/productos/productoDañadoVista.php");
  }else{
    die("La vista no existe.");
  }
  
?>