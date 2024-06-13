<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

use function PHPUnit\Framework\returnValue;

class moneda extends DBConnect
{
  use validar;
  private $moneda;
  private $alcambio;
  private $id;
  private $idedit;
  private $fechaActual;


  public function getMoneda($bitacora = false)
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT m.id_moneda, m.nombre, format(m.valor,2,'de_DE') as cambio, tabla_cambio.fecha FROM moneda m 
                                  LEFT JOIN (
                                      SELECT c.cambio, c.fecha, c.moneda FROM cambio c 
                                      WHERE c.status = 1
                                      ORDER BY c.fecha ASC LIMIT 99999
                                  ) as tabla_cambio ON tabla_cambio.moneda = m.id_moneda
                                  WHERE m.status = 1
                                  GROUP BY m.id_moneda;");
      $new->execute();
      $data = $new->fetchAll();

      if ($bitacora) $this->binnacle("Moneda", $_SESSION['cedula'], "Consultó listado de Moneda.");
      parent::desconectarDB();
      return $data;
    } catch (\PDOException $error) {

      return $this->http_error(500, $error);
    }
  }

  public function getAgregarMoneda($name)
  {
    if (!$this->validarString('nombre', $name)) {
      return $this->http_error(400, 'Moneda invalida.');
    }
    $this->moneda = $name;
    return $this->agregarMoneda();
  }

  private function agregarMoneda()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT id_moneda, nombre, status FROM moneda WHERE nombre = ?");
      $new->bindValue(1, $this->moneda);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();
      // return $data;

      if (isset($data[0]['id_moneda'])) {
        parent::conectarDB();
        $new = $this->con->prepare("UPDATE `moneda` SET `status`= 1 WHERE id_moneda = ?");
        $new->bindValue(1, $data[0]['id_moneda']);
        $new->execute();
        $resultado = ['resultado' => 'Registado con exito'];
        $this->binnacle("Moneda", $_SESSION['cedula'], "Registró una Moneda.");
        parent::desconectarDB();
        return $resultado;
      }

      parent::conectarDB();
      $new = $this->con->prepare("INSERT INTO `moneda`(`id_moneda`, `nombre`, `status`) VALUES (DEFAULT,?,1)");
      $new->bindValue(1, $this->moneda);
      $new->execute();
      $resultado = ['resultado' => 'Registado con exito'];
      $this->binnacle("Moneda", $_SESSION['cedula'], "Registró una Moneda.");
      parent::desconectarDB();
      return $resultado;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function mostrarM($id)
  {
    $this->id = $id;

    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT * FROM `moneda` WHERE id_moneda = ?");
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();
      return $data;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function getEditarM($nameEdit, $id)
  {
    if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{3,30}$/", $nameEdit) == false) {
      return ['resultado' => 'error', 'error' => 'Nombre de Moneda Invalida'];
    }

    $this->id = $id;
    $this->moneda = $nameEdit;

    return $this->editarM();
  }

  private function editarM()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE moneda SET nombre = ? WHERE status = 1 AND id_moneda = ?");
      $new->bindValue(1, $this->moneda);
      $new->bindValue(2, $this->id);
      $new->execute();
      $resultado = ['resultado' => 'Actualizado con exito'];
      $this->binnacle("Moneda", $_SESSION['cedula'], "Editó una Moneda.");
      parent::desconectarDB();
      return $resultado;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function getEliminarM($id)
  {

    try {
      if ($id == 1 || $id == 2) return $this->http_error(400, 'No se Puede Eliminar esta Moneda');
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE moneda SET status = 0 WHERE id_moneda = ? AND status = 1");
      $new->bindValue(1, $id);
      $new->execute();
      $data = ['resultado' => 'Eliminado con exito'];
      $this->binnacle("Moneda", $_SESSION['cedula'], "Eliminó una Moneda.");
      parent::desconectarDB();
      return $data;
    } catch (\PDOException $error) {

      return $this->http_error(500, $error);
    }
  }

  public function getAgregarCambio($alcambio, $tipo)
  {

    if (preg_match_all("/^[0-9]{1,30}$/", $tipo) != 1) {
      $resultado = ['resultado' => 'Error', 'error' => 'moneda inválido.'];
      return $resultado;
    }
    if (preg_match_all("/^([0-9]+\.+[0-9]|[0-9])+$/", $alcambio) != 1) {
      $resultado = ['resultado' => 'Error', 'error' => 'valor inválido.'];
      return $resultado;
    }

    $this->moneda = $tipo;
    $this->alcambio = $alcambio;

    return $this->agregarCambio();
  }

  private function agregarCambio()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("INSERT INTO `cambio`(`id_cambio`, `cambio`, `fecha`, `moneda`, `status`) VALUES (DEFAULT,?,DEFAULT,?,1)");
      $new->bindValue(1, $this->alcambio);
      $new->bindValue(2, $this->moneda);
      $new->execute();
      $resultado = ['resultado' => 'Registado con exito'];
      $this->binnacle("Moneda", $_SESSION['cedula'], "Registró un Valor de Moneda.");
      parent::desconectarDB();
      $this->actualizarValor($this->alcambio, $this->moneda);
      return $resultado;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }
  public function getMostrarCambio($nombreMon)
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT m.nombre, format(tabla_cambio.cambio,2,'de_DE') as cambio, tabla_cambio.fecha, tabla_cambio.id_cambio FROM moneda m LEFT JOIN ( SELECT c.cambio, c.fecha, c.id_cambio, c.moneda FROM cambio c WHERE c.status = 1 ORDER BY c.fecha ASC LIMIT 99999 ) as tabla_cambio ON tabla_cambio.moneda = m.id_moneda WHERE m.status = 1 AND m.id_moneda = ?");
      $new->bindValue(1, $nombreMon);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();
      return $data;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function SelectM()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT id_moneda, nombre FROM `moneda` WHERE status = 1");
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();
      return $data;
    } catch (\PDOException $error) {

      return $this->http_error(500, $error);
    }
  }

  public function getEliminarCambio($id)
  {
    $this->id = $id;
    return $this->eliminarCambio();
  }

  private function eliminarCambio()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE `cambio` SET `status` = '0' WHERE `id_cambio` = ? and status = 1");
      $new->bindValue(1, $this->id);
      $new->execute();
      $this->binnacle("Moneda", $_SESSION['cedula'], "Eliminó un Valor de Moneda.");

      $new = $this->con->prepare("SELECT m.id_moneda, c2.id_cambio, c2.cambio, c2.fecha FROM cambio c1 JOIN moneda m ON c1.moneda = m.id_moneda JOIN cambio c2 ON m.id_moneda = c2.moneda WHERE c1.id_cambio = ? AND c2.status = 1 AND c2.fecha = ( SELECT MAX(c3.fecha) FROM cambio c3 WHERE c3.moneda = c1.moneda AND c3.status = 1 );");
      $new->bindValue(1, $this->id);

      $new->execute();
      $dato = $new->fetchAll();
      parent::desconectarDB();
      $this->actualizarValor($dato[0]['cambio'], $dato[0]['id_moneda']);
      $resultado = ['resultado' => 'Eliminado'];
      return $resultado;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error->getMessage());
    }
  }


  public function mostrarUnico($unico)
  {
    $this->id = $unico;
    return $this->unico();
  }

  private function unico()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT * FROM `cambio` WHERE id_cambio = ?");
      $new->bindValue(1, $this->id);
      $new->execute();
      $datas = $new->fetchAll();

      parent::desconectarDB();
      return $datas;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function getEditarCambio($alcambio, $moneda, $unico)
  {

    if (preg_match_all("/^[0-9]{1,30}$/", $moneda) != 1) {
      $resultado = ['resultado' => 'Error', 'error' => 'Moneda inválido.'];
      return $resultado;
    }
    if (preg_match_all("/^([0-9]+\.+[0-9]|[0-9])+$/", $alcambio) != 1) {
      $resultado = ['resultado' => 'Error', 'error' => 'Cambio inválido.'];
      return $resultado;
    }

    $this->moneda = $moneda;
    $this->alcambio = $alcambio;
    $this->idedit = $unico;

    date_default_timezone_set("america/caracas");
    $this->fechaActual = date("Y-m-d G:i:s");

    return $this->editarCambio();
  }

  private function editarCambio()
  {
    try {
      parent::conectarDB();

      $new = $this->con->prepare("UPDATE `cambio` SET `cambio`= ?,`moneda`= ? WHERE id_cambio = ? and status = 1");
      $new->bindValue(1, $this->alcambio);
      $new->bindValue(2, $this->moneda);
      $new->bindValue(3, $this->idedit);
      $new->execute();
      $data = $new->fetchAll();

      $new = $this->con->prepare("SELECT c.cambio, c.fecha FROM cambio c INNER JOIN moneda m ON m.id_moneda = c.moneda
                                    WHERE c.moneda = ?
                                    AND c.fecha = (
                                        SELECT MAX(fecha)
                                        FROM cambio
                                        WHERE moneda = ?)");
      $new->bindValue(1, $this->moneda);
      $new->bindValue(2, $this->moneda);
      $new->execute();
      $dato = $new->fetchAll();
      $this->binnacle("Moneda", $_SESSION['cedula'], "Editó un Valor de Moneda.");
      parent::desconectarDB();
      $this->actualizarValor($dato[0]['cambio'], $this->moneda);
      $resultado = ['resultado' => 'Editado'];
      return $resultado;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }



  private function actualizarValor($valor, $moneda)
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE moneda SET valor = ? WHERE id_moneda = ? AND status = 1");
      $new->bindValue(1, $valor);
      $new->bindValue(2, $moneda);
      $new->execute();
      parent::desconectarDB();
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function getValidarMon($id, $moneda)
  {
    if ($moneda != " ") {
      if (!$this->validarString('nombre', $moneda)) return $this->http_error(400, 'Moneda invalida.');
    }

    if ($id != " ") {
      if (!$this->validarString('entero', $id)) return $this->http_error(400, 'Id invalido.');
    }

    $this->id = $id;
    $this->moneda = $moneda;

    return $this->validarMon();
  }

  private function validarMon()
  {
    try {
      if ($this->moneda == " ") { // Eliminar Moneda
        parent::conectarDB();
        $new = $this->con->prepare("SELECT nombre, status FROM moneda WHERE id_moneda = ? and status = 1");
        $new->bindValue(1, $this->id);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();
        if (isset($data[0]['nombre'])) return ['resultado' => 'Correcto'];
        return $this->http_error(404, 'Moneda no Registrada');
      } else { // Editar Moneda
        parent::conectarDB();
        $new = $this->con->prepare("SELECT nombre, status FROM moneda WHERE id_moneda <> ? AND nombre = ?");
        $new->bindValue(1, $this->id);
        $new->bindValue(2, $this->moneda);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();
        if (isset($data[0]['status'])) {
          if ($data[0]['status'] == 1) return $this->http_error(400, 'Moneda ya Registrada');
          if ($data[0]['status'] == 0 && $this->id != " ") return $this->http_error(400, 'No se Puede Editar');
        }
        return ['resultado' => 'Correcto'];
      }
      return $this->http_error(400, 'Desconocido');
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }




}
