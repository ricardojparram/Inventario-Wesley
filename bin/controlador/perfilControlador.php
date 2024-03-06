<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\perfil as perfil;



  $objModel = new perfil();
  
  if(!isset($_SESSION['nivel'])){
    die('<script> window.location = "?url=login" </script>');
  }

  if(isset($_POST['notificacion'])) {
    $objModel->getNotificacion();
  }

  $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
  
  if(isset($_SESSION['cedula']) && isset($_POST['mostrar'])) {
    $objModel->mostrarDatos($_SESSION['cedula']);
  }

  if(isset($_POST['usuarios'], $_POST['lista'])){
    $objModel->mostrarUsuarios();
  }

  if(isset($_POST['password'], $_POST['validarContraseña'])){
    $objModel->getValidarContraseña($_POST['password'], $_SESSION['cedula']);
  }

  if (isset($_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $_SESSION['cedula'])) {

    if(isset($_POST['borrar']))
      $objModel->getEditar('', $_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $_SESSION['cedula'], $_POST['borrar']);
    
    if(isset($_FILES['foto']))
      $objModel->getEditar($_FILES['foto'], $_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['email'], $_SESSION['cedula']);

  }

  if(isset($_SESSION['cedula']) && isset($_POST['passwordAct']) && isset($_POST['passwordNew']) && isset($_POST['passwordNewR'])) {
    
    $objModel->getCambioContra($_SESSION['cedula'], $_POST['passwordAct'], $_POST['passwordNew'], $_POST['passwordNewR']);
  }

  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);
  
  if(file_exists("vista/interno/perfilVista.php")){
    require_once("vista/interno/perfilVista.php");
  } 
  

  ?>
