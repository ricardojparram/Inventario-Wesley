<?php 

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\medida as medida;
 
    if(!isset($_SESSION['nivel'])){
		die('<script> window.location = "?url=login" </script>');
	}

	$objModel = new medida;
    $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
    $permiso = $permisos['Medida'];

    if(!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`); 

    if(isset($_POST['notificacion'])) {
    	$objModel->getNotificacion();
    }


     if(isset($_POST['getPermisos'])&& $permiso['Consultar'] == 1){
    	die(json_encode($permiso));
    }
    

	if(isset($_POST["mostrar"]) && isset($_POST['bitacora'])){
		($_POST['bitacora'] == 'true')
		? $objModel->getMostrarMedida(true)
		: $objModel->getMostrarMedida();
	}

	if (isset($_POST["medida"]) && $permiso['Registrar'] == 1){
		$objModel->getAgregarMedida($_POST["medida"]);

	}

if (isset($_POST["borrar"]) && isset($_POST["id"]) && $permiso['Eliminar'] == 1){
	$objModel->getEliminarMedida($_POST["id"]);
}
 if (isset($_POST["editar"]) && isset($_POST["medidaedit"]) && $permiso['Consultar'] == 1){
 	$objModel->mostrarlot($_POST["medidaedit"]);
 }
 if(isset($_POST["medidaEditar"]) && isset($_POST["medidaoedit"]) && $permiso['Editar'] ==1){
 	$objModel->getEditarTipo($_POST["medidaEditar"], $_POST["medidaedit"]);
 }

	$VarComp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);


	if(file_exists("vista/interno/productos/medidaVista.php")){
		require_once("vista/interno/productos/medidaVista.php");
	}else{
		die('La vista no existe.');
	}

?>