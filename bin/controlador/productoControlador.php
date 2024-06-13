<?php 

  use component\initcomponents as initcomponents;
  use component\header as header;
  use component\menuLateral as menuLateral;
  use modelo\productos as productos;

  $objModel = new productos();
  $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
  $permiso = $permisos['Producto'];
  
  if(!isset($permiso["Consultar"])) die('<script> window.location = "?url=home" </script>');

  if(isset($_POST['getPermisos'])&& $permiso['Consultar'] == 1){
    die(json_encode($permiso));
  }

  if(isset($_POST['notificacion'])) {
    $objModel->getNotificacion();
  }
   
   if (isset($_POST['mostrar']) && isset($_POST['bitacora'])) {
    $respuesta = ($_POST['bitacora'] == 'true')
      ? $objModel->MostrarProductos(true)
      : $objModel->MostrarProductos();
    die(json_encode($respuesta));
   }
   
   $mostraLab = $objModel->mostrarLaboratorio();
   $mostraPres = $objModel->mostrarPresentacion();
   $mostraTipo = $objModel->mostrarTipo();
   $mostrarClase = $objModel->mostrarClase();
   $mostrarTipoPro = $objModel->mostrarTipoPro();
 

  if(isset(
      $_POST['cod_producto'], $_POST['tipoprod'], $_POST['presentacion'], $_POST['laboratorio'], $_POST['tipoP'], $_POST['clase'], $_POST['composicionP'],$_POST['posologia'], $_POST['contrain']))  {

    

   	  $respuesta = $objModel->getRegistraProd($_POST['cod_producto'] , $_POST['tipoprod'], $_POST['presentacion'], $_POST['laboratorio'] , $_POST['tipoP'] , $_POST['clase'], $_POST['composicionP'] , $_POST['posologia'] , $_POST['contrain'] );
   	  die(json_encode($respuesta));
   }

   if(isset($_POST['select1'],$_POST['id'])){
    $respuesta = $objModel->mostrarImg($_POST['id']);
    die(json_encode($respuesta));
  }

  if(isset($_POST['editarImg'], $_POST['id'])){
    $respuesta = (isset($_POST['borrar']))
    ? $objModel->getEditarImg('', $_POST['id'], true)
    : $objModel->getEditarImg($_FILES['foto'], $_POST['id']);

    die(json_encode($respuesta));
  }

   if(isset($_POST['select'],$permiso['Consultar'])) {
     $respuesta = $objModel->MostrarEditProductos($_POST['id']);
     die(json_encode($respuesta));
   }


   if (isset(
    $_POST['cod_productoEd'] , $_POST['tipoprodEd'] , $_POST['presentaciónEd'] , 
    $_POST['laboratorioEd'] , $_POST['tipoEd'] , $_POST['claseEd'] , $_POST['composicionEd'] , $_POST['posologiaEd'] , $_POST['contraInEd'] , $_POST['id'], $permiso['Editar']
    )
  ) {

      $respuesta = $objModel->getEditarProd($_POST['cod_productoEd'] , $_POST['tipoprodEd'] , $_POST['presentaciónEd'] , $_POST['laboratorioEd'] , $_POST['tipoEd'] , $_POST['claseEd'] , $_POST['composicionEd'] , $_POST['posologiaEd']  , $_POST['contraInEd'] , $_POST['id'] );
      die(json_encode($respuesta));
      
   }

   
   if (isset($_POST['delete'], $permiso['Eliminar'])){
    $respuesta = $objModel->getEliminarProd($_POST['id']);
    die(json_encode($respuesta));
   }

   
   $VarComp = new initcomponents();
   $header = new header();
   $menu = new menuLateral($permisos);

  if(file_exists("vista/interno/productos/productoVista.php")){
    require_once("vista/interno/productos/productoVista.php");
  }

?>