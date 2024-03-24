<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;

class transferencia extends DBConnect {

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

  public function mostrarProductos() {
    try {
      $this->conectarDB();
      $sql = "SELECT * FROM producto_sede WHERE id_sede = 5;";
      $new = $this->con->prepare($sql);
      $new->execute();
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

  public function getAgregarTransferencia(): array {
    try {
      $this->conectarDB();
      $sql = '';
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->id_transferencia);
    } catch (\Throwable $th) {
      //throw $th;
    }
  }
}
