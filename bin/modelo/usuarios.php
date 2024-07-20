<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class usuarios extends DBConnect
{
  use validar;
  private $cedula;
  private $name;
  private $apellido;
  private $email;
  private $password;
  private $rol;
  private $id;

  private $key;
  private $iv;
  private $cipher;

  function __construct()
  {
    parent::__construct();
  }

  public function getAgregarUsuario($cedula, $name, $apellido, $email, $password, $tipoUsuario)
  {

    if (!$this->validarString('nombre', $name)) {
      return $this->http_error(400, 'Nombre inválido.');
    }
    if (!$this->validarString('nombre', $apellido)) {
      return $this->http_error(400, 'Apellido invalido.');
    }
    if (!$this->validarString('documento', $cedula)) {
      return $this->http_error(400, 'Documento invalido.');
    }
    if (!$this->validarString('correo', $email)) {
      return $this->http_error(400, 'Correo invalido.');
    }
    if (!$this->validarString('contraseña', $password)) {
      return $this->http_error(400, 'Contraseña invalida.');
    }
    if (!$this->validarString('numero', $tipoUsuario)) {
      return $this->http_error(400, 'Tipo de Usuario invalido.');
    }

    $this->cedula = $cedula;
    $this->name = $name;
    $this->apellido = $apellido;
    $this->email = $email;
    $this->password = $password;
    $this->rol = $tipoUsuario;

    return $this->agregarUsuario();
  }

  private function agregarUsuario()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT `cedula`, `status` FROM `usuario` WHERE `cedula` = ?");
      $new->bindValue(1, $this->cedula);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();
      if (!isset($data[0]['status'])) {

        parent::conectarDB();
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $new = $this->con->prepare("INSERT INTO `usuario`(`cedula`, `nombre`, `apellido`, `correo`, `password`, `rol`, `status`) VALUES (?,?,?,?,?,?,1)");
        $new->bindValue(1, $this->cedula);
        $new->bindValue(2, $this->name);
        $new->bindValue(3, $this->apellido);
        $new->bindValue(4, $this->email);
        $new->bindValue(5, $this->password);
        $new->bindValue(6, $this->rol);
        $new->execute();
        $resultado = ['resultado' => 'Registrado correctamente.'];
        $this->binnacle("Usuario", $_SESSION['cedula'], "Registró un usuario");
        parent::desconectarDB();
      } elseif ($data[0]['status'] == 0) {

        parent::conectarDB();
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $new = $this->con->prepare("UPDATE `usuario` SET `nombre`= ? ,`apellido`= ? ,`correo`= ? ,`password`= ? ,`rol`= ? ,`status`= 1  WHERE `cedula` = ?");
        $new->bindValue(1, $this->name);
        $new->bindValue(2, $this->apellido);
        $new->bindValue(3, $this->email);
        $new->bindValue(4, $this->password);
        $new->bindValue(5, $this->rol);
        $new->bindValue(6, $this->cedula);
        $new->execute();
        $resultado = ['resultado' => 'Registrado correctamente.'];
        $this->binnacle("Usuario", $_SESSION['cedula'], "Registró un usuario");
        parent::desconectarDB();
      } else {
        return $this->http_error(500, "error desconocido");
      }
      return $resultado;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function getMostrarUsuario($bitacora = false)
  {

    try {
      parent::conectarDB();
      $query = "SELECT u.cedula, u.nombre, u.apellido, u.correo, r.nombre as rol FROM usuario u INNER JOIN rol r ON u.rol = r.id_rol WHERE u.status = 1 AND u.cedula <> 'V-123123123'";

      $new = $this->con->prepare($query);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      if ($bitacora)
        $this->binnacle("Usuario", $_SESSION['cedula'], "Consultó listado Usuarios.");

      parent::desconectarDB();
      return $data;
    } catch (\PDOException $error) {

      return $this->http_error(500, $error);
    }
  }

  public function mostrarRol()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT * FROM `rol` WHERE status = 1");
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;
    } catch (\PDOException $error) {

      return $this->http_error(500, $error);
    }
  }

  public function getEliminar($cedula)
  {
    $this->cedula = $cedula;

    return $this->eliminarUsuario();
  }

  private function eliminarUsuario()
  {
    try {
      if ($this->cedula == $_SESSION['cedula']) {
        $resultado = ['resultado' => 'Error', 'msg' => 'No se puede Eliminar su Propia Cuenta'];
        return $resultado;
      }

      parent::conectarDB();
      $new = $this->con->prepare("UPDATE `usuario` SET `status` = '0' WHERE `usuario`.`cedula` = ?"); //"DELETE FROM `usuario` WHERE `usuario`.`cedula` = ?"
      $new->bindValue(1, $this->cedula);
      $new->execute();
      $resultado = ['resultado' => 'Eliminado'];

      $this->binnacle("Usuario", $_SESSION['cedula'], "Eliminó un usuario");
      parent::desconectarDB();
      return $resultado;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function getUnico($cedula)
  {
    $this->cedula = $cedula;

    return $this->seleccionarUnico();
  }

  private function seleccionarUnico()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT cedula, nombre, apellido, correo, rol FROM `usuario` WHERE `usuario`.`cedula` = ?");
      $new->bindValue(1, $this->cedula);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      // $data[0]->cedula = openssl_decrypt($data[0]->cedula, $this->cipher, $this->key, 0, $this->iv);
      // $data[0]->correo = openssl_decrypt($data[0]->correo, $this->cipher, $this->key, 0, $this->iv);

      parent::desconectarDB();

      return $data;
    } catch (\PDOException $error) {

      return $this->http_error(500, $error);
    }
  }

  public function getEditar($cedula, $name, $apellido, $email, $password, $tipoUsuario, $id)
  {

    if (!$this->validarString('nombre', $name)) {
      return $this->http_error(400, 'Nombre inválido.');
    }
    if (!$this->validarString('nombre', $apellido)) {
      return $this->http_error(400, 'Apellido invalido.');
    }
    if (!$this->validarString('documento', $cedula)) {
      return $this->http_error(400, 'Documento invalido.');
    }
    if (!$this->validarString('correo', $email)) {
      return $this->http_error(400, 'Correo invalido.');
    }
    if ($password !== "") {
      if (!$this->validarString('contraseña', $password)) {
        return $this->http_error(400, 'Contraseña invalida.');
      }
    }
    if (!$this->validarString('numero', $tipoUsuario)) {
      return $this->http_error(400, 'Tipo de Usuario invalido.');
    }
    if (!$this->validarString('documento', $id)) {
      return $this->http_error(400, 'Documento invalido.');
    }

    $this->cedula = $cedula;
    $this->name = $name;
    $this->apellido = $apellido;
    $this->email = $email;
    $this->password = $password;
    $this->rol = $tipoUsuario;
    $this->id = $id;

    return $this->editarUsuario();
  }

  private function editarUsuario()
  {

    try {
      parent::conectarDB();
      if ($this->password !== "") {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $new = $this->con->prepare("UPDATE `usuario` SET `password`=? WHERE `usuario`.`cedula` = ?");
        $new->bindValue(1, $this->password);
        $new->bindValue(2, $this->id);
        $new->execute();
      }
      $new = $this->con->prepare("UPDATE `usuario` SET `cedula`= ?,`nombre`= ?,`apellido`= ?,`correo`= ?,`rol`=? WHERE `usuario`.`cedula` = ?");
      $new->bindValue(1, $this->cedula);
      $new->bindValue(2, $this->name);
      $new->bindValue(3, $this->apellido);
      $new->bindValue(4, $this->email);
      $new->bindValue(5, $this->rol);
      $new->bindValue(6, $this->id);
      $new->execute();
      
      $resultado = ['resultado' => 'Editado'];
      $this->binnacle("Usuario", $_SESSION['cedula'], "Editó un usuario");
      parent::desconectarDB();
      return $resultado;
    } catch (\PDOException $error) {

      return $this->http_error(500, $error);
    }
  }

  public function getValidarC($cedula, $id)
  {
    $this->cedula = $cedula;
    $this->id = $id;

    return $this->validarC();
  }

  private function validarC()
  {
    try {
      if ($this->cedula == " ") {
        parent::conectarDB();
        $new = $this->con->prepare("SELECT `cedula` FROM `usuario` WHERE `cedula` = ?");
        $new->bindValue(1, $this->id);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();
        if (isset($data[0]['cedula'])) {
          $resultado = ['resultado' => 'Correcto', 'msg' => 'El documento está registrado.'];
        } else {
          $resultado = ['resultado' => 'Error', 'msg' => 'Documento no Registrado'];
        }
      } elseif ($this->id == " ") {
        parent::conectarDB();
        $new = $this->con->prepare("SELECT `cedula` FROM `usuario` WHERE `status`= 1 and `cedula` = ?");
        $new->bindValue(1, $this->cedula);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();
        if (isset($data[0]['cedula'])) {
          $resultado = ['resultado' => 'Error', 'msg' => 'El documento ya está registrado.'];
        } else {
          $resultado = ['resultado' => 'Correcto'];
        }
      } elseif ($this->id != " " && $this->cedula != " " && $this->cedula != $this->id) {
        parent::conectarDB();
        $new = $this->con->prepare("SELECT `cedula`, `status` FROM usuario WHERE cedula = ?");
        $new->bindValue(1, $this->cedula);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();
        if (isset($data[0]['status']) && $data[0]['status'] == 0) {
          $resultado = ['resultado' => 'Error', 'msg' => 'No Puede Ser Registrado'];
        } elseif (isset($data[0]['cedula']) && $data[0]['cedula'] == $this->cedula && $data[0]['status'] == 1) {
          $resultado = ['resultado' => 'Error', 'msg' => 'El documento ya esta Registrado'];
        } else {
          $resultado = ['resultado' => 'Correcto'];
        }
      } elseif ($this->cedula == $this->id) {

        $resultado = ['resultado' => 'Correcto'];
      }
      return $resultado;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function getValidarE($correo, $id)
  {
    $this->email = $correo;
    $this->id = $id;

    return $this->validarE();
  }

  private function validarE()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT `correo`, `status` FROM usuario WHERE cedula <> ? and correo = ?");
      $new->bindValue(1, $this->id);
      $new->bindValue(2, $this->email);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();
      if (isset($data[0]['correo']) && $data[0]['status'] === 1) {
        $resultado = ['resultado' => 'Error', 'msg' => 'El Correo ya esta Registrado'];
        return $resultado;
      }
      // elseif (isset($data[0]['correo']) && $data[0]['status'] === 0 ) {
      //     $resultado = ['resultado' => 'Error', 'msg' => 'El Correo no Puede Ser Registrado'];
      //     return $resultado;
      // } -------> Preguntar si dejo esta validacion <-------
      $resultado = ['resultado' => 'Correcto'];
      return $resultado;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }

  public function getPersonal($cedula)
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT cedula, nombres, apellidos, correo, status FROM personal WHERE cedula = ?");
      $new->bindValue(1, $cedula);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();
      if (!isset($data[0]['cedula']) || $data[0]['cedula'] == 0) return  $this->http_error(404, 'Registre Primero en Personal');
      return $data;
    } catch (\PDOException $error) {
      return $this->http_error(500, $error);
    }
  }
}
