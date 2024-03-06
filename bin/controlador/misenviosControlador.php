<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\misenvios as misenvios;

  use component\nav as nav;
  use component\carDesplegable as carDesplegable;
  



      if(!isset($_SESSION['nivel'])){
       die('<script> window.location = "?url=login" </script>');
     }

     $objModel = new misenvios();
     $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
     $permiso = $permisos['Ventas'];


      if(isset($_SESSION['nivel'])){
      if($_SESSION['nivel'] != 1){
      die('<script> window.location = "?url=home" </script>');
    }
      }else{
        die('<script> window.location = "?url=login" </script>');
    }


      if($permiso->status != 1) die(`<script> window.location = "?url=home" </script>`);

      if (isset($_POST['getPermisos']) && $permiso->status == 1 || $permiso->status == 3) {
        die(json_encode($permiso));
      }
      
      $mostrarC = $objModel->getMostrarCliente();
      $mostrerM = $objModel->getMostrarMetodo();

      if (isset($_POST['mostrar']) && isset($_POST['bitacora'])) {
        
        $objModel->MostrarVentas();
      }

       if(isset($_POST['detalleV']) && $permiso->consultar == 1) {
          $objModel->getDetalleV($_POST['id']);
       }

      if (isset($_POST['selectM']) && $permiso->status == 1) {
         $objModel->getMostrarMoneda();
      }
       
      if(isset($_POST['select']) && $permiso->status == 1) {
         $objModel->getMostrarProducto();
      }

      if(isset($_POST['cedula']) && isset($_POST['validar']) && $permiso->status == 1){
        $objModel->validarCliente($_POST['cedula']);
      }

      if(isset($_GET['producto']) && isset($_GET['fill'])  && $permiso->status == 1){
        $objModel->productoDetalle($_GET['producto']);
      }


      if(isset($_POST['cedula']) && isset($_POST['montoT']) && isset($_POST['metodo']) && isset($_POST['moneda']) && $permiso->registrar == 1){

        $objModel->getAgregarVenta($_POST['cedula'] , $_POST['montoT'] , $_POST['metodo'] , $_POST['moneda'] );

      }

      if(isset($_POST['producto']) && isset($_POST['precio']) && isset($_POST['cantidad']) && isset($_POST['id']) && $permiso->registrar == 1){

       $objModel->AgregarVentaXProd($_POST['producto'] , $_POST['precio'] , $_POST['cantidad'], $_POST['id'] );
       
     }

     if (isset($_POST['id']) && isset($_POST['factura']) ){
       $objModel->ExportarFactura($_POST['id']);
     }

     if(isset($_POST['validarCI']) && isset($_POST['id']) && $permiso->status == 1){
      $objModel->validarSelect($_POST['id']);
     }

     if (isset($_POST["eliminar"]) && $permiso->eliminar == 1) {
       $MS = $objModel->eliminarVenta($_POST["id"]);
     }

     $VarComp = new initcomponents();
     $header = new header();
     $menu = new menuLateral($permisos);
     $Nav = new nav();
  $Car = new carDesplegable();


   if(file_exists("vista/inicio/misenviosVista.php")){
     require_once("vista/inicio/misenviosVista.php");
   }

?>