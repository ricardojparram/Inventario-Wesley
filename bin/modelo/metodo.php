<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class metodo extends DBConnect
{

  use validar;
  private $metodo;
  private $id;

  public function getMostrarMetodo($bitacora = false)
  {

    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT fp.id_forma_pago , fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1");
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      if ($bitacora)
        $this->binnacle("Metodo", $_SESSION['cedula'], "Consultó listado metodo de pago.");

      parent::desconectarDB();
      return $data;
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function validarMetodo($metodo, $id)
  {
    if (!$this->validarString('string', $metodo))
      return $this->http_error(400, 'metodo invalido.');

    $this->id = ($id === 'false') ? false : $id;
    $this->metodo = $metodo;

    return $this->validMetodo();
  }

  private function validMetodo()
  {
    try {
      parent::conectarDB();
      if ($this->id === false) {
        $new = $this->con->prepare('SELECT fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1 AND fp.tipo_pago = ?');
        $new->bindValue(1, $this->metodo);
      } else {
        $new = $this->con->prepare('SELECT fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1 AND fp.tipo_pago = ? AND fp.id_forma_pago != ?');
        $new->bindValue(1, $this->metodo);
        $new->bindValue(2, $this->id);
      }

      $new->execute();
      $data = $new->fetchAll();

      if (isset($data[0]['tipo_pago'])) {
        $resultado = ['resultado' => 'error', 'msg' => 'El metodo ya está registrado.', 'res' => false];
      } else {
        $resultado = ['resultado' => 'metodo valido', 'res' => true];
      }

      parent::desconectarDB();
      return $resultado;
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function validarExitencia($id)
  {
    if (!$this->validarString('entero', $id))
      return $this->http_error(400, 'id metodo inválido.');

    $this->id = $id;

    return $this->validExistencia();
  }

  private function validExistencia()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare('SELECT fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1 AND fp.id_forma_pago = ?');
      $new->bindValue(1,  $this->id);
      $new->execute();
      $data = $new->fetchAll();

      parent::desconectarDB();

      if (isset($data[0]["tipo_pago"])) {
        return ['resultado' => 'Si existe este metodo.'];
      } else {
        return ['resultado' => 'Error de metodo'];
      }
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function getAgregarMetodo($metodo)
  {
    if (!$this->validarString('string', $metodo))
      return $this->http_error(400, 'metodo invalido.');

    $this->metodo = $metodo;

    $this->id = false;
    $validarMetodo = $this->validMetodo();
    if ($validarMetodo['res'] === false) {
      return ['resultado' => 'error', 'msg' => 'El metodo ya está registrado.'];
    }

    return $this->agregarMetodo();
  }

  private function agregarMetodo()
  {
    try {
      parent::conectarDB();

      $new = $this->con->prepare("INSERT INTO `forma_pago`(`id_forma_pago`, `tipo_pago`, `status`) VALUES (DEFAULT,?,1)");

      $new->bindValue(1, $this->metodo);
      $new->execute();
      $data = $new->fetchAll();

      $resultado = ["resultado" => "registrado correctamente"];
      $this->binnacle("Metodo", $_SESSION['cedula'], "Registró un metodo de pago.");
      parent::desconectarDB();
      return $resultado;
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }


  public function mostrarEdit($id)
  {
    if (!$this->validarString('entero', $id))
      return $this->http_error(400, 'id metodo inválido.');

    $this->id = $id;

    return $this->selectEdit();
  }


  private function selectEdit()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1 AND fp.id_forma_pago = ?");
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function getEditarMetodo($metodo, $id)
  {
    if (!$this->validarString('string', $metodo))
      return $this->http_error(400, 'metodo invalido.');

    if (!$this->validarString('entero', $id))
      return $this->http_error(400, 'id metodo inválido.');

    $this->metodo = $metodo;
    $this->id = $id;

    $validarMetodo = $this->validMetodo();
    if ($validarMetodo['res'] === false) {
      return ['resultado' => 'error', 'msg' => 'El metodo ya está registrado.'];
    }

    return $this->editarMetodo();
  }
  private function editarMetodo()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE forma_pago fp SET fp.tipo_pago = ? WHERE fp.status = 1 AND fp.id_forma_pago = ?");
      $new->bindValue(1, $this->metodo);
      $new->bindValue(2, $this->id);
      $new->execute();

      $resultado = ['resultado' => 'Editado'];
      $this->binnacle("Metodo", $_SESSION['cedula'], "Editó un metodo de pago.");

      parent::desconectarDB();
      return $resultado;
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  public function getEliminarMetodo($id)
  {
    if (!$this->validarString('entero', $id))
      return $this->http_error(400, 'id metodo inválido.');

      if (!$this->validarMetodoSiTieneRegistros($id)) {
        return $this->http_error(403, "No se puede eliminar el metodo porque ya tiene registros.");
      }

    $this->id = $id;

    return $this->eliminarMetodo();
  }

  private function eliminarMetodo()
  {

    try {
      parent::conectarDB();

      $new = $this->con->prepare("UPDATE forma_pago fp SET fp.status = 0 WHERE fp.id_forma_pago = ?");
      $new->bindValue(1, $this->id);
      $new->execute();
      $resultado = ['resultado' => 'Eliminado'];
      $this->binnacle("Metodo", $_SESSION['cedula'], "Eliminó un metodo de pago.");
      parent::desconectarDB();

      return $resultado;
    } catch (\PDOException $e) {
      return $this->http_error(500, $e->getMessage());
    }
  }

  private function validarMetodoSiTieneRegistros($id)
  {
    try {
      $this->conectarDB();
      $sql = "SELECT (COUNT(dp.id_forma_pago)) AS count FROM forma_pago fp LEFT JOIN detalle_pago dp ON dp.id_forma_pago = fp.id_forma_pago WHERE fp.id_forma_pago = :id";
      $new = $this->con->prepare($sql);
      $new->bindValue(':id', $id);
      $new->execute();
      $data = $new->fetch(\PDO::FETCH_OBJ);
      $this->desconectarDB();
      return intval($data->count) === 0;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error->getMessage());
    }
  }
}
