<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;

class inventario extends DBConnect
{
    public function __construct()
    {
        parent::__construct();
    }

    public function mostrarInventarioAjax($bitacora)
    {

        try {

            parent::conectarDB();
            // $query = "SELECT h.fecha, h.tipo_movimiento, h.entrada, h.salida, s.nombre, h.id_lote, h.id_producto_sede, h.cantidad FROM historial as h, sede as s WHERE h.status = 1";
            $query = "SELECT h.id_historial, h.fecha, h.tipo_movimiento, h.entrada, h.salida, s.nombre as sede, ps.lote as id_lote, ps.presentacion_producto as producto, h.cantidad FROM historial as h
                      INNER JOIN vw_producto_sede_detallado ps ON ps.id_producto_sede = h.id_producto_sede
                      INNER JOIN sede s ON s.id_sede = ps.id_sede
                      WHERE h.status = 1;";
            $new = $this->con->prepare($query);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            echo json_encode($data);
            parent::desconectarDB();
            die();
        } catch (\PDOException $error) {
            return $error;

        }
    }
}

