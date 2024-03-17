<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\laboratorio as laboratorio;

  if(!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

  $objModel = new laboratorio();
  $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
  $permiso = $permisos['Laboratorio'];

  if(!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

  if(isset($_POST['getPermisos'], $permiso["Consultar"])){
    die(json_encode($permiso));
  }

  if(isset($_POST['mostrar'], $permiso["Consultar"])){
    $res = $objModel->mostrarLaboratorios($_POST['bitacora']);
    die(json_encode($res));
  }

  if(isset($_POST['rif'], $_POST['direccion'], $_POST['razon'],$permiso["Registrar"])){

    $res= $objModel->getRegistrarLaboratorio($_POST['rif'], $_POST['direccion'], $_POST['razon'],$permiso["Registrar"]);
    die(json_encode($res));


  }

  if(isset($_POST['select'], $_POST['id'], $permiso["Editar"])){
    $res= $objModel->getItem($_POST['id']);
    die(json_encode($res));
  }

  if(isset($_POST['rifEdit'], $_POST['direccionEdit'], $_POST['razonEdit'], $_POST['id'], $permiso["Editar"])){

    $res = $objModel->getEditar($_POST['rifEdit'], $_POST['direccionEdit'], $_POST['razonEdit'], $_POST['id'], $permiso["Editar"]);
    die(json_encode($res));

  }

  if(isset($_POST['eliminar'], $permiso["Eliminar"])){
    $res = $objModel->getEliminar($_POST['id']);
    die(json_encode($res));
  }

  if(isset($_POST['rif'], $_POST['validar'], $_POST['edit'])){
    $res = $objModel->getRif($_POST['rif'], $_POST['edit']);
    die(json_encode($res));
  }
  


  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);

  if(file_exists("vista/interno/productos/laboratorioVista.php")){
    require_once("vista/interno/productos/laboratorioVista.php");
  }else{
    die("La vista no existe.");
  }
  
?>