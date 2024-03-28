<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class recepcion extends DBConnect {
    use validar;
    private $id_recepcion;
    private $id_transferencia;
    private $id_sede;
    private $id_lote;
    private $cantidad;
    private $fecha;
    private $productos;
    private $id_producto;

    public function mostrarSedes() {
        try {
            $this->conectarDB();
            $sql = "SELECT id_sede, nombre FROM sede WHERE status = 1";
            $new = $this->con->prepare($sql);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    public function mostrarTransferencias($bitacora): array {
        try {
            $this->conectarDB();
            $sql = "SELECT t.id_transferencia, s.nombre as nombre_sede, t.fecha FROM transferencia t 
                  INNER JOIN sede s ON t.id_sede = s.id_sede
                  WHERE t.status = 1;";
            $new = $this->con->prepare($sql);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            // if($bitacora == "true") $this->binnacle("Transferencia",$_SESSION['cedula'],"Consultó listado.");
            $this->desconectarDB();
            return $data;
        } catch (\PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getDatosTransferencia($id_transferencia): array {
        if (preg_match_all("/^[0-9]{1,10}$/", $id_transferencia) != 1) {
            http_response_code(400);
            return ['resultado' => 'error', 'msg' => 'Producto invalido.'];
        }

        $this->id_transferencia = $id_transferencia;
        return $this->datosTransferencia();
    }

    private function datosTransferencia(): array {
        try {
            $this->conectarDB();
            $sql = "SELECT id_sede, fecha FROM transferencia WHERE id_transferencia = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_transferencia);
            $new->execute();
            [$transferencia] = $new->fetchAll(\PDO::FETCH_OBJ);

            $sql = "SELECT ps.lote, dt.cantidad, ps.id_producto_sede FROM detalle_transferencia dt
                    INNER JOIN producto_sede ps ON ps.id_producto_sede = dt.id_producto_sede
                    INNER JOIN producto p ON p.cod_producto = ps.cod_producto 
                    WHERE dt.id_transferencia = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_transferencia);
            $new->execute();
            $this->desconectarDB();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            return ["transferencia" => $transferencia, "productos" => $data];
        } catch (\PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    private function mostrarProductoInventario(): array {
        try {
            $this->conectarDB();
            $sql = "SELECT cantidad FROM producto_sede WHERE id_producto_sede = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_producto);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getAgregarRecepcion($id_transferencia, $fecha, $productos): array {
        if (preg_match_all("/^[0-9]{1,10}$/", $id_transferencia) != 1) {
            http_response_code(400);
            return ['resultado' => 'error', 'msg' => 'Id invalida.'];
        }

        if ($this->validarFecha($fecha) !== true) {
            http_response_code(400);
            return ['resultado' => 'error', 'msg' => 'Fecha inválida'];
        }

        if (!is_array($productos)) {
            http_response_code(400);
            return ['resultado' => 'error', 'error' => 'Productos inválidos'];
        }


        $this->fecha = $fecha;
        $this->productos = $productos;
        $this->id_transferencia = $id_transferencia;

        return $this->agregarRecepcion();
    }

    private function agregarRecepcion(): array {
        try {
            $this->conectarDB();
            $sql = "INSERT INTO recepcion_sede(id_transferencia, fecha, status) VALUES (?,?,1)";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_transferencia);
            $new->bindValue(2, $this->fecha);
            $new->execute();
            $this->id_recepcion = $this->con->lastInsertId();

            $sql = "INSERT INTO detalle_transferencia(id_transferencia, id_producto_sede, cantidad) VALUES (?,?,?)";
            foreach ($this->productos as $producto) {
                $this->id_producto = $producto['id_producto'];
                [$data] = $this->mostrarProductoInventario();
                $this->conectarDB();
                $new = $this->con->prepare($sql);
                $new->bindValue(1, $this->id_recepcion);
                $new->bindValue(2, $this->id_producto);
                $new->bindValue(3, $producto['cantidad']);
                $new->execute();

                $inventario = intval($data->cantidad) - intval($producto['cantidad']);

                $new = $this->con->prepare("UPDATE producto_sede SET cantidad = ? WHERE id_producto_sede = ?");
                $new->bindValue(1, $inventario);
                $new->bindValue(2, $this->id_producto);
                $new->execute();
            }

            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => 'Se ha registrado la transferencia correctamente.'];
        } catch (\PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
