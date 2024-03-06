<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\ventas as ventas;

      if(!isset($_SESSION['nivel'])){
       die('<script> window.location = "?url=login" </script>');
     }

     $objModel = new ventas();
     $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
     $permiso = $permisos['Ventas'];

      if(!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`);

      if(isset($_POST['notificacion'])) {
        $objModel->getNotificacion();
      }

      if (isset($_POST['getPermisos']) && $permiso['Consultar'] == 1) {
        die(json_encode($permiso));
      }
      
       $mostrarC = $objModel->getMostrarCliente();

       if (isset($_POST['selectTipo']) && $permiso['Consultar'] == 1) {
         $res = $objModel->getMostrarMetodo();
         die(json_encode($res));
       }
       

      if (isset($_POST['mostrar']) && isset($_POST['bitacora'])) {
        $res = $objModel->getMostrarVentas($_POST['bitacora']);
         die(json_encode($res));  
      }

       if(isset($_POST['detalleV']) && $permiso['Consultar'] == 1) {
         $res = $objModel->getDetalleV($_POST['id']);
         die(json_encode($res));
       }

       if(isset($_POST['detalleTipo']) && $permiso['Consultar'] == 1) {
         $res = $objModel->getDetalleTipoPago($_POST['id']);
         die(json_encode($res));
       }

      if (isset($_POST['selectM']) && $permiso['Consultar'] == 1) {
        $res = $objModel->getMostrarMoneda();
        die(json_encode($res));
      }
       
      if(isset($_POST['select']) && $permiso['Consultar'] == 1) {
        $res = $objModel->getMostrarProducto();
        die(json_encode($res));
      }

      if(isset($_POST['cedula']) && isset($_POST['validar']) && $permiso['Consultar'] == 1){
       $res = $objModel->validarCliente($_POST['cedula']);
       die(json_encode($res));
      }

      if(isset($_GET['producto']) && isset($_GET['fill'])  && $permiso['Consultar'] == 1){
       $res = $objModel->productoDetalle($_GET['producto']);
       die(json_encode($res));
      }


      if(isset($_POST['cedula']) && $permiso['Registrar'] == 1){

       $res = $objModel->getAgregarVenta($_POST['cedula']);
       die(json_encode($res));

      }

      if(isset($_POST['producto']) && isset($_POST['precio']) && isset($_POST['cantidad']) && isset($_POST['id']) && $permiso['Registrar'] == 1){

      $res = $objModel->agregarVentaXProd($_POST['producto'] , $_POST['precio'] , $_POST['cantidad'], $_POST['id'] );
      die(json_encode($res));
       
     }

     if (isset($_POST['montoT']) && isset($_POST['id']) && $permiso['Registrar'] == 1) {
      $res =  $objModel->getPago($_POST['montoT'] , $_POST['id']);
      die(json_encode($res));
     }

     if(isset($_POST['tipoPago']) && isset($_POST['montoPorTipo']) && isset($_POST['id']) && isset($_POST['moneda']) && $permiso['Registrar'] == 1) {
      $res = $objModel->agregarDetallePago($_POST['tipoPago'] , $_POST['montoPorTipo'] , $_POST['id'] , $_POST['moneda']);
      die(json_encode($res));
     }

     if (isset($_POST['id']) && isset($_POST['factura']) ){
      $res = $objModel->ExportarFactura($_POST['id']);
      die(json_encode($res));
     }

     if(isset($_POST['validarCI']) && isset($_POST['id']) && $permiso['Consultar'] == 1){
      $res = $objModel->validarSelect($_POST['id']);
      die(json_encode($res));
     }

     if (isset($_POST["eliminar"]) && $permiso['Eliminar'] == 1) {
       $res = $objModel->getEliminar($_POST["id"]);
       die(json_encode($res));
     }

     $VarComp = new initcomponents();
     $header = new header();
     $menu = new menuLateral($permisos);


   if(file_exists("vista/interno/ventasVista.php")){
     require_once("vista/interno/ventasVista.php");
   }

?>