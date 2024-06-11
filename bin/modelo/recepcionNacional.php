<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;
use utils\fechas;

class recepcionNacional extends DBConnect
{
    use validar;
    use fechas;
    private $id_rep_nacional;
    private $id_proveedor;
    private $fecha;
    private $productos;
    private $id_producto;

    public function mostrarRecepciones($bitacora)
    {
        try {
            $this->conectarDB();
            $sql = "SELECT rn.id_rep_nacional, p.razon_social, rn.fecha FROM recepcion_nacional rn
              INNER JOIN proveedor p ON p.rif_proveedor = rn.id_proveedor
              WHERE rn.status = 1;";
            $new = $this->con->prepare($sql);
            $new->execute();
            // if ($bitacora == "true") $this->binnacle("Laboratorio", $_SESSION['cedula'], "Consultó listado.");
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getMostrarDetalle($id_rep_nacional)
    {
        if (!$this->validarString('entero', $id_rep_nacional)) {
            return $this->http_error(400, 'Producto invalido.');
        }

        $this->id_rep_nacional = $id_rep_nacional;

        return $this->mostrarDetalle();
    }
    private function mostrarDetalle()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT
                        drn.cantidad,
                        p.razon_social,
                        ps.presentacion_producto,
                        ps.lote,
                        ps.fecha_vencimiento
                    FROM
                        recepcion_nacional rn
                        INNER JOIN detalle_recepcion_nacional drn ON drn.id_rep_nacional = rn.id_rep_nacional
                        INNER JOIN proveedor p ON p.rif_proveedor = rn.id_proveedor
                        INNER JOIN vw_producto_sede_detallado ps ON ps.id_producto_sede = drn.id_producto_sede
                    WHERE
                        rn.id_rep_nacional = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_rep_nacional);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function mostrarProveedores()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT rif_proveedor, razon_social FROM proveedor WHERE status = 1;";
            $new = $this->con->prepare($sql);
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
            $sql = "SELECT
                        p.cod_producto,
                        concat(tp.nombrepro, ' ', pres.peso, ' ', med.nombre, ' ') AS presentacion_producto,
                        tp.nombrepro as nombre_producto,
                        pres.peso as presentacion_peso,
                        med.nombre as medida,
                        tipo.nombre_t as tipo,
                        clase.nombre_c as clase
                    FROM
                        producto p
                        INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod
                        INNER JOIN presentacion pres ON pres.cod_pres = p.cod_pres
                        INNER JOIN medida med ON med.id_medida = pres.id_medida
                        INNER JOIN tipo ON tipo.id_tipo = p.id_tipo
                        INNER JOIN clase ON clase.id_clase = p.id_clase;";
            $new = $this->con->prepare($sql);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    private function verificarExistenciaDelLote($cod_producto, $lote, $fecha_vencimiento)
    {
        try {
            $sql = "SELECT
                        id_producto_sede,
                        cantidad,
                        version
                    FROM
                        producto_sede
                    WHERE
                        lote = :lote
                        AND fecha_vencimiento = :fecha_vencimiento
                        AND cod_producto = :cod_producto
                        AND id_sede = 1;";
            $new = $this->con->prepare($sql);
            $new->bindValue(":lote", $lote);
            $new->bindValue(":fecha_vencimiento", $fecha_vencimiento);
            $new->bindValue(":cod_producto", $cod_producto);
            $new->execute();
            return $new->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    public function getAgregarRecepcionNacional($id_proveedor, $fecha, $productos): array
    {
        if (!$this->validarString('rif', $id_proveedor)) {
            return $this->http_error(400, 'Proveedor inválido.');
        }

        if (!$this->validarFecha($fecha, 'Y-m-d')) {
            return $this->http_error(400, 'Fecha inválida.');
        }

        $estructura_productos = [
            'id_producto' => 'string',
            'fecha_vencimiento' => 'string',
            'lote' => 'string',
            'cantidad' => 'string'
        ];
        if (!$this->validarEstructuraArray($productos, $estructura_productos, true)) {
            return $this->http_error(400, 'Productos inválidos.');
        }

        $this->id_proveedor = $id_proveedor;
        $this->fecha = $fecha;
        $this->productos = $productos;

        return $this->agregarRecepcionNacional();
    }

    private function agregarRecepcionNacional(): array
    {
        try {
            $this->conectarDB();
            $this->con->beginTransaction();
            $sql = "INSERT INTO recepcion_nacional(id_proveedor, fecha, status) VALUES  (?,?,1)";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_proveedor);
            $new->bindValue(2, $this->fecha);
            $new->execute();
            $this->id_rep_nacional = $this->con->lastInsertId();

            foreach ($this->productos as $producto) {
                [
                    'id_producto' => $cod_producto,
                    'fecha_vencimiento' => $fecha,
                    'lote' => $lote,
                    'cantidad' => $cantidad
                ] = $producto;

                $fecha_vencimiento = $this->convertirFecha($fecha, 'd/m/Y');
                if ($fecha_vencimiento === false) {
                    $this->con->rollBack();
                    return $this->http_error(400, 'Fecha de vencimiento inválida.');
                }

                $producto_sede = $this->verificarExistenciaDelLote($cod_producto, $lote, $fecha_vencimiento);
                if (is_array($producto_sede)) {
                    $this->con->rollBack();
                    return $producto_sede;
                }

                if (isset($producto_sede->id_producto_sede)) {
                    $inventario = intval($producto_sede->cantidad) + intval($cantidad);
                    $version = intval($producto_sede->version) + 1;
                    $sql = "UPDATE producto_sede SET cantidad = :inventario, version = :version_nueva
                            WHERE id_producto_sede = :id_producto_sede AND version = :version_leida";
                    $new = $this->con->prepare($sql);
                    $new->bindValue(":inventario", $inventario);
                    $new->bindValue(":version_nueva", $version);
                    $new->bindValue(":id_producto_sede", $producto_sede->id_producto_sede);
                    $new->bindValue(":version_leida", $producto_sede->version);
                    $new->execute();
                    $this->id_producto = $producto_sede->id_producto_sede;
                    if ($new->rowCount() == 0) {
                        $this->con->rollBack();
                        return $this->http_error(409, 'La recepción nacional falló debido al exceso de concurrencia.');
                    }
                } else {
                    $sql = "INSERT INTO producto_sede(cod_producto, lote, fecha_vencimiento, id_sede, cantidad)
                            VALUES (:cod_producto, :lote, :fecha_vencimiento, 1, :cantidad)";
                    $new = $this->con->prepare($sql);
                    $new->bindValue(':cod_producto', $cod_producto);
                    $new->bindValue(':lote', $lote);
                    $new->bindValue(':cantidad', $cantidad);
                    $new->bindValue(':fecha_vencimiento', $fecha_vencimiento);
                    $new->execute();
                    $this->id_producto = $this->con->lastInsertId();
                }

                $sql = "INSERT INTO detalle_recepcion_nacional(id_rep_nacional, id_producto_sede, cantidad) VALUES (?,?,?)";
                $new = $this->con->prepare($sql);
                $new->bindValue(1, $this->id_rep_nacional);
                $new->bindValue(2, $this->id_producto);
                $new->bindValue(3, $cantidad);
                $new->execute();

                $this->inventario_historial("Recepcion nacional", "x", "", "", $this->id_producto, $cantidad);
            }
            $this->con->commit();
            return ['resultado' => 'ok', 'msg' => 'Se ha registrado la recepcion correctamente.'];
        } catch (\PDOException $e) {
            $this->con->rollback();
            return $this->http_error(500, $e->getMessage());
        } finally {
            $this->desconectarDB();
        }
    }

    public function getEliminarRecepcionNacional($id_rep_nacional): array
    {
        if (!$this->validarString('entero', $id_rep_nacional)) {
            return $this->http_error(400, 'Recepción nacional inválida.');
        }

        $this->id_rep_nacional = $id_rep_nacional;

        return $this->eliminarRecepcionNacional();
    }
    private function eliminarRecepcionNacional(): array
    {
        try {
            $this->conectarDB();
            $this->con->beginTransaction();

            $sql = "UPDATE recepcion_nacional SET status = 0 WHERE id_rep_nacional = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_rep_nacional);
            $new->execute();

            $sql = "SELECT
                        ps.id_producto_sede,
                        dn.cantidad,
                        ps.cantidad as inventario,
                        ps.version
                    FROM
                        detalle_recepcion_nacional dn
                        INNER JOIN producto_sede ps ON ps.id_producto_sede = dn.id_producto_sede
                    WHERE
                        id_rep_nacional = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_rep_nacional);
            $new->execute();
            $detalle_recepcion = $new->fetchAll(\PDO::FETCH_OBJ);
            foreach ($detalle_recepcion as $producto) {
                if (intval($producto->inventario) < intval($producto->cantidad)) {
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
                            AND version = :version_leida;";
                $new = $this->con->prepare($sql);
                $new->bindValue(':cantidad', $inventario);
                $new->bindValue(':version_nueva', $version);
                $new->bindValue(':id', $producto->id_producto_sede);
                $new->bindValue(':version_leida', $producto->version);
                $new->execute();
                if ($new->rowCount() == 0) {
                    $this->con->rollBack();
                    return $this->http_error(409, 'Eliminar la recepción nacional falló debido al exceso de concurrencia.');
                }
            }
            $this->con->commit();
            return ['resultado' => 'ok', 'msg' => 'Se ha anulado la recepcion nacional correctamente.'];
        } catch (\PDOException $e) {
            $this->con->rollback();
            return $this->http_error(500, $e->getMessage());
        } finally {
            $this->desconectarDB();
        }
    }
}
