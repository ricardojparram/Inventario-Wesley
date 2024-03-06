<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use modelo\miscompras as miscompras;
  use component\tienda;
	



      if(!isset($_SESSION['nivel'])){
       die('<script> window.location = "?url=login" </script>');
     }

     $objModel = new miscompras();
 

      if (isset($_POST['mostrar']) && isset($_POST['bitacora'])) {
        
        $objModel->getMostrarVentas();
      }
      if(isset($_POST['detalleV'])) {
          $objModel->getDetalleV($_POST['id']);
       }
     if (isset($_POST['id']) && isset($_POST['factura']) ){
       $objModel->ExportarFactura($_POST['id']);
     }
      if(isset($_POST['detalleTipo'])) {
          $objModel->getDetalleTipoPago($_POST['id']);
       }

     if(isset($_POST['validarCI']) && isset($_POST['id'])){
      $objModel->validarSelect($_POST['id']);
     }

     if (isset($_POST["eliminar"]) && $permiso->eliminar == 1) {
       $MS = $objModel->eliminarVenta($_POST["id"]);
     }

     $VarComp = new initcomponents();
     $header = new header();
     $Nav = new tienda();


   if(file_exists("vista/inicio/miscomprasVista.php")){
     require_once("vista/inicio/miscomprasVista.php");
   }

?>