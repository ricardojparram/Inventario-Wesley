<?php  

	use component\initcomponents as initcomponents;
	use component\tienda;
	use component\footerInicio as footerInicio;
	use modelo\carrito as carrito;

	$model = new carrito();

	if(isset($_POST['consultarCarrito'])){
		if(!isset($_SESSION['nivel'])){
			die(json_encode(['resultado' => 'error', 'msg' => 'No ha iniciado sesión.']));
		}
		$model->getCarritoUsuario($_SESSION['cedula']);
	}

	if(isset($_POST['precioDolar'])){
		$model->getPrecioDolar();
	}

	if(isset($_POST['añadirCarrito'], $_POST['productos'], $_SESSION['cedula'])){
		$model->getAgregarProducto($_SESSION['cedula'], $_POST['productos']);
	}


	if(isset($_POST['validar'], $_POST['productos'])){
		$model->getValidarStock($_POST['productos']);
	}

	if(isset($_POST['editar'], $_POST['cod_producto'], $_POST['cantidad'], $_SESSION['cedula'])){
		$model->getEditarProd($_POST['cod_producto'], $_POST['cantidad'], $_SESSION['cedula']);
	}

	if(isset($_POST['eliminar'], $_POST['id'])){
		$model->getEliminarProd($_POST['id'], $_SESSION['cedula']);
	}

	if(isset($_POST['vaciarCarrito'])){
		$model->vaciarCarrito($_SESSION['cedula']);
	}

	$VarComp = new initcomponents();
	$tiendaComp = new tienda();
	$footer= new footerInicio(); 
	
	require "vista/inicio/carritoVista.php";	

?>