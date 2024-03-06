<?php 

	use component\initcomponents as initcomponents;
	use component\tienda;
	use component\footerInicio as footerInicio;

	if(!file_exists("vista/inicio/nosotrosVista.php")){
		die("No existe la vista del módulo");
	}

	$VarComp = new initcomponents();	
	$tiendaComp = new tienda();
	$footer= new footerInicio(); 

	require_once("vista/inicio/nosotrosVista.php");

?>