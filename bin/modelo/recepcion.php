<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class recepcion extends DBConnect
{
    use validar;
    private $id_recepcion;
    private $id_transferencia;
    private $id_sede;
    private $id_lote;
    private $cantidad;
    private $fecha;
    private $productos;
    private $id_producto;

    public function mostrarSedes()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT id_sede, nombre FROM sede WHERE status = 1";
            $new = $this->con->prepare($sql);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    public function mostrarRecepciones($bitacora): array
    {
        try {
            $this->conectarDB();
            $sql = "SELECT r.id_recepcion, s.nombre as nombre_sede, r.fecha FROM recepcion_sede r
                    INNER JOIN transferencia t ON t.id_transferencia = r.id_transferencia
                    INNER JOIN sede s ON t.id_sede = s.id_sede
                    WHERE r.status = 1;";
            $new = $this->con->prepare($sql);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            if($bitacora == "true") {
                $this->binnacle("Recepcion", $_SESSION['cedula'], "Consultó listado de recepcion.");
            }
            $this->desconectarDB();
            return $data;
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function mostrarTransferencias($bitacora): array
    {
        try {
            $this->conectarDB();
            $sql = "SELECT t.id_transferencia, s.nombre as nombre_sede, t.fecha FROM transferencia t 
                  INNER JOIN sede s ON t.id_sede = s.id_sede
                  WHERE t.status = 1 AND s.id_sede = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $_SESSION['id_sede']);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            if($bitacora == "true") {
                $this->binnacle("Transferencia", $_SESSION['cedula'], "Consultó listado de transferencias.");
            }
            $this->desconectarDB();
            return $data;
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    public function getMostrarDetalle($id_recepcion)
    {
        if (preg_match_all("/^[0-9]{1,10}$/", $id_recepcion) != 1) {
            return $this->http_error(400, 'Producto invalido.');
        }

        $this->id_recepcion = $id_recepcion;
        return $this->mostrarDetalle();
    }
    private function mostrarDetalle()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT s.nombre as nombre_sede, ps.lote, ps.id_producto_sede, p.cod_producto, dr.cantidad, ps.fecha_vencimiento FROM detalle_recepcion dr
                    INNER JOIN recepcion_sede r ON r.id_recepcion = dr.id_recepcion
                    INNER JOIN transferencia t ON r.id_transferencia = t.id_transferencia
                    INNER JOIN producto_sede ps ON ps.id_producto_sede = dr.id_producto_sede
                    INNER JOIN producto p ON p.cod_producto = ps.cod_producto 
                    INNER JOIN sede s ON s.id_sede = t.id_sede
                    WHERE r.id_recepcion = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_recepcion);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getDatosTransferencia($id_transferencia): array
    {
        if (preg_match_all("/^[0-9]{1,10}$/", $id_transferencia) != 1) {
            return $this->http_error(400, 'Producto invalido.');
        }

        $this->id_transferencia = $id_transferencia;
        return $this->datosTransferencia();
    }

    private function datosTransferencia(): array
    {
        try {
            $this->conectarDB();
            $sql = "SELECT id_sede, fecha FROM transferencia WHERE id_transferencia = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_transferencia);
            $new->execute();
            $transferencia = $new->fetch(\PDO::FETCH_OBJ);

            $sql = "SELECT ps.lote, dt.cantidad, ps.id_producto_sede, dt.descripcion FROM detalle_transferencia dt
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
            return $this->http_error(500, $e->getMessage());
        }
    }
    private function mostrarProductoInventario(): array
    {
        try {
            $this->conectarDB();
            $sql = "SELECT cantidad FROM producto_sede WHERE id_producto_sede = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_producto);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    private function cambiarEstadoTransferencia($status)
    {
        try {
            $sql = "UPDATE transferencia SET status = :status WHERE id_transferencia = :id_transferencia";
            $new = $this->con->prepare($sql);
            $new->bindValue(':status', $status);
            $new->bindValue(':id_transferencia', $this->id_transferencia);
            $new->execute();
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getAgregarRecepcion($id_transferencia, $sede, $fecha, $productos): array
    {
        if (preg_match_all("/^[0-9]{1,10}$/", $id_transferencia) != 1) {
            return $this->http_error(400, 'Transferencia inválida.');
        }

        if (preg_match_all("/^[0-9]{1,10}$/", $sede) != 1) {
            return $this->http_error(400, 'Sede inválida.');
        }

        $fecha =  date('Y-m-d H:i:s', strtotime($fecha));
        if ($this->validarFecha($fecha, 'Y-m-d H:i:s') !== true) {
            return $this->http_error(400, 'Fecha inválida.');
        }
        $estructura_productos = [
          'id_producto' => 'string',
          'cantidad' => 'string',
          'descripcion' => 'string'
        ];
        if (!$this->validarEstructuraArray($productos, $estructura_productos, true)) {
            return $this->http_error(400, 'Productos inválidos.');
        }

        $this->id_sede = $sede;
        $this->fecha = $fecha;
        $this->productos = $productos;
        $this->id_transferencia = $id_transferencia;

        return $this->agregarRecepcion();
    }

    private function agregarRecepcion(): array
    {
        try {
            $this->conectarDB();
            $sql = "INSERT INTO recepcion_sede(id_transferencia, fecha, status) VALUES (?,?,1)";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_transferencia);
            $new->bindValue(2, $this->fecha);
            $new->execute();
            $this->id_recepcion = $this->con->lastInsertId();

            foreach ($this->productos as $producto) {
                $this->id_producto = $producto['id_producto'];
                $producto_sede = $this->verificarExistenciaDelLote();

                if (isset($producto_sede->id_producto_sede)) {
                    $inventario = intval($producto_sede->cantidad) + intval($producto['cantidad']);
                    $sql = "UPDATE producto_sede SET cantidad = :inventario 
                            WHERE id_producto_sede = :id_producto_sede";
                    $new = $this->con->prepare($sql);
                    $new->bindValue(":inventario", $inventario);
                    $new->bindValue(":id_producto_sede", $producto_sede->id_producto_sede);
                    $new->execute();
                    $this->id_producto = $producto_sede->id_producto_sede;
                } else {
                    $sql = "INSERT INTO producto_sede(cod_producto, lote, fecha_vencimiento, id_sede, cantidad)
                            SELECT cod_producto, lote, fecha_vencimiento, :sede, :cantidad FROM producto_sede
                            WHERE id_producto_sede = :id_producto_sede;";
                    $new = $this->con->prepare($sql);
                    $new->bindValue(':sede', $this->id_sede);
                    $new->bindValue(':cantidad', $producto['cantidad']);
                    $new->bindValue(':id_producto_sede', $this->id_producto);
                    $new->execute();
                    $this->id_producto = $this->con->lastInsertId();
                }


                $sql = "INSERT INTO detalle_recepcion(id_recepcion, id_producto_sede, cantidad, descripcion) VALUES (?,?,?,?)";
                $new = $this->con->prepare($sql);
                $new->bindValue(1, $this->id_recepcion);
                $new->bindValue(2, $this->id_producto);
                $new->bindValue(3, $producto['cantidad']);
                $new->bindValue(4, $producto['descripcion']);
                $new->execute();
                $this->inventario_historial("Recepcion", "x", "", "", $this->id_producto, $producto["cantidad"]);
            }

            $this->cambiarEstadoTransferencia(2);

            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => 'Se ha registrado la recepcion correctamente.'];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    private function verificarExistenciaDelLote()
    {
        try {
            $sql = "SELECT id_producto_sede, cantidad FROM producto_sede 
                    WHERE lote = (
                        SELECT lote FROM producto_sede WHERE id_producto_sede = :id_producto_sede
                    ) 
                    AND cod_producto = (
                        SELECT cod_producto FROM producto_sede WHERE id_producto_sede = :id_producto_sede
                    ) 
                    AND id_sede = :id_sede;";
            $new = $this->con->prepare($sql);
            $new->bindValue(":id_producto_sede", $this->id_producto);
            $new->bindValue(":id_sede", $this->id_sede);
            $new->execute();
            return $new->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getEliminarRecepcion($id_recepcion): array
    {
        if (preg_match_all("/^[0-9]{1,10}$/", $id_recepcion) != 1) {
            return $this->http_error(400, 'Transferencia inválida.');
        }

        $this->id_recepcion = $id_recepcion;

        return $this->eliminarRecepcion();
    }
    private function eliminarRecepcion(): array
    {
        try {
            $this->conectarDB();
            $sql = "SELECT id_transferencia FROM recepcion_sede
                    WHERE id_recepcion = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_recepcion);
            $new->execute();
            $transferencia = $new->fetch(\PDO::FETCH_OBJ);

            $this->id_transferencia = $transferencia->id_transferencia;
            $this->cambiarEstadoTransferencia(1);

            $sql = "UPDATE recepcion_sede SET status = 0 WHERE id_recepcion = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_recepcion);
            $new->execute();

            $sql = "SELECT ps.id_producto_sede, dr.cantidad, ps.cantidad as inventario FROM detalle_recepcion dr
                    INNER JOIN producto_sede ps ON ps.id_producto_sede = dr.id_producto_sede
                    WHERE id_recepcion = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_recepcion);
            $new->execute();
            $detalle_recepcion = $new->fetchAll(\PDO::FETCH_OBJ);
            foreach ($detalle_recepcion as $producto) {
                $inventario = intval($producto->cantidad) - intval($producto->inventario);
                $new = $this->con->prepare("UPDATE producto_sede SET cantidad = ? WHERE id_producto_sede = ?");
                $new->bindValue(1, $inventario);
                $new->bindValue(2, $producto->id_producto_sede);
                $new->execute();
            }

            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => 'Se ha anulado la recepcion correctamente.'];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
}
