<?php 

	use component\initcomponents as initcomponents;
	use component\tienda;
	use component\footerInicio as footerInicio;
	use modelo\catalogo as catalogo;

	$model = new catalogo();


	if(isset($_POST['mostrarCategorias'])){
		$model->mostrarCategorias();
	}
	if(isset($_POST['mostrarCatalogo'])){
		$model->mostrarCatalogo();
	}

	if(!file_exists("vista/inicio/catalogoVista.php")){
		die("No existe la vista del módulo");
	}

	$VarComp = new initcomponents();	
	$tiendaComp = new tienda();
	$footer= new footerInicio(); 

	require_once("vista/inicio/catalogoVista.php");

?>