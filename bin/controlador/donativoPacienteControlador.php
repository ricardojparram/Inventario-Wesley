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
      

     $VarComp = new initcomponents();
     $header = new header();
     $menu = new menuLateral($permisos);


   if(file_exists("vista/interno/donaciones/donativoPacienteVista.php")){
     require_once("vista/interno/donaciones/donativoPacienteVista.php");
   }

?>