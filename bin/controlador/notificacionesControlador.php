<?php

use component\initcomponents as initcomponents;
use component\header as header;
use component\menuLateral as menuLateral;
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

if (isset($_POST['notificacionVista'], $_POST['notificationId'])) {
	$model->notificacionVista($_POST['notificationId']);
}



die("<script> window.location = 'login' </script>");
