<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class productoDanado extends DBConnect {
    use validar;
    private $id_descargo;
    private $num_descargo;
    private $fecha;
    private $productos;
    private $id_producto;

    public function mostrarDescargos($bitacora) {
        try {
            $this->conectarDB();
            $sql = "SELECT id_descargo, fecha, num_descargo FROM descargo 
                    WHERE status = 1;";
            $new = $this->con->prepare($sql);
            $new->execute();
            // if ($bitacora == "true") $this->binnacle("Laboratorio", $_SESSION['cedula'], "Consultó listado.");
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getMostrarDetalle($id_descargo) {
        if (!$this->validarString('entero', $id_descargo))
            return $this->http_error(400, 'descargo inválido.');

        $this->id_descargo = $id_descargo;

        return $this->mostrarDetalle();
    }
    private function mostrarDetalle() {
        try {
            $this->conectarDB();
            $sql = "SELECT ps.lote, ps.presentacion_producto, ps.fecha_vencimiento, dc.cantidad, c.num_descargo FROM descargo c 
                    INNER JOIN detalle_descargo dc ON dc.id_descargo = c.id_descargo
                    INNER JOIN vw_producto_sede_detallado ps ON ps.id_producto_sede = dc.id_producto_sede
                    WHERE c.id_descargo = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_descargo);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function mostrarProductos() {
        try {
            $this->conectarDB();
            $sql = "SELECT id_producto_sede, presentacion_producto, fecha_vencimiento, cantidad FROM vw_producto_sede_detallado
                    WHERE id_sede = 1";
            $new = $this->con->prepare($sql);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
   
public function exportar(){
    try{
        $this->conectarDB();
        $sql = "SELECT d.num_descargo, d.fecha, CONCAT(tp.nombrepro,' ',pr.peso,' ',m.nombre) AS producto, dd.cantidad FROM descargo d INNER JOIN detalle_descargo dd ON dd.id_descargo = d.id_descargo INNER JOIN producto_sede ps ON ps.id_producto_sede = dd.id_producto_sede INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida WHERE d.status = 1 ORDER BY d.fecha ASC;
        ";
        $new = $this->con->prepare($sql);
        $new->execute();
        $this->desconectarDB();
        return $new->fetchAll(\PDO::FETCH_OBJ);
    }catch(\PDOException $e){
        return $this->http_error(500, $e->getMessag());
    }
}
  
}
