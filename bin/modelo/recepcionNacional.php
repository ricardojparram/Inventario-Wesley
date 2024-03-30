<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class recepcionNacional extends DBConnect {
  use validar;
  private $id_rep_nacional;
  private $proveedor;
  private $fecha;
  private $estado_producto;

  public function mostrarRecepciones($bitacora) {
    try {
      $this->conectarDB();
      $sql = "SELECT rn.id_rep_nacional, p.razon_social, rn.fecha FROM recepcion_nacional rn
              INNER JOIN proveedor p ON p.rif_proveedor = rn.id_proveedor;
              WHERE rn.status = 1;";
      $new = $this->con->prepare($sql);
      $new->execute();
      // if ($bitacora == "true") $this->binnacle("Laboratorio", $_SESSION['cedula'], "Consultó listado.");
      $this->desconectarDB();
      return $new->fetchAll(\PDO::FETCH_OBJ);;
    } catch (\PDOException $e) {
      return ['error' => $e->getMessage()];
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
      return ['error' => $e->getMessage()];
    }
  }
}
