<?php

namespace modelo;

Use config\connect\DBConnect as DBConnect;

class bitacora extends DBConnect{

    public function __construct(){
        parent::__construct();
    }

    public function mostrarBitacora(){
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT concat(u.nombre,' ',u.apellido) as nombre, b.descripcion, b.fecha FROM bitacora b INNER JOIN usuario u ON b.cedula = u.cedula WHERE b.status = 1");
            $new->execute();
            $data = $new->fetchAll();
            echo json_encode($data);
            parent::desconectarDB();
            die();
        } catch (\PDOexection $error) {
            return $error;
        }
    }

}

?>