<?php 

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\home as home;

	if(!isset($_SESSION['nivel'])){
    	die('<script> window.location = "?url=login" </script>');
  	}
	$objModel = new home();
	$permisos = $objModel->getPermisosRol($_SESSION['nivel']);

  	$VarComp = new initcomponents();
   	$header = new header();
   	$menu = new menuLateral($permisos);

  	if(file_exists("vista/interno/ayudaVista.php")){
    	require_once("vista/interno/ayudaVista.php");
  	}

?>