<?php 

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\tipo as tipo;
 
    if(!isset($_SESSION['nivel'])){
		die('<script> window.location = "?url=login" </script>');
	}

	$objModel = new tipo();
    $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
    $permiso = $permisos['Tipo'];

    if(!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`); 

    if(isset($_POST['notificacion'])) {
    	$objModel->getNotificacion();
    }


     if(isset($_POST['getPermisos'])&& $permiso['Consultar'] == 1){
    	die(json_encode($permiso));
    }
    

	if(isset($_POST["mostrar"]) && isset($_POST['bitacora'])){
		$res = $objModel->getMostrarTipo($_POST['bitacora']);
		die(json_encode($res));
		
	}

	if (isset($_POST["tipo"]) && $permiso['Registrar'] == 1){
		$res = $objModel->getAgregarTipo($_POST["tipo"]);
	 	die(json_encode($res));

	}

if (isset($_POST["borrar"]) && isset($_POST["id"]) && $permiso['Eliminar'] == 1){
	$objModel->getEliminarTipo($_POST["id"]);
}
 if (isset($_POST["editar"]) && isset($_POST["tipoedit"]) && $permiso['Consultar'] == 1){
 	$eres = $objModel->mostrarlot($_POST["tipoedit"]);
	die(json_encode($res));
 }
 if(isset($_POST["tipoEditar"]) && isset($_POST["tipoedit"]) && $permiso['Editar'] ==1){
 	$res = $objModel->getEditarTipo($_POST["tipoEditar"], $_POST["tipoedit"]);
	die(json_encode($res));
 	$res = $objModel->getEditarTipo($_POST["tipoEditar"], $_POST["tipoedit"]);
	die(json_encode($res));
 }

	$VarComp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);


	if(file_exists("vista/interno/productos/tipoVista.php")){
		require_once("vista/interno/productos/tipoVista.php");
	}else{
		die('La vista no existe.');
	}

?>