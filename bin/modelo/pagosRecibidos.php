<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class pagosRecibidos extends DBConnect {
    use validar;
    private $id_pago;
    private $status;

    public function mostrarPagosRecibidos($bitacora) {
        try {
            $this->conectarDB();
            $sql = "SELECT id_pago, status_pago, num_fact, monto_fact, cedula, nombre, fecha, total_divisa FROM vw_venta_detallada";
            $new = $this->con->prepare($sql);
            $new->execute();
            // if ($bitacora == "true") $this->binnacle("Laboratorio", $_SESSION['cedula'], "Consultó listado.");
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getMostrarDetalle($id_pago) {
        if (!$this->validarString('entero', $id_pago))
            return $this->http_error(400, 'Pago inválido.');

        $this->id_pago = $id_pago;

        return $this->mostrarDetalle();
    }
    private function mostrarDetalle() {
        try {
            $this->conectarDB();
            $sql = "SELECT
                        fp.tipo_pago,
                        dp.referencia,
                        dp.monto_pago,
                        v.monto_fact,
                        v.total_divisa,
                        v.num_fact,
                        v.status_pago
                    FROM
                        vw_venta_detallada v
                        INNER JOIN detalle_pago dp ON v.id_pago = dp.id_pago
                        INNER JOIN forma_pago fp ON fp.id_forma_pago = dp.id_forma_pago
                    WHERE v.id_pago = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_pago);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getConfirmarPago($status, $id_pago) {
        if (!$this->validarString('entero', $id_pago))
            return $this->http_error(400, 'Pago inválido.');
        if (!$this->validarString('entero', $status))
            return $this->http_error(400, 'Status inválido.');

        $this->id_pago = $id_pago;
        $this->status = $status;

        return $this->confirmarPago();
    }

    private function confirmarPago() {
        try {
            $this->conectarDB();
            $sql = "UPDATE pagos_recibidos SET status = ? WHERE id_pago = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->status);
            $new->bindValue(2, $this->id_pago);
            $new->execute();
            return ['resultado' => 'ok', 'msg' => 'Se ha actualizado el estado del pago.'];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
}
