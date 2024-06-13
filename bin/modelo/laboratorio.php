<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class laboratorio extends DBConnect {
  use validar;
  private $cod_lab;
  private $rif;
  private $direccion;
  private $razon;
  private $idedit;


  public function mostrarLaboratorios($bitacora) {
    try {
      $this->conectarDB();
      $sql = "SELECT rif_laboratorio, razon_social, direccion FROM laboratorio 
                  WHERE status = 1;";
      $new = $this->con->prepare($sql);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      if ($bitacora == "true") $this->binnacle("Laboratorio", $_SESSION['cedula'], "Consultó listado.");
      $this->desconectarDB();
      return $data;
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function getRegistrarLaboratorio($rif, $direccion, $razon) {

    if (!$this->validarString('rif', $rif))
      return $this->http_error(400, 'Rif inválido.');

    if (!$this->validarString('nombre', $razon))
      return $this->http_error(400, 'Rif inválido.');

    if (!$this->validarString('direccion', $direccion))
      return $this->http_error(400, 'Dirección inválida.');

    $this->rif = $rif;
    $this->direccion = $direccion;
    $this->razon = $razon;

    $this->idedit = false;
    $validarRif = $this->validarRif();
    if (!isset($validarRif['res'])) return $validarRif;

    return $this->registrarLaboratorio();
  }

  private function registrarLaboratorio() {

    try {
      $this->conectarDB();
      $new = $this->con->prepare("INSERT INTO laboratorio(rif_laboratorio,direccion,razon_social,status) VALUES(?,?,?,1)");
      $new->bindValue(1, $this->rif);
      $new->bindValue(2, $this->direccion);
      $new->bindValue(3, $this->razon);
      if (!$new->execute())
        return ['resultado' => 'error', 'msg' => 'Ha ocurrido un error en la base de datos.'];

      return ['resultado' => 'ok', 'msg' => "Se ha registrado el laboratorio {$this->razon}."];
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function getRif($rif) {
    if (!$this->validarString('rif', $rif))
      return $this->http_error(400, 'Rif inválido.');

    $this->rif = $rif;
    return $this->validarRif();
  }

  private function validarRif() {

    try {
      $this->conectarDB();

      $sql = "SELECT rif_laboratorio FROM laboratorio
              WHERE status = 1 AND rif_laboratorio = ?";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->rif);
      $new->execute();
      $data = $new->fetchAll();

      $this->desconectarDB();
      if (isset($data[0]['rif_laboratorio']))
        return $this->http_error(400, 'El rif ya está registrado.');
      else
        return ['resultado' => 'ok', 'msg' => 'Rif válido.', 'res' => false];
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }


  public function getItem($rif) {
    if (!$this->validarString('rif', $rif))
      return $this->http_error(400, 'Rif inválido.');

    $this->rif = $rif;

    return $this->selectItem();
  }

  private function selectItem() {

    try {
      $this->conectarDB();
      $sql = "SELECT rif_laboratorio, direccion, razon_social FROM laboratorio 
                  WHERE status = 1 and rif_laboratorio = ? ;";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->rif);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      $this->desconectarDB();
      return $data;
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function getEditar($rif, $direccion, $razon, $id) {

    if (!$this->validarString('rif', $rif))
      return $this->http_error(400, 'Rif inválido.');

    if (!$this->validarString('nombre', $razon))
      return $this->http_error(400, 'Rif inválido.');

    if (!$this->validarString('direccion', $direccion))
      return $this->http_error(400, 'Dirección inválida.');

    if (!$this->validarString('rif', $id))
      return $this->http_error(400, 'Rif inválido.');

    $this->rif = $rif;
    $this->direccion = $direccion;
    $this->razon = $razon;
    $this->idedit = $id;

    if ($this->idedit !== $this->rif) {
      $validarRif = $this->validarRif();
      if ($validarRif['res']) return $this->http_error(400, $validarRif['msg']);
    }

    return $this->editarLaboratorio();
  }

  private function editarLaboratorio() {

    try {
      $this->conectarDB();
      $sql = "UPDATE laboratorio 
              SET rif_laboratorio = ? , direccion = ? , razon_social = ?
              WHERE rif_laboratorio = ?";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->rif);
      $new->bindValue(2, $this->direccion);
      $new->bindValue(3, $this->razon);
      $new->bindValue(4, $this->idedit);
      $new->execute();
      $this->binnacle("Laboratorio", $_SESSION['cedula'], "Editó laboratorio.");
      $this->desconectarDB();
      return ['resultado' => 'ok', "msg" => "Se ha editado correctamente el laboratorio {$this->rif}."];
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }


  public function getEliminar($rif) {
    if (!$this->validarString('rif', $rif))
      return $this->http_error(400, 'Rif inválido.');

    $this->rif = $rif;

    return $this->eliminarLaboratorio();
  }

  private function eliminarLaboratorio() {
    try {
      $this->conectarDB();
      $new = $this->con->prepare("UPDATE laboratorio SET status = 0 WHERE rif_laboratorio = ?; ");
      $new->bindValue(1, $this->rif);
      $new->execute();
      $this->binnacle("Laboratorio", $_SESSION['cedula'], "Eliminó laboratorio.");
      $this->desconectarDB();
      return ['resultado' => 'ok', "msg" => "Se ha eliminado correctamente el laboratorio {$this->rif}."];
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }
}
