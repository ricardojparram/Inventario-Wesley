<?php
	use modelo\barraNotificaciones;

	$model = new barraNotificaciones();


	if (isset($_GET['notificaciones'])) {
	$res = $model->getNotificaciones();
	die(json_encode($res));
	}

	if(isset($_GET['registro'])){
		$res = $model->getRegistrarNotificacion();
	}

	if (isset($_GET['detalleNotificacion'], $_GET['notificationId'])) {
	$res = $model->mostrarDetalleNotificacion($_GET['notificationId']);
	die(json_encode($res));
	}

	if(isset($_POST['notificacionVista'], $_POST['notificationId'])) {
		$model->notificacionVista($_POST['notificationId']);
	}

	die("<script> window.location = '?url=login' </script>");

?>