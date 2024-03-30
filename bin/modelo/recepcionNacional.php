<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class recepcionNacional extends DBConnect {
  use validar;
  private $id_rep_nacional;
  private $id_proveedor;
  private $fecha;
  private $productos;
  private $id_producto;

  public function mostrarRecepciones($bitacora) {
    try {
      $this->conectarDB();
      $sql = "SELECT rn.id_rep_nacional, p.razon_social, rn.fecha FROM recepcion_nacional rn
              INNER JOIN proveedor p ON p.rif_proveedor = rn.id_proveedor
              WHERE rn.status = 1;";
      $new = $this->con->prepare($sql);
      $new->execute();
      // if ($bitacora == "true") $this->binnacle("Laboratorio", $_SESSION['cedula'], "Consultó listado.");
      $this->desconectarDB();
      return $new->fetchAll(\PDO::FETCH_OBJ);;
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function getMostrarDetalle($id_rep_nacional) {
    if (preg_match_all("/^[0-9]{1,10}$/", $id_rep_nacional) != 1)
      return $this->http_error(400, 'Producto invalido.');

    $this->id_rep_nacional = $id_rep_nacional;

    return $this->mostrarDetalle();
  }
  private function mostrarDetalle() {
    try {
      $this->conectarDB();
      $sql = "SELECT drn.cantidad, p.razon_social, ps.presentacion_producto, ps.lote, ps.fecha_vencimiento FROM recepcion_nacional rn
      INNER JOIN detalle_recepcion_nacional drn ON drn.id_rep_nacional = rn.id_rep_nacional
      INNER JOIN proveedor p ON p.rif_proveedor = rn.id_proveedor
      INNER JOIN vw_producto_sede_detallado ps ON ps.id_producto_sede = drn.id_producto_sede
      WHERE rn.id_rep_nacional = ?;";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->id_rep_nacional);
      $new->execute();
      $this->desconectarDB();
      return $new->fetchAll(\PDO::FETCH_OBJ);
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function mostrarProveedores() {
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

  public function mostrarProductos() {
    try {
      $this->conectarDB();
      $sql = "SELECT p.cod_producto, concat(tp.nombrepro,' ',pres.peso,' ',med.nombre,' ') AS presentacion_producto, tp.nombrepro as nombre_producto, pres.peso as presentacion_peso, med.nombre as medida, tipo.nombre_t as tipo, clase.nombre_c as clase
              FROM producto p 
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
  private function verificarExistenciaDelLote($cod_producto, $lote, $fecha_vencimiento) {
    try {
      $sql = "SELECT id_producto_sede, cantidad FROM producto_sede 
              WHERE lote = :lote AND fecha_vencimiento = :fecha_vencimiento 
              AND cod_producto = :cod_producto AND id_sede = 1;";
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
  public function getAgregarRecepcionNacional($id_proveedor, $fecha, $productos): array {
    // if (preg_match_all("/^[0-9]{1,10}$/", $id_proveedor) != 1)
    //   return $this->http_error(400, 'Transferencia inválida.');

    $fecha =  date('Y-m-d', strtotime($fecha));
    if ($this->validarFecha($fecha, 'Y-m-d') !== true)
      return $this->http_error(400, 'Fecha inválida.');

    if (!is_array($productos))
      return $this->http_error(400, 'Productos inválidos.');

    $this->id_proveedor = $id_proveedor;
    $this->fecha = $fecha;
    $this->productos = $productos;

    return $this->agregarRecepcionNacional();
  }

  private function agregarRecepcionNacional(): array {
    try {
      $this->conectarDB();
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
        $fecha_vencimiento = date('Y-m-d', strtotime($fecha));

        $producto_sede = $this->verificarExistenciaDelLote($cod_producto, $lote, $fecha_vencimiento);
        if (isset($producto_sede->id_producto_sede)) {
          $inventario = intval($producto_sede->cantidad) + intval($cantidad);
          $sql = "UPDATE producto_sede SET cantidad = :inventario 
                  WHERE id_producto_sede = :id_producto_sede";
          $new = $this->con->prepare($sql);
          $new->bindValue(":inventario", $inventario);
          $new->bindValue(":id_producto_sede", $producto_sede->id_producto_sede);
          $new->execute();
          $this->id_producto = $producto_sede->id_producto_sede;
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
      }

      $this->desconectarDB();
      return ['resultado' => 'ok', 'msg' => 'Se ha registrado la recepcion correctamente.'];
    } catch (\PDOException $e) {
      return ['error' => $e->getMessage()];
    }
  }

  public function getEliminarRecepcionNacional($id_rep_nacional): array {
    if (preg_match_all("/^[0-9]{1,10}$/", $id_rep_nacional) != 1)
      return $this->http_error(400, 'Transferencia inválida.');

    $this->id_rep_nacional = $id_rep_nacional;

    return $this->eliminarRecepcionNacional();
  }
  private function eliminarRecepcionNacional(): array {
    try {
      $this->conectarDB();

      $sql = "UPDATE recepcion_nacional SET status = 0 WHERE id_rep_nacional = ?";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->id_rep_nacional);
      $new->execute();

      $sql = "SELECT ps.id_producto_sede, dn.cantidad, ps.cantidad as inventario FROM detalle_recepcion_nacional dn
              INNER JOIN producto_sede ps ON ps.id_producto_sede = dn.id_producto_sede
              WHERE id_rep_nacional = ?;";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->id_rep_nacional);
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
      return ['resultado' => 'ok', 'msg' => 'Se ha anulado la recepcion nacional correctamente.'];
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }
}
