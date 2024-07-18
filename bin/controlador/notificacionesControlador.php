<?php

    use component\initcomponents as initcomponents;
    use component\header as header;
    use component\menuLateral as menuLateral;
    use modelo\notificaciones as notificaciones;

    if(!isset($_SESSION['nivel'])){
        die(`<script> window.location = "?url=login" </script>`);
    }

    $objModel = new notificaciones();
	$permisos = $objModel->getPermisosRol($_SESSION['nivel']);

    if(isset($_GET['consultar'])){
        $res = $objModel->mostrarNotificaciones();
        die(json_encode($res));
    }

    if(isset($_POST['id'] , $_POST['status'])){
        $res = $objModel->getActualizarStatus($_POST['id'] , $_POST['status']);
        die(json_encode($res));
    }

    $VarComp = new initcomponents();
    $header = new header();
    $menu = new menuLateral($permisos);

    if(file_exists("vista/interno/notificacionesVista.php")){
        require_once('vista/interno/notificacionesVista.php');
    }else{
        echo 'Error no existe la vista';
    }
