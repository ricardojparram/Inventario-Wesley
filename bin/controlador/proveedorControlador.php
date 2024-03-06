<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\proveedor as proveedor;

  if(!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

  $objModel = new proveedor();
  $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
  $permiso = $permisos['Proveedor'];

  if(!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

  if(isset($_POST['notificacion'])) {
    $objModel->getNotificacion();
  }

  if(isset($_POST['getPermisos'], $permiso["Consultar"])){
    die(json_encode($permiso));
  }

  if(isset($_POST['mostrar'], $permiso["Consultar"])){
    $objModel->mostrarProveedorAjax($_POST['bitacora']);
  }

  if(isset($_POST['rif'], $_POST['direccion'], $_POST['razon'], $_POST['telefono'], $_POST['contacto'], $permiso["Registrar"])){

    $objModel->getDatosPro($_POST['rif'], $_POST['direccion'], $_POST['razon'], $_POST['telefono'], $_POST['contacto']);

  }

  if(isset($_POST['select'], $permiso["Editar"])){
    $objModel->getItem($_POST['id']);
  }
  
  if(isset($_POST['rifEdit'], $_POST['direccionEdit'], $_POST['razonEdit'], $_POST['telefonoEdit'], $_POST['contactoEdit'], $_POST['id'], $permiso["Editar"])){

    $objModel->getEditar($_POST['rifEdit'], $_POST['direccionEdit'], $_POST['razonEdit'], $_POST['telefonoEdit'], $_POST['contactoEdit'], $_POST['id']);

  }

  if(isset($_POST['eliminar'], $permiso["Eliminar"])){
    $objModel->getEliminar($_POST['id']);
  }

  if(isset($_POST['rif'], $_POST['validar'])){
    $resultado = $objModel->getRif($_POST['rif']);
    die(json_encode($resultado));
  }
  


  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);

  if(file_exists("vista/interno/productos/proveedorVista.php")){
    require_once("vista/interno/productos/proveedorVista.php");
  }else{
    die("La vista no existe.");
  }
  
?>