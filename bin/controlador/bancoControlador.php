<?php 

	 use component\initcomponents as initcomponents;
	 use component\header as header;
	 use component\menuLateral as menuLateral;
	 use modelo\banco as banco;

	 if(isset($_SESSION['nivel'])) {
	 	if ($_SESSION['nivel'] != 1 && $_SESSION['nivel'] != 2) {
	 		die('<script> window.location = "?url=home" </script>');
	 	}
	 }else{
          die('<script> window.location = "?url=login" </script>');
	 }

    $objModel = new banco();
    $permisos = $objModel->getPermisosRol($_SESSION['nivel']);
    $permiso = $permisos['Bancos'];

    if(!isset($permiso['Consultar'])) die(`<script> window.location = "?url=home" </script>`);

    if(isset($_POST['notificacion'])) {
      $objModel->getNotificacion();
    }

    if(isset($_POST['getPermisos']) && $permiso['Consultar'] == 1){
      die(json_encode($permiso));
    }

     if (isset($_POST['mostrar']) && isset($_POST['bitacora'])) {
  
       $res = $objModel->mostrarBank($_POST['bitacora']);
        die(json_encode($res));
     }

      $datosBanco = $objModel->datosBanco();

     if (isset($_POST['selecTipoPago']) && $permiso['Consultar'] == 1) {
     	$res = $objModel->selecTipoPago();
      die(json_encode($res));
     }

     if(isset($_POST['id']) && isset($_POST['validar']) && $permiso['Consultar'] == 1){
      $res = $objModel->validarTipoP($_POST['id']);
      die(json_encode($res));
     }

     if(isset($_POST['tipoP']) && isset($_POST['tipo']) && isset($_POST['cedulaRif']) && isset($_POST['nombre']) && isset($_POST['id']) &&  isset($_POST['validarD']) && $permiso['Consultar'] == 1 ){
       $res = $objModel->ValidarDatos($_POST['tipoP'] , $_POST['tipo'] , $_POST['cedulaRif'], $_POST['nombre'] , $_POST['id']);
        die(json_encode($res));
     }

     if (isset($_POST['data']) && isset($_POST['registro']) && $permiso['Registrar'] == 1) {
      $res = $objModel->getRegistrarBanco($_POST['data']);
       die(json_encode($res));
     }

     if (isset($_POST['validarC']) && isset($_POST['id']) && $permiso['Consultar'] == 1) {
      $res = $objModel->validarSelect($_POST['id']);
       die(json_encode($res));
     }

     if (isset($_POST['clickEdit']) && isset($_POST['id']) && $permiso['Consultar'] == 1) {
      $res = $objModel->rellenarEdit($_POST['id']);
       die(json_encode($res));
     }

     if (isset($_POST['data']) && isset($_POST['id']) && isset($_POST['editar']) && $permiso['Editar'] == 1) {
      $res = $objModel->getEditarBanco($_POST['data'] , $_POST['id']);
       die(json_encode($res));
     }

     if (isset($_POST['eliminar']) && isset($_POST['id']) && $permiso['Eliminar'] == 1) {
      $res = $objModel->getEliminarBanco($_POST['id']);
       die(json_encode($res));
     }


     $VarComp = new initcomponents();
     $header = new header();
     $menu = new menuLateral($permisos);

     if (file_exists("vista/interno/configuraciones/bancoVista.php")) {
       require_once("vista/interno/configuraciones/bancoVista.php");
     }else{
      die('La vista no existe.');
     }

 ?>