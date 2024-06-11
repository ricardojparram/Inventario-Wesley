<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class descargo extends DBConnect
{
    use validar;
    private $id_descargo;
    private $num_descargo;
    private $fecha;
    private $productos;
    private $id_producto;
    private $img;

    public function mostrarDescargos($bitacora)
    {
        try {
            $this->conectarDB();
            $sql = "SELECT id_descargo, fecha, num_descargo FROM descargo 
                    WHERE status = 1 AND id_sede = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $_SESSION['id_sede']);
            $new->execute();
            if ($bitacora == "true") {
                $this->binnacle("Descargo", $_SESSION['cedula'], "Consultó listado en el módulo descargo.");
            }
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getMostrarDetalle($id_descargo)
    {
        if (!$this->validarString('entero', $id_descargo)) {
            return $this->http_error(400, 'Descargo inválido.');
        }

        $this->id_descargo = $id_descargo;

        return $this->mostrarDetalle();
    }
    private function mostrarDetalle()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT d.fecha, s.nombre as nombre_sede, d.num_descargo FROM descargo d
                    INNER JOIN sede s ON s.id_sede = d.id_sede
                    WHERE d.status = 1 AND d.id_descargo = :id";
            $new = $this->con->prepare($sql);
            $new->execute([':id' => $this->id_descargo]);
            $descargo = $new->fetch(\PDO::FETCH_ASSOC);

            $sql = "SELECT img as src FROM img_descargo WHERE status = 1 AND id_descargo = :id;";
            $new = $this->con->prepare($sql);
            $new->execute([':id' => $this->id_descargo]);
            $img = $new->fetchAll(\PDO::FETCH_ASSOC);

            $sql = "SELECT ps.lote, ps.presentacion_producto, ps.fecha_vencimiento, dc.cantidad FROM detalle_descargo dc
                    INNER JOIN vw_producto_sede_detallado ps ON ps.id_producto_sede = dc.id_producto_sede
                    WHERE dc.id_descargo = :id";
            $new = $this->con->prepare($sql);
            $new->execute([':id' => $this->id_descargo]);
            $detalle = $new->fetchAll(\PDO::FETCH_ASSOC);
            $this->desconectarDB();
            return ['descargo' => $descargo, 'detalle' => $detalle, 'img' => $img];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function mostrarProductos()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT id_producto_sede, presentacion_producto, fecha_vencimiento, cantidad FROM vw_producto_sede_detallado
                    WHERE id_sede = ? AND cantidad > 0";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $_SESSION['id_sede']);
            $new->execute();
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
                    AND id_sede = 1;";
            $new = $this->con->prepare($sql);
            $new->bindValue(":id_producto_sede", $this->id_producto);
            $new->execute();
            return $new->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    public function getAgregarDescargo($num_descargo, $fecha, $productos, $img = false): array
    {
        if (!$this->validarString('numero', $num_descargo)) {
            return $this->http_error(400, 'Numero de cargo inválido.');
        }

        $fecha =  date('Y-m-d', strtotime($fecha));
        if (!$this->validarFecha($fecha, 'Y-m-d')) {
            return $this->http_error(400, 'Fecha inválida.');
        }

        $estructura_productos = [
            'id_producto' => 'string',
            'cantidad' => 'string',
            'descripcion' => 'string'
        ];
        $productos = json_decode($productos, 1);
        if (!$this->validarEstructuraArray($productos, $estructura_productos, true)) {
            return $this->http_error(400, 'Productos inválidos.');
        }

        if ($img !== false) {
            $valid = $this->validarImagen($img, true);
            if (!$valid['valid']) {
                return $valid['res']();
            }
        }

        $this->num_descargo = $num_descargo;
        $this->fecha = $fecha;
        $this->productos = $productos;
        $this->img = $img;

        return $this->agregarDescargo();
    }

    private function agregarDescargo(): array
    {
        try {
            $this->conectarDB();
            $this->con->beginTransaction();
            $sql = "INSERT INTO descargo(fecha, num_descargo, id_sede, status) VALUES (?,?,?,1)";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->fecha);
            $new->bindValue(2, $this->num_descargo);
            $new->bindValue(3, $_SESSION['id_sede']);
            $new->execute();
            $this->id_descargo = $this->con->lastInsertId();

            if ($this->img !== false) {
                $res = $this->registrarImagenesDescargo();
                if ($res['resultado'] !== 'ok') {
                    $this->con->rollBack();
                    return $this->http_error(500, 'Hubo un error al subir imagen.');
                }
            }

            foreach ($this->productos as $producto) {
                [
                    'id_producto' => $cod_producto,
                    'cantidad' => $cantidad,
                    'descripcion' => $descripcion
                ] = $producto;
                $this->id_producto = $cod_producto;
                $producto_sede = $this->verificarExistenciaDelLote();
                if (is_array($producto_sede)) {
                    $this->con->rollBack();
                    return $producto_sede;
                }

                if (intval($producto_sede->cantidad) < intval($cantidad)) {
                    $this->con->rollBack();
                    return $this->http_error(400, 'Cantidad insuficiente en el inventario.');
                }

                $inventario = intval($producto_sede->cantidad) - intval($cantidad);
                $version = intval($producto_sede->version) + 1;
                $sql = "UPDATE producto_sede SET cantidad = :inventario, version = :version_nueva
                        WHERE id_producto_sede = :id_producto_sede AND version = :version_leida";
                $new = $this->con->prepare($sql);
                $new->bindValue(":inventario", $inventario);
                $new->bindValue(":version_nueva", $version);
                $new->bindValue(":id_producto_sede", $producto_sede->id_producto_sede);
                $new->bindValue(":version_leida", $producto_sede->version);
                $new->execute();
                if ($new->rowCount() == 0) {
                    $this->con->rollBack();
                    return $this->http_error(409, 'EL descargo falló debido al exceso de concurrencia.');
                }
                $this->id_producto = $producto_sede->id_producto_sede;


                $sql = "INSERT INTO detalle_descargo(id_descargo, id_producto_sede, cantidad, descripcion) VALUES (?,?,?,?)";
                $new = $this->con->prepare($sql);
                $new->bindValue(1, $this->id_descargo);
                $new->bindValue(2, $this->id_producto);
                $new->bindValue(3, $cantidad);
                $new->bindValue(4, $descripcion);
                $new->execute();

                $this->inventario_historial("Descargo", "", "x", "", $this->id_producto, $cantidad);
            }
            $this->binnacle("Descargo", $_SESSION['cedula'], "Registró un descargo.");
            $this->con->commit();
            return ['resultado' => 'ok', 'msg' => 'Se ha registrado el descargo correctamente.'];
        } catch (\PDOException $e) {
            $this->con->rollBack();
            return $this->http_error(500, $e->getMessage());
        } finally {
            $this->desconectarDB();
        }
    }

    private function registrarImagenesDescargo(): array
    {
        for ($i = 0; $i < count($this->img['name']); $i++) {
            $name = $this->randomRepository('assets/img/inventario/', $this->img['name'][$i], 'descargo_');
            if (!move_uploaded_file($this->img['tmp_name'][$i], $name)) {
                return ["resultado" => "error", "msg" => "No se pudo guardar la imagen"];
            }
            $new = $this->con->prepare("INSERT INTO img_descargo(id_descargo, img, status) VALUES (:id,:img,1)");
            $new->bindValue(':id', $this->id_descargo);
            $new->bindValue(':img', $name);
            $new->execute();
        }
        return ["resultado" => "ok", "msg" => "No se pudo guardar la imagen"];
    }

    public function getEliminarDescargo($id_descargo): array
    {
        if (!$this->validarString('entero', $id_descargo)) {
            return $this->http_error(400, 'Id descargo inválida.');
        }

        $this->id_descargo = $id_descargo;

        return $this->eliminarDescargo();
    }
    private function eliminarDescargo(): array
    {
        try {
            $this->conectarDB();
            $this->con->beginTransaction();
            $sql = "UPDATE descargo SET status = 0 WHERE id_descargo = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_descargo);
            $new->execute();

            $sql = "SELECT
                        ps.id_producto_sede,
                        dc.cantidad,
                        ps.cantidad as inventario,
                        ps.version
                    FROM
                        detalle_descargo dc
                        INNER JOIN producto_sede ps ON ps.id_producto_sede = dc.id_producto_sede
                    WHERE
                        id_descargo = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_descargo);
            $new->execute();
            $detalle_descargo = $new->fetchAll(\PDO::FETCH_OBJ);
            foreach ($detalle_descargo as $producto) {
                $inventario = intval($producto->cantidad) + intval($producto->inventario);
                $version = intval($producto->version) + 1;
                $new = $this->con->prepare("UPDATE producto_sede SET cantidad = :cantidad, version = :version_nueva WHERE id_producto_sede = :id AND version = :version_leida");
                $new->bindValue(':cantidad', $inventario);
                $new->bindValue(':version_nueva', $version);
                $new->bindValue(':id', $producto->id_producto_sede);
                $new->bindValue(':version_leida', $producto->version);
                $new->execute();
                if ($new->rowCount() == 0) {
                    $this->con->rollBack();
                    return $this->http_error(409, 'Eliminar el descargo falló debido al exceso de concurrencia.');
                }
            }
            $this->con->commit();
            return ['resultado' => 'ok', 'msg' => 'Se ha anulado el descargo correctamente.'];
        } catch (\PDOException $e) {
            $this->con->rollBack();
            return $this->http_error(500, $e->getMessage());
        } finally {

            $this->desconectarDB();
        }
    }
}
