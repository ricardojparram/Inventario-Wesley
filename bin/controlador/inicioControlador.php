<?php

	use component\initcomponents as initcomponents;
	use component\tienda as tienda;
	use component\footerInicio as footerInicio;
	use modelo\inicio as inicio;

	$model = new inicio();

	if (isset($_POST['mostraC'])) {
	 	$model->mostrarCatalogo();
	}

	if (isset($_POST['mostraProductos']) && isset($_POST['id'])) {
		$model->rellenarDatos($_POST['id']);
	}
	
	if(isset($_POST['mostrarCategorias'])){
		$model->mostrarCategorias();
	}

	$VarComp = new initcomponents();	
	$tiendaComp = new tienda();
	$footer= new footerInicio(); 
	require "vista/inicio/inicioVista.php";	

?>