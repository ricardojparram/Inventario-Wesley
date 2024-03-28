<?php  

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\sede as sede;

	if(!isset($_SESSION['nivel'])) die('<script> window.location = "?url=login" </script>');

	$model = new sede();

	$permisos = $model->getPermisosRol($_SESSION['nivel']);
	$permiso = $permisos['Sedes'];

	if(!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');


	if(isset($_POST['notificacion'])) {
		$objModel->getNotificacion();
	}

	

	if(isset($_POST['getPermisos'], $permiso['Consultar'])){
		die(json_encode($permiso));
	}

	if(isset($_POST['mostrar'], $_POST['bitacora'])){
		$res = $model->mostrarSedes($_POST['bitacora']);
		die(json_encode($res));
	}

	if(isset($_POST['validar'], $_POST['empresa'], $permiso['Consultar'])){
		$validar = $model->getValidarEmpresa($_POST['empresa']);
		die(json_encode(['resultado' => $validar]));
	}

	if(isset($_POST['registrar'], $_POST['empresa'], $_POST['estado'], $_POST['nombre'], $_POST['ubicacion'], $permiso['Registrar'])){
		$res = $model->getRegistrarSede($_POST['empresa'], $_POST['estado'], $_POST['nombre'], $_POST['ubicacion']);
		die(json_encode($res));
	}

	if(isset($_POST['select'], $_POST['id'], $permiso['Editar'])){
		$respuesta = $model->getSede($_POST['id']);
		die(json_encode($respuesta));
	}

	if(isset($_POST['editar'], $_POST['id'], $_POST['empresa'], $_POST['estado'], $_POST['nombre'], $_POST['ubicacion'], $permiso['Editar'])){
		$res = $model->getEditarSede( $_POST['empresa'], $_POST['estado'], $_POST['nombre'], $_POST['ubicacion'], $_POST['id']);
		die(json_encode($res));
	}

	if(isset($_POST['eliminar'], $_POST['id'], $permiso['Eliminar'])){
		$res = $model->getEliminarSede($_POST['id']);
		die(json_encode($res));
	}

	$VarComp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);


	if(file_exists('vista/interno/sedeVista.php')){
		require_once('vista/interno/sedeVista.php');
	}else {
		die("La vista no existe.");
	}

?>