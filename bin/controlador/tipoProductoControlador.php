<?php 

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\tipoProducto as tipoProducto;

	 if(!isset($_SESSION['nivel'])){
		die('<script> window.location = "?url=login" </script>');
	}

	$objModel = new tipoProducto();
	$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
	$permiso = $permisos['Tipo'];

	 if(!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`); 

	 if(isset($_POST['notificacion'])) {
	 	$objModel->getNotificacion();
	 }

     if(isset($_POST['getPermisos'],$permiso['Consultar'])){
    	die(json_encode($permiso));
    }

	if(isset($_POST["tipoProducto"], $permiso['Registrar'])) {
		$res = $objModel->getAgregarTipoProducto($_POST["tipoProducto"]);
		die(json_encode($res));
	}

	if(isset($_POST["mostrar"],$permiso['Consultar'])) {
		$res = $objModel->mostrarTipoProducto($_POST['bitacora']);
		die(json_encode($res));
	}

	if(isset($_POST["id"],$_POST["borrar"], $permiso['Eliminar'])){
		$res =$objModel->getEliminar($_POST["id"]);
		die(json_encode($res));
	}

	if(isset($_POST["idedit"],$_POST["item"],$permiso['Consultar'])){
		$res = $objModel->getItem($_POST["idedit"]);
		die(json_encode ($res));
	}

	if(isset($_POST["tipoProductoEdit"],$_POST["idedit"],$permiso['Editar'])) {
		$res = $objModel->getEditarTipoProducto($_POST["tipoProductoEdit"], $_POST["idedit"]);
		die(json_encode ($res));
	}

	$VarComp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);


	if(file_exists("vista/interno/productos/tipoProductoVista.php")){
		require_once("vista/interno/productos/tipoProductoVista.php");
	}

?>