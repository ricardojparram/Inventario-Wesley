<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class descargo extends DBConnect {
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
    private function verificarExistenciaDelLote() {
        try {
            $sql = "SELECT id_producto_sede, cantidad FROM producto_sede 
                    WHERE lote = (
                        SELECT lote FROM producto_sede WHERE id_producto_sede = :id_producto_sede
                    ) 
                    AND cod_producto = (
                        SELECT cod_producto FROM producto_sede WHERE id_producto_sede = :id_producto_sede
                    ) 
                    AND id_sede = 1;";
            $new = $this->con->prepare($sql);
            $new->bindValue(":id_producto_sede", $this->id_producto);
            $new->execute();
            return $new->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    public function getAgregarDescargo($num_descargo, $fecha, $productos): array {
        if (!$this->validarString('entero', $num_descargo))
            return $this->http_error(400, 'Transferencia inválida.');

        $fecha =  date('Y-m-d', strtotime($fecha));
        if (!$this->validarFecha($fecha, 'Y-m-d'))
            return $this->http_error(400, 'Fecha inválida.');

        $estructura_productos = [
            'id_producto' => 'string',
            'cantidad' => 'string'
        ];
        if (!$this->validarEstructuraArray($productos, $estructura_productos, true))
            return $this->http_error(400, 'Productos inválidos.');

        $this->num_descargo = $num_descargo;
        $this->fecha = $fecha;
        $this->productos = $productos;

        return $this->agregarDescargo();
    }

    private function agregarDescargo(): array {
        try {
            $this->conectarDB();
            $sql = "INSERT INTO descargo(fecha, num_descargo, status) VALUES (?,?,1)";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->fecha);
            $new->bindValue(2, $this->num_descargo);
            $new->execute();
            $this->id_descargo = $this->con->lastInsertId();

            foreach ($this->productos as $producto) {
                [
                    'id_producto' => $cod_producto,
                    'cantidad' => $cantidad
                ] = $producto;
                $this->id_producto = $cod_producto;
                $producto_sede = $this->verificarExistenciaDelLote();

                $inventario = intval($producto_sede->cantidad) - intval($cantidad);
                $sql = "UPDATE producto_sede SET cantidad = :inventario 
                        WHERE id_producto_sede = :id_producto_sede";
                $new = $this->con->prepare($sql);
                $new->bindValue(":inventario", $inventario);
                $new->bindValue(":id_producto_sede", $producto_sede->id_producto_sede);
                $new->execute();
                $this->id_producto = $producto_sede->id_producto_sede;


                $sql = "INSERT INTO detalle_descargo(id_descargo, id_producto_sede, cantidad) VALUES (?,?,?)";
                $new = $this->con->prepare($sql);
                $new->bindValue(1, $this->id_descargo);
                $new->bindValue(2, $this->id_producto);
                $new->bindValue(3, $cantidad);
                $new->execute();
            }

            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => 'Se ha registrado el descargo correctamente.'];
        } catch (\PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getEliminarDescargo($id_descargo): array {
        if (!$this->validarString('entero', $id_descargo))
            return $this->http_error(400, 'descargo inválido.');

        $this->id_descargo = $id_descargo;

        return $this->eliminarDescargo();
    }
    private function eliminarDescargo(): array {
        try {
            $this->conectarDB();

            $sql = "UPDATE descargo SET status = 0 WHERE id_descargo = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_descargo);
            $new->execute();

            $sql = "SELECT ps.id_producto_sede, dc.cantidad, ps.cantidad as inventario FROM detalle_descargo dc
                    INNER JOIN producto_sede ps ON ps.id_producto_sede = dc.id_producto_sede
                    WHERE id_descargo = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_descargo);
            $new->execute();
            $detalle_descargo = $new->fetchAll(\PDO::FETCH_OBJ);
            foreach ($detalle_descargo as $producto) {
                $inventario = intval($producto->cantidad) + intval($producto->inventario);
                $new = $this->con->prepare("UPDATE producto_sede SET cantidad = ? WHERE id_producto_sede = ?");
                $new->bindValue(1, $inventario);
                $new->bindValue(2, $producto->id_producto_sede);
                $new->execute();
            }

            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => 'Se ha anulado el descargo correctamente.'];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
}
