<?php 

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\clase as clase;

	 if(!isset($_SESSION['nivel'])){
		die('<script> window.location = "?url=login" </script>');
	}

	$objModel = new clase();
	$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
	$permiso = $permisos['Clase'];

	 if(!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`); 

	 if(isset($_POST['notificacion'])) {
	 	$objModel->getNotificacion();
	 }

     if(isset($_POST['getPermisos'],$permiso['Consultar'])){
    	die(json_encode($permiso));
    }

	if(isset($_POST["clase"],$permiso['Registrar'])) {
		$res = $objModel->getAgregarClase($_POST["clase"]);
		die(json_encode ($res));
	}

	if(isset($_POST["mostrar"],$permiso['Consultar'])) {
		$res = $objModel->mostrarClase($_POST['bitacora']);
		die(json_encode ($res));
	}

	if(isset($_POST["id"],$_POST["borrar"], $permiso['Eliminar'])){
		$res = $objModel->getEliminar($_POST["id"]);
		die(json_encode($res));
	}

	if(isset($_POST["idedit"],$_POST["item"],$permiso['Consultar'])){
		$res = $objModel->getItem($_POST["idedit"]);
		die(json_encode ($res));
	}

	if(isset($_POST["claseEdit"],$_POST["idedit"], $permiso['Editar'])) {
		$res = $objModel->getEditarClase($_POST["claseEdit"], $_POST["idedit"]);
		die(json_encode ($res));
	}

	$VarComp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);


	if(file_exists("vista/interno/productos/claseVista.php")){
		require_once("vista/interno/productos/claseVista.php");
	}

?>