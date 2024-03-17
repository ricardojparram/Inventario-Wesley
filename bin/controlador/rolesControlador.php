<?php 

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\roles as roles;

	if(!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

	$model = new roles();
	$permisos = $model->getPermisosRol($_SESSION['nivel']);
	$permiso = $permisos['Roles'];

	if(!isset($permiso['Consultar'])) die('<script> window.location = "?url=home" </script>');

	if(isset($_POST['notificacion'])) {
		$objModel->getNotificacion();
	}

	if(isset($_POST['getPermisos'], $permiso['Consultar'])){
		die(json_encode($permisos['Roles']));
	}

	if(isset($_POST['rol'], $_POST['registrar'])){
		$res = $model->getAgregarRol($_POST['rol']);	
		die(json_encode($res));
	}

	if(isset($_POST['select'], $_POST['id'])){
		$res = $model->getMostrarRol($_POST['id']);	
		die(json_encode($res));
	}

	if(isset($_POST['id'], $_POST['editar'])){
		$res = $model->getEditarRol($_POST['id'],$_POST['nombre']);	
		die(json_encode($res));
	}

	if(isset($_POST['id'], $_POST['eliminar'])){
		$res = $model->getEliminarRol($_POST['id']);	
		die(json_encode($res));
	}

	if(isset($_POST['mostrar'], $_POST['bitacora'], $permiso['Consultar'])){
		$res = $model->mostrarRoles($_POST['bitacora']);
		die(json_encode($res));
	}

	if(isset($_POST['mostrar_permisos'], $_POST['id'], $permiso['Modificar acciones'])){
		$res = $model->getPermisos($_POST['id']);
		die(json_encode($res));
	}

	if(isset($_POST['datos_permisos'], $_POST['id'], $permiso['Modificar acciones'])){
		$res = $model->getDatosPermisos($_POST['datos_permisos'], $_POST['id']);
		die(json_encode($res));
	}

	$VarComp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);

	if(!file_exists("vista/interno/rolesVista.php")){
		die("La vista no existe.");
	}
	require_once("vista/interno/rolesVista.php");

?>