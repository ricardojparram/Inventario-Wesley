<?php  

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\transferencia;

	if(!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

	$model = new transferencia();

	$permisos = $model->getPermisosRol($_SESSION['nivel']);
	$permiso = $permisos['Transferencia'];

	if(!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

	if(isset($_POST['notificacion'])) {
		$model->getNotificacion();
	}

	if(isset($_POST['getPermisos'], $permiso['Consultar'])){
		die(json_encode($permiso));
	}

	if(isset($_POST['mostrar'], $permiso["Consultar"])){
    $res = $model->mostrarTransferencias($_POST['bitacora']);
		die(json_encode($res));
	}

	$VarComp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);


	if(file_exists('vista/interno/transferenciaVista.php')){
		require_once('vista/interno/transferenciaVista.php');
	}else {
		die("La vista no existe.");
	}

?>
