<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\home as home;

  if(isset($_SESSION['nivel'])) {
    if ($_SESSION['nivel'] == 4 ) {
      die('<script> window.location = "?url=login" </script>');
    }
  }else {
    die('<script> window.location = "?url=login" </script>');
  }

  $objModel = new home();
  $permisos = $objModel->getPermisosRol($_SESSION['nivel']);

  if (isset($_POST['clien'])) {
    $objModel->mostrarClientes();
  }

  if(isset($_POST['grafico'])){
    $objModel->getGrafico();
  }

  if(isset($_POST['ventas']) && isset($_POST['opcionV'])){
    $objModel->getVentas($_POST['opcionV']);
  }

  if(isset($_POST['compras']) && isset($_POST['opcionC'])){
    $objModel->getCompras($_POST['opcionC']);
  }

  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);

  if(file_exists("vista/interno/homeVista.php")){
    require_once("vista/interno/homeVista.php");
  } 


    

?>