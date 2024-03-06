<?php  

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\envios;

	if(!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

	$model = new envios();
	$permisos = $model->getPermisosRol($_SESSION['nivel']);
	$permiso = $permisos['Envios'];

	if(!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

	if(isset($_POST['getPermisos'], $permiso["Consultar"])){
		die(json_encode($permiso));
	}

	if(isset($_POST['precio_envio'])){
		$res = $model->calcularPrecioEnvio();
		die(json_encode($res));
	}

	if(isset($_POST['mostrar'], $_POST['bitacora'], $permiso['Consultar'])){
		$res = $model->mostrarEnvios($_POST['bitacora']);
		die(json_encode($res));
	}

	if(isset($_POST['id_envio'], $_POST['estado'], $permiso['Asignar estado'])){
		$res = $model->getComprobacion($_POST['id_envio'], $_POST['estado']);
		die(json_encode($res));
	}
	
	$comp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);

	if(!file_exists("vista/interno/comprobarPagoVista.php")){
		die("No existe la vista del módulo");
	}

	require_once("vista/interno/enviosVista.php");

?>