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

    if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $name) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'Nombre invalido.'];
      return $resultado;
    }
    if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $apellido) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'Apellido invalido.'];
      return $resultado;
    }
    if (preg_match_all("/^[VE]-[A-Z0-9]{7,12}$/", $cedula) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'Documento invalido.'];
      return $resultado;
    }
    if (preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'Correo invalida.'];
      return $resultado;
    }
    if (preg_match_all("/^[A-Za-z\d$@\/_.#-]{3,30}$/", $password) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'Correo invalida.'];
      return $resultado;
    }
    if (preg_match_all("/^[0-9]{1,2}$/", $tipoUsuario) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'rol invalido.'];
      return $resultado;
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
        $resultado = ['resultado' => 'Error', 'error' => 'error desconocido.'];
      }
      return $resultado;
      



    } catch (exection $error) {
      return $error;
    }

  }

  public function getMostrarUsuario($bitacora = false)
  {

    try {
      parent::conectarDB();
      $query = "SELECT u.cedula, u.nombre, u.apellido, u.correo, r.nombre as rol FROM usuario u INNER JOIN rol r ON u.rol = r.id_rol WHERE u.status = 1";

      $new = $this->con->prepare($query);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      
      if ($bitacora)
        $this->binnacle("Usuario", $_SESSION['cedula'], "Consultó listado Usuarios.");
      
      parent::desconectarDB();
      return $data;
      

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

    return $this->eliminarUsuario();
  }

  private function eliminarUsuario()
  {
    try {
      if ($this->cedula == $_SESSION['cedula']) {
        $resultado = ['resultado' => 'Error', 'msj' => 'No se puede Eliminar su Propia Cuenta'];
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

    } catch (\PDOexection $error) {
      return $error;
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
    } catch (\PDOexection $error) {

      return $error;

    }
  }

  public function getEditar($cedula, $name, $apellido, $email, $password, $tipoUsuario, $id)
  {

    if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $name) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'Nombre invalido.'];
      return $resultado;
    }
    if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $apellido) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'Apellido invalido.'];
      return $resultado;
    }
    if (preg_match_all("/^[VEJ]-[A-Z0-9]{7,12}$/", $cedula) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'Documento invalido.'];
      return $resultado;
    }
    if (preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'Correo invalida.'];
      return $resultado;
    }
    if ($password != "") {
      if (preg_match_all("/^[A-Za-z\d$@\/_.#-]{3,30}$/", $password) == false) {
        $resultado = ['resultado' => 'Error', 'error' => 'Contraseña invalida.'];
        return $resultado;
      }
    }
    if (preg_match_all("/^[0-9]{1,2}$/", $tipoUsuario) == false) {
      $resultado = ['resultado' => 'Error', 'error' => 'rol invalido.'];
      return $resultado;
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
      if ($this->password != "") {
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
    } catch (\PDOexection $error) {

      return $error;

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
          $resultado = ['resultado' => 'Correcto', 'msj' => 'El documento está registrado.'];

          
        } else {
          $resultado = ['resultado' => 'Error', 'msj' => 'Documento no Registrado'];
          
        }
      } elseif ($this->id == " ") {
        parent::conectarDB();
        $new = $this->con->prepare("SELECT `cedula` FROM `usuario` WHERE `status`= 1 and `cedula` = ?");
        $new->bindValue(1, $this->cedula);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();
        if (isset($data[0]['cedula'])) {
          $resultado = ['resultado' => 'Error', 'msj' => 'El documento ya está registrado.'];
          
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
          $resultado = ['resultado' => 'Error', 'msj' => 'No Puede Ser Registrado'];
          
        } elseif (isset($data[0]['cedula']) && $data[0]['cedula'] == $this->cedula && $data[0]['status'] == 1) {
          $resultado = ['resultado' => 'Error', 'msj' => 'El documento ya esta Registrado'];
          
        } else {
          $resultado = ['resultado' => 'Correcto'];
          
        }
      } elseif ($this->cedula == $this->id) {
        
        $resultado = ['resultado' => 'Correcto'];
        
      } 
      return $resultado;


    } catch (\PDOException $error) {
      return $error;
    }
  }

  public function getValidarE($correo, $id)
  {
    $this->correo = $correo;
    $this->id = $id;

    return $this->validarE();
  }

  private function validarE()
  {
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT `correo`, `status` FROM usuario WHERE cedula <> ? and correo = ?");
      $new->bindValue(1, $this->id);
      $new->bindValue(2, $this->correo);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();
      if (isset($data[0]['correo']) && $data[0]['status'] === 1) {
        $resultado = ['resultado' => 'Error', 'msj' => 'El Correo ya esta Registrado'];
        return $resultado;
      }
      // elseif (isset($data[0]['correo']) && $data[0]['status'] === 0 ) {
      //     $resultado = ['resultado' => 'Error', 'msj' => 'El Correo no Puede Ser Registrado'];
      //     return $resultado;
      // } -------> Preguntar si dejo esta validacion <-------
      $resultado = ['resultado' => 'Correcto'];
      return $resultado;

    } catch (\PDOException $e) {
      return $e;
    }
  }


}

?>