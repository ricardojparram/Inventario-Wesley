<?php  

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\comprobarPago;

	if(!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

	$model = new comprobarPago();
	$permisos = $model->getPermisosRol($_SESSION['nivel']);
	$permiso = $permisos['Comprobar pago'];

	if(isset($_POST['getPermisos'], $permiso["Consultar"])){
		die(json_encode($permiso));
	}

	if(!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

	if(isset($_POST['mostrar'], $_POST['bitacora'], $permiso['Consultar'])){
		$res = $model->mostrarPagos($_POST['bitacora']);
		die(json_encode($res));
	}

	if(isset($_POST['id_pago'], $_POST['estado'])){
		$res = $model->getComprobacion($_POST['id_pago'], $_POST['estado']);
		die(json_encode($res));
	}

	if(isset($_POST['id_pago'], $_POST['detalle_pago'], $permiso['Consultar'])){
		$res = $model->getDetallePago($_POST['id_pago']);
		die(json_encode($res));
	}

	$comp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);

	if(!file_exists("vista/interno/comprobarPagoVista.php")){
		die("No existe la vista del módulo");
	}

	require_once("vista/interno/comprobarPagoVista.php");

?>