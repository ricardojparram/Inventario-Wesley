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
  
    if(isset($_POST['getPermisos'], $permiso['Consultar'])){
    	die(json_encode($permiso));
    }
    
	if(isset($_POST['mostrar']) && isset($_POST['bitacora'])){
	$res = $objModel->mostrarCompras($_POST['bitacora']);
	die(json_encode($res));
	}

	if(isset($_POST['detalleCompra'])&& isset($_POST['id'])){
		$res = $objModel->productoDetalle($_POST['id']);
		die(json_encode($res)); 
	}

	if(isset($_POST['select'],$permiso['Consultar'])){
		$res= $objModel->mostrarSelect();
		die(json_encode($res));
	}

	if(isset($_POST['proveedor'] , $_POST['orden'] , $_POST['fecha'] , $_POST['monto'] , $_POST['productos'], $permiso["Consultar"])){
		$res = $objModel->getRegistrarCompra($_POST['proveedor'] , $_POST['orden'] , $_POST['fecha'] , $_POST['monto'] , $_POST['productos']);
		die(json_encode($res));
	}

	if (isset($_POST["borrar"],$_POST["id"],$permiso['Eliminar'])){
		$res = $objModel->getEliminarCompra($_POST["id"]);
		die(json_encode($res));  
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