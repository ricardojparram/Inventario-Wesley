<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use DateTime;
use utils\validar;

class transferencia extends DBConnect {
  use validar;
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
      return $new->fetchAll(\PDO::FETCH_OBJ);
    } catch (\PDOException $e) {
      return ['error' => $e->getMessage()];
    }
  }
  public function validarFecha($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
  }
  /* TO DO: Realizar una buena query para traer los datos del producto. */
  public function mostrarProductos() {
    try {
      $this->conectarDB();
      $sql = "SELECT id_producto_sede, lote FROM producto_sede WHERE id_sede = 1;";
      $new = $this->con->prepare($sql);
      $new->execute();
      return $new->fetchAll(\PDO::FETCH_OBJ);
    } catch (\PDOException $e) {
      return ['error' => $e->getMessage()];
    }
  }

  public function getMostrarProductoInventario($id_producto) {
    if (preg_match_all("/^[0-9]{1,10}$/", $id_producto) != 1)
      return ['resultado' => 'error', 'msg' => 'Id invalida.'];

    $this->id_producto = $id_producto;

    return $this->mostrarProductoInventario();
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
  public function getMostrarDetalle($id_transferencia): array {
    if (preg_match_all("/^[0-9]{1,10}$/", $id_transferencia) != 1) {
      http_response_code(400);
      return ['resultado' => 'error', 'msg' => 'Producto invalido.'];
    }

    $this->id_transferencia = $id_transferencia;

    return $this->mostrarDetalle();
  }
  private function mostrarDetalle(): array {
    try {
      $this->conectarDB();
      $sql = "SELECT s.nombre as nombre_sede, ps.lote, p.cod_producto, dt.cantidad, ps.fecha_vencimiento FROM detalle_transferencia dt
              INNER JOIN transferencia t ON dt.id_transferencia = t.id_transferencia
              INNER JOIN producto_sede ps ON ps.id_producto_sede = dt.id_producto_sede
              INNER JOIN producto p ON p.cod_producto = ps.cod_producto 
              INNER JOIN sede s ON s.id_sede = t.id_sede
              WHERE t.id_transferencia = ?;";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->id_transferencia);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      $this->desconectarDB();
      return $data;
    } catch (\PDOException $e) {
      return ['error' => $e->getMessage()];
    }
  }

  public function getAgregarTransferencia($id_sede, $fecha, $productos): array {
    if (preg_match_all("/^[0-9]{1,10}$/", $id_sede) != 1) {
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
    $this->id_sede = $id_sede;

    return $this->agregarTransferencia();
  }
  private function agregarTransferencia(): array {
    try {
      $this->conectarDB();
      $sql = "INSERT INTO transferencia (id_sede, fecha, status) VALUES (?,?,1)";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->id_sede);
      $new->bindValue(2, $this->fecha);
      $new->execute();
      $this->id_transferencia = $this->con->lastInsertId();

      $sql = "INSERT INTO detalle_transferencia(id_transferencia, id_producto_sede, cantidad) VALUES (?,?,?)";
      foreach ($this->productos as $producto) {
        $this->id_producto = $producto['id_producto'];
        [$data] = $this->mostrarProductoInventario();
        $this->conectarDB();
        $new = $this->con->prepare($sql);
        $new->bindValue(1, $this->id_transferencia);
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

  public function getEliminarTransferencia($id_transferencia): array {
    if (preg_match_all("/^[0-9]{1,10}$/", $id_transferencia) != 1) {
      http_response_code(400);
      return ['resultado' => 'error', 'msg' => 'Id invalida.'];
    }

    $this->id_transferencia = $id_transferencia;

    return $this->eliminarTransferencia();
  }

  private function eliminarTransferencia() {
    try {
      $sql = "UPDATE transferencia SET status = 0 WHERE id_transferencia = ?";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->id_transferencia);
      $new->execute();
      $this->desconectarDB();
      return ['resultado' => 'ok', 'msg' => 'Se ha eliminado la transferencia correctamente.'];
    } catch (\PDOException $e) {
      return ['error' => $e->getMessage()];
    }
  }
}
