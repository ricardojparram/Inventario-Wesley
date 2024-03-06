<?php 

	use component\initcomponents as initcomponents;
	use component\header as header;
	use component\menuLateral as menuLateral;
	use modelo\compras as compras;

	
	if(!isset($_SESSION['nivel'])){
		die('<script> window.location = "?url=login" </script>');
	}

	$objModel = new compras();
	$permisos = $objModel->getPermisosRol($_SESSION['nivel']);
    $permiso = $permisos['Compras'];


    if(!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`); 

    if(isset($_POST['notificacion'])) {
    	$objModel->getNotificacion();
    }

    $proveedores = $objModel->mostrarProveedor();
  
    if(isset($_POST['getPermisos'])&& $permiso['Consultar'] ==1){
    	die(json_encode($permiso));
    }
    
	if(isset($_POST['mostrar']) && isset($_POST['bitacora'])){
		($_POST['bitacora'] == 'true')
		? $objModel->mostrarCompras(true)
		: $objModel->mostrarCompras();
	}

  
	if (isset($_POST['selectM'])&& $permiso['Consultar'] == 1) {
		$objModel->mostrarMoneda();
	}

	if(isset($_POST['select'])&& $permiso['Consultar'] == 1){
		$objModel->mostrarSelect();
	}

	if(isset($_GET['producto']) && isset($_GET['fill'])&& $permiso['Consultar'] == 1){
		$objModel->productoDetalle($_GET['producto']);
	}

	if(isset($_POST['orden']) && isset($_POST['validar'])&& $permiso['Consultar'] == 1){
		$objModel->getOrden($_POST['orden']);
	}

	if(isset($_POST['proveedor']) && isset($_POST['orden']) && isset($_POST['fecha']) && isset($_POST['cambio']) && isset($_POST['montoT'])&& $permiso['Registrar'] == 1){
		$objModel->getCompras($_POST['proveedor'], $_POST['orden'], $_POST['fecha'], $_POST['cambio'], $_POST['montoT']);
	}
	if(isset($_POST['cantidad']) && isset($_POST['precio']) && isset($_POST['producto']) && isset($_POST['id'])&& $permiso['Registrar'] == 1){
		$objModel->getProducto($_POST['cantidad'], $_POST['precio'], $_POST['producto'], $_POST['id']);
	}

	if(isset($_POST['detalleCompra']) && isset($_POST['id'])&& $permiso['Consultar'] == 1){
		$objModel->getDetalleCompra($_POST['id']);
	}

	if(isset($_POST['eliminar']) && isset($_POST['id']) && $permiso['Eliminar'] == 1){
		$objModel->getEliminarCompra($_POST['id']);
	}
	  
	$VarComp = new initcomponents();
	$header = new header();
	$menu = new menuLateral($permisos);

	if(file_exists("vista/interno/comprasVista.php")){
		require_once("vista/interno/comprasVista.php");
	}else{
		die('La vista no existe');
	}




?>