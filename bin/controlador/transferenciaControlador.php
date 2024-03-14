<?php  

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\sedeEnvio as sedeEnvio;

	if(!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

	$model = new sedeEnvio();

	$permisos = $model->getPermisosRol($_SESSION['nivel']);
	$permiso = $permisos['Sedes de Envio'];

	if(!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');


	if(isset($_POST['notificacion'])) {
		$objModel->getNotificacion();
	}

	if(isset($_POST['getPermisos'], $permiso['Consultar'])){
		die(json_encode($permiso));
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