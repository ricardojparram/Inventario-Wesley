<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;

class usuarios extends DBConnect
{

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

    if (preg_match_all("/^[a-zA-ZÀ-ÿ]{0,30}$/", $name) == false) {
      $resultado = ['resultado' => 'Error de nombre', 'error' => 'Nombre invalido.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^[a-zA-ZÀ-ÿ]{0,30}$/", $apellido) == false) {
      $resultado = ['resultado' => 'Error de apellido', 'error' => 'Apellido invalido.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^[0-9]{7,10}$/", $cedula) == false) {
      $resultado = ['resultado' => 'Error de cedula', 'error' => 'Cédula invalida.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false) {
      $resultado = ['resultado' => 'Error de email', 'error' => 'Correo invalida.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^[A-Za-z0-9 *?=&_!¡()@#]{3,30}$/", $password) == false) {
      $resultado = ['resultado' => 'Error de contraseña', 'error' => 'Correo invalida.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^[0-9]{1,2}$/", $tipoUsuario) == false) {
      $resultado = ['resultado' => 'Error de rol', 'error' => 'rol invalido.'];
      echo json_encode($resultado);
      die();
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
      $this->key = parent::KEY();
      $this->iv = parent::IV();
      $this->cipher = parent::CIPHER();

      $this->cedula = openssl_encrypt($this->cedula, $this->cipher, $this->key, 0, $this->iv);
      $this->email = openssl_encrypt($this->email, $this->cipher, $this->key, 0, $this->iv);

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
        echo json_encode($resultado);
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
        echo json_encode($resultado);
        $this->binnacle("Usuario", $_SESSION['cedula'], "Registró un usuario");
        parent::desconectarDB();

      } else {
        $resultado = ['resultado' => 'No se registro', 'error' => 'error desconocido.'];
        echo json_encode($resultado);

      }

      die();


    } catch (exection $error) {
      die($error);
    }

  }

  public function getMostrarUsuario($bitacora = false)
  {

    try {
      parent::conectarDB();
      $this->key = parent::KEY();
      $this->iv = parent::IV();
      $this->cipher = parent::CIPHER();

      $query = "SELECT u.cedula as cedulaE, u.cedula, u.nombre, u.apellido, u.correo, u.rol FROM usuario u WHERE u.status = 1";

      $new = $this->con->prepare($query);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      foreach ($data as $item) {
        $item->cedula = openssl_decrypt($item->cedula, $this->cipher, $this->key, 0, $this->iv);
        $item->correo = openssl_decrypt($item->correo, $this->cipher, $this->key, 0, $this->iv);
      }
      if ($bitacora)
        $this->binnacle("Usuario", $_SESSION['cedula'], "Consultó listado.");
      echo json_encode($data);

      parent::desconectarDB();
      die();

    } catch (\PDOexection $error) {

      return $error;

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
    } catch (\PDOexection $error) {

      return $error;

    }
  }

  public function getEliminar($cedula)
  {
    $this->cedula = $cedula;

    $this->eliminarUsuario();
  }

  private function eliminarUsuario()
  {
    try {

      parent::conectarDB();
      $new = $this->con->prepare("UPDATE `usuario` SET `status` = '0' WHERE `usuario`.`cedula` = ?"); //"DELETE FROM `usuario` WHERE `usuario`.`cedula` = ?"
      $new->bindValue(1, $this->cedula);
      $new->execute();
      $resultado = ['resultado' => 'Eliminado'];
      echo json_encode($resultado);
      $this->binnacle("Usuario", $_SESSION['cedula'], "Eliminó un usuario");
      parent::desconectarDB();
      die();

    } catch (\PDOexection $error) {
      return $error;
    }
  }

  public function getUnico($cedula)
  {
    $this->cedula = $cedula;

    $this->seleccionarUnico();
  }

  private function seleccionarUnico()
  {
    try {
      parent::conectarDB();
      $this->key = parent::KEY();
      $this->iv = parent::IV();
      $this->cipher = parent::CIPHER();

      $new = $this->con->prepare("SELECT cedula, nombre, apellido, correo, rol FROM `usuario` WHERE `usuario`.`cedula` = ?");
      $new->bindValue(1, $this->cedula);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      $data[0]->cedula = openssl_decrypt($data[0]->cedula, $this->cipher, $this->key, 0, $this->iv);
      $data[0]->correo = openssl_decrypt($data[0]->correo, $this->cipher, $this->key, 0, $this->iv);
      echo json_encode($data);
      parent::desconectarDB();

      die();
    } catch (\PDOexection $error) {

      return $error;

    }
  }

  public function getEditar($cedula, $name, $apellido, $email, $password, $tipoUsuario, $id)
  {

    if (preg_match_all("/^[a-zA-Z]{0,30}$/", $name) == false) {
      $resultado = ['resultado' => 'Error de nombre', 'error' => 'Nombre invalido.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^[a-zA-Z]{0,30}$/", $apellido) == false) {
      $resultado = ['resultado' => 'Error de apellido', 'error' => 'Apellido invalido.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^[0-9]{7,10}$/", $cedula) == false) {
      $resultado = ['resultado' => 'Error de cedula', 'error' => 'Cédula invalida.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false) {
      $resultado = ['resultado' => 'Error de email', 'error' => 'Correo invalida.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^[A-Za-z0-9 *?=&_!¡()@#]{3,30}$/", $password) == false) {
      $resultado = ['resultado' => 'Error de contraseña', 'error' => 'Contraseña invalida.'];
      echo json_encode($resultado);
      die();
    }
    if (preg_match_all("/^[0-9]{1,2}$/", $tipoUsuario) == false) {
      $resultado = ['resultado' => 'Error de rol', 'error' => 'rol invalido.'];
      echo json_encode($resultado);
      die();
    }

    $this->cedula = $cedula;
    $this->name = $name;
    $this->apellido = $apellido;
    $this->email = $email;
    $this->password = $password;
    $this->rol = $tipoUsuario;
    $this->id = $id;

    $this->editarUsuario();
  }

  private function editarUsuario()
  {

    try {
      $this->key = parent::KEY();
      $this->iv = parent::IV();
      $this->cipher = parent::CIPHER();

      $this->cedula = openssl_encrypt($this->cedula, $this->cipher, $this->key, 0, $this->iv);
      $this->email = openssl_encrypt($this->email, $this->cipher, $this->key, 0, $this->iv);

      $this->password = password_hash($this->password, PASSWORD_BCRYPT);
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE `usuario` SET `cedula`= ?,`nombre`= ?,`apellido`= ?,`correo`= ?,`password`=?,`rol`=? WHERE `usuario`.`cedula` = ?");
      $new->bindValue(1, $this->cedula);
      $new->bindValue(2, $this->name);
      $new->bindValue(3, $this->apellido);
      $new->bindValue(4, $this->email);
      $new->bindValue(5, $this->password);
      $new->bindValue(6, $this->rol);
      $new->bindValue(7, $this->id);
      $new->execute();
      $data = $new->fetchAll();
      $resultado = ['resultado' => 'Editado'];
      echo json_encode($resultado);
      $this->binnacle("Usuario", $_SESSION['cedula'], "Editó un usuario");
      parent::desconectarDB();
      die();
    } catch (\PDOexection $error) {

      return $error;

    }
  }

  public function getValidarC($cedula, $id)
  {
    $this->cedula = $cedula;
    $this->id = $id;

    $this->validarC();
  }

  private function validarC()
  {
    try {
      $this->key = parent::KEY();
      $this->iv = parent::IV();
      $this->cipher = parent::CIPHER();
      if ($this->cedula == " ") {
        parent::conectarDB();
        $new = $this->con->prepare("SELECT `cedula` FROM `usuario` WHERE `cedula` = ?");
        $new->bindValue(1, $this->id);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();
        if (isset($data[0]['cedula'])) {
          $resultado = ['resultado' => 'Correcto', 'msj' => 'La cédula está registrada.'];

          echo json_encode($resultado);
          die();
        } else {
          $resultado = ['resultado' => 'Error', 'msj' => 'Cedula no Registrada'];
          echo json_encode($resultado);
          die();
        }
      } elseif ($this->id == " ") {
        $this->cedula = openssl_encrypt($this->cedula, $this->cipher, $this->key, 0, $this->iv);
        parent::conectarDB();
        $new = $this->con->prepare("SELECT `cedula` FROM `usuario` WHERE `status`= 1 and `cedula` = ?");
        $new->bindValue(1, $this->cedula);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();
        if (isset($data[0]['cedula'])) {
          $resultado = ['resultado' => 'Error', 'msj' => 'La cédula ya está registrada.'];
          echo json_encode($resultado);
          die();
        } else {
          $resultado = ['resultado' => 'Correcto'];
          echo json_encode($resultado);
          die();
        }
      } elseif ($this->id != " " && $this->cedula != " " && openssl_encrypt($this->cedula, $this->cipher, $this->key, 0, $this->iv) != $this->id) {
        $this->cedula = openssl_encrypt($this->cedula, $this->cipher, $this->key, 0, $this->iv);
        parent::conectarDB();
        $new = $this->con->prepare("SELECT `cedula`, `status` FROM usuario WHERE cedula = ?");
        $new->bindValue(1, $this->cedula);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();
        if (isset($data[0]['status']) && $data[0]['status'] == 0) {
          $resultado = ['resultado' => 'Error', 'msj' => 'No Puede Ser Registrada'];
          echo json_encode($resultado);
          die();
        } elseif (isset($data[0]['cedula']) && $data[0]['cedula'] == $this->cedula && $data[0]['status'] == 1) {
          $resultado = ['resultado' => 'Error', 'msj' => 'La Cedula ya esta Registrada'];
          echo json_encode($resultado);
          die();
        } else {
          $resultado = ['resultado' => 'Correcto'];
          echo json_encode($resultado);
          die();
        }
      } elseif (openssl_encrypt($this->cedula, $this->cipher, $this->key, 0, $this->iv) == $this->id) {
        $resultado = ['resultado' => 'Correcto'];
        echo json_encode($resultado);
        die();
      }
      $resultado = ['resultado' => 'Ninguno'];
      echo json_encode($resultado);
      die();


    } catch (\PDOException $error) {
      die($error);
    }
  }

  public function getValidarE($correo, $id)
  {
    $this->correo = $correo;
    $this->id = $id;

    $this->validarE();
  }

  private function validarE()
  {
    try {
      $this->key = parent::KEY();
      $this->iv = parent::IV();
      $this->cipher = parent::CIPHER();

      parent::conectarDB();
      $this->correo = openssl_encrypt($this->correo, $this->cipher, $this->key, 0, $this->iv);
      $new = $this->con->prepare("SELECT `correo`, `status` FROM usuario WHERE cedula <> ? and correo = ?");
      $new->bindValue(1, $this->id);
      $new->bindValue(2, $this->correo);
      $new->execute();
      $data = $new->fetchAll();
      // echo json_encode($data);
      // die();
      parent::desconectarDB();
      if (isset($data[0]['correo']) && $data[0]['status'] === 1) {
        $resultado = ['resultado' => 'Error', 'msj' => 'El Correo ya esta Registrado'];
        echo json_encode($resultado);
        die();
      }
      // elseif (isset($data[0]['correo']) && $data[0]['status'] === 0 ) {
      //     $resultado = ['resultado' => 'Error', 'msj' => 'El Correo no Puede Ser Registrado'];
      //     echo json_encode($resultado);
      //     die();
      // } -------> Preguntar si dejo esta validacion <-------
      $resultado = ['resultado' => 'Correcto'];
      echo json_encode($resultado);
      die();

    } catch (\PDOException $e) {
      die($e);
    }
  }


}

?>