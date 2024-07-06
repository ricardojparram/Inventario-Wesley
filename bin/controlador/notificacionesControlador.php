<?php
	use modelo\notificaciones;

	$model = new notificaciones();


	if (isset($_POST['notificaciones'])) {
	$res = $model->getNotificaciones();
	die(json_encode($res));
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