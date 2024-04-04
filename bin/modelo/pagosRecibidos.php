<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class pagosRecibidos extends DBConnect {
    use validar;
    private $id_cargo;
    private $num_cargo;
    private $fecha;
    private $productos;
    private $id_producto;

    public function mostrarPagosRecibidos($bitacora) {
        try {
            $this->conectarDB();
            $sql = "SELECT id_pago, num_fact, monto_fact, cedula, nombre, fecha, total_divisa FROM vw_venta_detallada";
            $new = $this->con->prepare($sql);
            $new->execute();
            // if ($bitacora == "true") $this->binnacle("Laboratorio", $_SESSION['cedula'], "Consultó listado.");
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getMostrarDetalle($id_cargo) {
        if (!$this->validarString('entero', $id_cargo))
            return $this->http_error(400, 'Cargo inválido.');

        $this->id_cargo = $id_cargo;

        // return $this->mostrarDetalle();
    }
}
