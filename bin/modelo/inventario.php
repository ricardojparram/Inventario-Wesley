<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class inventario extends DBConnect
{
    use validar;
    private $sede;
    private $cedula;

    public function __construct($session)
    {
        parent::__construct();
        $this->sede = $session['sede'];
        $this->cedula = $session['cedula'];
    }

    public function mostrarInventario($bitacora)
    {
        try {
            parent::conectarDB();
            $query = "SELECT
                        ps.id_producto_sede as id,
                        ps.presentacion_producto,
                        ps.presentacion_peso,
                        ps.medida,
                        ps.lote,
                        ps.fecha_vencimiento,
                        ps.cantidad as inventario,
                        ps.tipo,
                        ps.clase
                    FROM
                        vw_producto_sede_detallado ps
                    WHERE
                        id_sede = :id_sede
                        AND ps.cantidad > 0;";
            $new = $this->con->prepare($query);
            $new->bindValue(":id_sede", $this->sede);
            $new->execute();
            if ($bitacora === "true") {
                $this->binnacle("", $this->cedula, "Consulto el listado de inventario.");
            }
            parent::desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $error) {
            return $this->http_error(500, $error->getMessage());
        }
    }
}
