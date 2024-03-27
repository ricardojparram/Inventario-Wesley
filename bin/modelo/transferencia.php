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

  public function mostrarProductoInventario($id_producto): array {
    if (preg_match_all("/^[0-9]{1,10}$/", $id_producto) != 1)
      return ['resultado' => 'error', 'msg' => 'Id invalida.'];
    try {
      $this->conectarDB();
      $sql = "SELECT cantidad FROM producto_sede WHERE id_producto_sede = ?;";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $id_producto);
      $new->execute();
      $this->desconectarDB();
      return $new->fetchAll(\PDO::FETCH_OBJ);
    } catch (\PDOException $th) {
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
    if (preg_match_all("/^[0-9]{1,10}$/", $id_transferencia) != 1)
      return ['resultado' => 'error', 'msg' => 'Id invalida.'];

    $this->id_transferencia = $id_transferencia;

    return $this->mostrarDetalle();
  }
  private function mostrarDetalle(): array {
    try {
      $this->conectarDB();
      $sql = "SELECT s.nombre as nombre_sede, ps.lote, p.cod_producto, dt.cantidad, ps.fecha_vencimiento FROM detalle_transferencia dt
              INNER JOIN transferencia t ON dt.id_detalle = t.id_transferencia
              INNER JOIN producto_sede ps ON ps.id_producto_sede = dt.id_lote
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

  public function getAgregarTransferencia($id_sede, $fecha): array {
    if (preg_match_all("/^[0-9]{1,10}$/", $id_sede) != 1)
      return ['resultado' => 'error', 'msg' => 'Id invalida.'];

    if ($this->validarFecha($fecha) !== true)
      return ['resultado' => 'error', 'msg' => 'Fecha inválida'];


    $this->id_sede = $id_sede;
  }
  private function agregarTransferencia(): array {
    try {
      $this->conectarDB();
      $sql = '';
      $new = $this->con->prepare($sql);
      return $new->bindValue(1, $this->id_transferencia);
    } catch (\PDOException $e) {
      return ['error' => $e->getMessage()];
    }
  }
}
