<?php  
  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use component\footer as footer;
  use modelo\tipoEmpleado as tipoEmpleado;


  $objModel = new tipoEmpleado();
  $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
  $permiso = $permisos['Tipo empleado'];

  if(!isset($_SESSION['nivel'])){
  	die('<script> window.location = "?url=login" </script>');
  }

  if(!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`);

  if(isset($_POST['notificacion'])) {
  	$objModel->getNotificacion();
  }


  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);
  $footer = new footer();

  if(file_exists('vista/interno/tipoEmpleadoVista.php')) {
  	require_once('vista/interno/tipoEmpleadoVista.php');
  }

?>