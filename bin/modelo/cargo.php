<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class cargo extends DBConnect
{
    use validar;
    private $id_cargo;
    private $num_cargo;
    private $fecha;
    private $productos;
    private $id_producto;

    public function mostrarCargos($bitacora)
    {
        try {
            $this->conectarDB();
            $sql = "SELECT id_cargo, fecha, num_cargo FROM cargo 
                    WHERE status = 1;";
            $new = $this->con->prepare($sql);
            $new->execute();
            if ($bitacora == "true") $this->binnacle("Laboratorio", $_SESSION['cedula'], "Consultó listado.");
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getMostrarDetalle($id_cargo)
    {
        if (!$this->validarString('entero', $id_cargo)) {
            return $this->http_error(400, 'Cargo inválido.');
        }

        $this->id_cargo = $id_cargo;

        return $this->mostrarDetalle();
    }
    private function mostrarDetalle()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT ps.lote, ps.presentacion_producto, ps.fecha_vencimiento, dc.cantidad, c.num_cargo FROM cargo c 
                    INNER JOIN detalle_cargo dc ON dc.id_cargo = c.id_cargo
                    INNER JOIN vw_producto_sede_detallado ps ON ps.id_producto_sede = dc.id_producto_sede
                    WHERE c.id_cargo = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_cargo);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function mostrarProductos()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT id_producto_sede, presentacion_producto, fecha_vencimiento, cantidad FROM vw_producto_sede_detallado
                    WHERE id_sede = :id_sede";
            $new = $this->con->prepare($sql);
            $new->execute([':id_sede' => $_SESSION['id_sede']]);
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    private function verificarExistenciaDelLote()
    {
        try {
            $sql = "SELECT id_producto_sede, cantidad, version FROM producto_sede 
                    WHERE lote = (
                        SELECT lote FROM producto_sede WHERE id_producto_sede = :id_producto_sede
                    ) 
                    AND cod_producto = (
                        SELECT cod_producto FROM producto_sede WHERE id_producto_sede = :id_producto_sede
                    ) 
                    AND id_sede = :id_sede;";
            $new = $this->con->prepare($sql);
            $new->execute([':id_producto_sede' => $this->id_producto, ':id_sede' => $_SESSION['id_sede']]);
            return $new->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    public function getAgregarCargo($num_cargo, $fecha, $productos): array
    {
        if (!$this->validarString('numero', $num_cargo)) {
            return $this->http_error(400, 'Numero de cargo inválido.');
        }

        $fecha =  date('Y-m-d', strtotime($fecha));
        if (!$this->validarFecha($fecha, 'Y-m-d')) {
            return $this->http_error(400, 'Fecha inválida.');
        }

        $estructura_productos = [
            'id_producto' => 'string',
            'cantidad' => 'string'
        ];
        if (!$this->validarEstructuraArray($productos, $estructura_productos, true)) {
            return $this->http_error(400, 'Productos inválidos.');
        }

        $this->num_cargo = $num_cargo;
        $this->fecha = $fecha;
        $this->productos = $productos;

        return $this->agregarCargo();
    }

    private function agregarCargo(): array
    {
        try {
            $this->conectarDB();
            $this->con->beginTransaction();
            $sql = "INSERT INTO cargo(fecha, id_sede, num_cargo, status) VALUES (:fecha,:id_sede,:cargo,1)";
            $new = $this->con->prepare($sql);
            $new->execute([
                ':fecha' => $this->fecha,
                ':id_sede' => $_SESSION['id_sede'],
                ':cargo' => $this->num_cargo
            ]);
            $this->id_cargo = $this->con->lastInsertId();

            foreach ($this->productos as $producto) {
                [
                    'id_producto' => $cod_producto,
                    'cantidad' => $cantidad,
                ] = $producto;
                $this->id_producto = $cod_producto;
                $producto_sede = $this->verificarExistenciaDelLote();
                if (is_array($producto_sede)) {
                    $this->con->rollBack();
                    return $producto_sede;
                }

                $inventario = intval($producto_sede->cantidad) + intval($cantidad);
                $version = intval($producto_sede->version) + 1;
                $sql = "UPDATE producto_sede SET cantidad = :inventario, version = :version
                        WHERE id_producto_sede = :id_producto_sede";
                $new = $this->con->prepare($sql);
                $new->bindValue(":inventario", $inventario);
                $new->bindValue(":version", $version);
                $new->bindValue(":id_producto_sede", $producto_sede->id_producto_sede);
                $new->execute();
                $this->id_producto = $producto_sede->id_producto_sede;
                if ($new->rowCount() == 0) {
                    $this->con->rollBack();
                    return $this->http_error(409, 'El cargo falló debido al exceso de concurrencia.');
                }


                $sql = "INSERT INTO detalle_cargo(id_cargo, id_producto_sede, cantidad) VALUES (?,?,?)";
                $new = $this->con->prepare($sql);
                $new->bindValue(1, $this->id_cargo);
                $new->bindValue(2, $this->id_producto);
                $new->bindValue(3, $cantidad);
                $new->execute();
                $this->inventario_historial("Cargo", "x", "", "", $this->id_producto, $producto["cantidad"]);
            }

            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => 'Se ha registrado el cargo correctamente.'];
        } catch (\PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getEliminarCargo($id_cargo): array
    {
        if (!$this->validarString('entero', $id_cargo)) {
            return $this->http_error(400, 'Id cargo inválida.');
        }

        $this->id_cargo = $id_cargo;

        return $this->eliminarCargo();
    }
    private function eliminarCargo(): array
    {
        try {
            $this->conectarDB();
            $this->con->beginTransaction();
            $sql = "UPDATE cargo SET status = 0 WHERE id_cargo = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_cargo);
            $new->execute();

            $sql = "SELECT
                        ps.id_producto_sede,
                        dc.cantidad,
                        ps.cantidad as inventario,
                        ps.version
                    FROM
                        detalle_cargo dc
                        INNER JOIN producto_sede ps ON ps.id_producto_sede = dc.id_producto_sede
                    WHERE
                        id_cargo = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_cargo);
            $new->execute();
            $detalle_cargo = $new->fetchAll(\PDO::FETCH_OBJ);
            foreach ($detalle_cargo as $producto) {
                $producto_sede = $this->verificarExistenciaDelLote();
                if (is_array($producto_sede)) {
                    $this->con->rollBack();
                    return $producto_sede;
                }

                if (intval($producto_sede->cantidad) < intval($producto->cantidad)) {
                    $this->con->rollBack();
                    return $this->http_error(400, 'Cantidad insuficiente en el inventario.');
                }

                $inventario = intval($producto->cantidad) - intval($producto->inventario);
                $version = intval($producto->version) + 1;
                $sql = "UPDATE
                            producto_sede
                        SET
                            cantidad = :cantidad,
                            version = :version_nueva
                        WHERE
                            id_producto_sede = :id
                            AND version = :version_leida";
                $new = $this->con->prepare($sql);
                $new->bindValue(':cantidad', $inventario);
                $new->bindValue(':version', $version);
                $new->bindValue(':id', $producto->id_producto_sede);
                $new->execute();
                if ($new->rowCount() == 0) {
                    $this->con->rollBack();
                    return $this->http_error(409, 'Eliminar el cargo falló debido al exceso de concurrencia.');
                }
            }

            $this->con->commit();
            return ['resultado' => 'ok', 'msg' => 'Se ha anulado el cargo correctamente.'];
        } catch (\PDOException $e) {
            $this->con->rollBack();
            return $this->http_error(500, $e->getMessage());
        } finally {
            $this->desconectarDB();
        }
    }
}
