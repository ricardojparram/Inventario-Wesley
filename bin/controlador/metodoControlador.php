 <?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use component\footer as footer;
  use modelo\metodo as metodo;

    
    $objModel = new metodo();
    $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
    $permiso = $permisos['Metodo pago'];

    if(!isset($_SESSION['nivel'])){
      die('<script> window.location = "?url=login" </script>');
    }

    if(!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`);

    if(isset($_POST['notificacion'])) {
      $objModel->getNotificacion();
    }
    
    if(isset($_POST['getPermisos'])&& $permiso['Consultar'] == 1){
      die(json_encode($permiso));
    }
    
    if(isset($_POST['mostrar']) && isset($_POST['bitacora'])){
      $res = $objModel->getMostrarMetodo($_POST['bitacora']);
      die(json_encode($res));
    }


    if(isset($_POST["metodo"])&& $permiso['Registrar'] == 1) {
      $res = $objModel->getAgregarMetodo($_POST["metodo"]); 
      die(json_encode($res)); 
    } 

    if(isset($_POST['tipoPago'] , $_POST['validarTipoPago'] , $_POST['id']) ){
     $res = $objModel->validarMetodo($_POST['tipoPago'] , $_POST['id']);
     die(json_encode($res));
   }

   if (isset($_POST['validarE']) && isset($_POST['id'])){
    $res = $objModel->validarExitencia($_POST['id']);
    die(json_encode($res));
  }


  if (isset($_POST["eliminar"]) && isset($_POST["id"]) && $permiso['Eliminar'] == 1) {
    $res = $objModel->getEliminarMetodo($_POST["id"]);
    die(json_encode($res));
  }

  if (isset($_POST["id"]) && isset($_POST["editar"]) && $permiso['Consultar']){
   $res = $objModel->mostrarEdit($_POST["id"]);
   die(json_encode($res));
  }

  if(isset($_POST["tipoEdit"]) && isset($_POST["id"]) && $permiso['Editar']) {
    $res = $objModel->getEditarMetodo($_POST["tipoEdit"], $_POST["id"]);
    die(json_encode($res));
  }

  $VarComp = new initcomponents();
  $header = new header();
  $menu = new menuLateral($permisos);
  $footer = new footer();

  if(file_exists("vista/interno/configuraciones/metodoVista.php")){
    require_once("vista/interno/configuraciones/metodoVista.php");
  }

  ?>