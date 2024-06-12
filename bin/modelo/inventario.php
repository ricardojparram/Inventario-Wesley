<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class inventario extends DBConnect
{
    use validar;
    public function mostrarInventario($bitacora)
    {
        try {
            parent::conectarDB();
            $query = "
              SELECT 
                  u.cedula as usuario,
                  s.nombre as nombre_sede,
                  h.fecha as fecha,
                  vpsd.presentacion_producto as presentacion_producto,
                  h.entrada as entrada,
                  h.salida as salida,
                  h.tipo_movimiento as tipo_movimiento,
                  vpsd.lote as producto_lote,
                  h.cantidad as cantidad
              FROM
                  historial h
                  INNER JOIN usuario u ON h.id_usuario = u.cedula
                  INNER JOIN sede s ON s.id_sede = h.id_sede
                  INNER JOIN vw_producto_sede_detallado vpsd ON vpsd.id_producto_sede = h.id_producto_sede
              WHERE h.status = 1;
            ";
            $new = $this->con->prepare($query);
            $new->execute();
            if($bitacora === "true") {
                $this->binnacle("", $_SESSION['cedula'], "Consulto el listado de inventario.");
            }
            parent::desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $error) {
            return $this->http_error(500, $error);
        }
    }
}
