<?php 

  namespace modelo;
  use config\connect\DBConnect as DBConnect;

  class registro extends DBConnect{
    private $cedula;
    private $nombre;
    private $apellido;
    private $email;
    private $password;
    private $nivel;
    private $repass;

    public function __construct(){
      parent::__construct();
    } 

    public function getRegistrarSistema($cedula,$nombre,$apellido,$email,$password){

      if(preg_match_all("/^[a-zA-ZÀ-ÿ]{0,30}$/", $nombre) == false){
        die(json_encode(['resultado' => 'Error de nombre' , 'error' => 'Nombre inválido.']));
      }
      if(preg_match_all("/^[a-zA-ZÀ-ÿ]{0,30}$/", $apellido) == false){
        die(json_encode(['resultado' => 'Error de apellido' , 'error' => 'Apellido inválido.']));
      }
      if(preg_match_all("/^[0-9]{7,10}$/", $cedula) == false){
        die(json_encode(['resultado' => 'Error de cedula' , 'error' => 'Cédula inválida.']));
      }
      if(preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false){
        die(json_encode(['resultado' => 'Error de email' , 'error' => 'Correo inválida.']));
      }
      if(preg_match_all("/^[A-Za-z0-9 *?=&_!¡()@#]{3,30}$/", $password) == false) {
        die(json_encode(['resultado' => 'Error de contraseña' , 'error' => 'Correo inválida.']));
      }

      $this->nombre = $nombre;
      $this->apellido = $apellido;
      $this->cedula = $cedula;
      $this->email = $email;
      $this->password = $password;

      $validCedula = $this->validarCedula();
      if($validCedula['res'] != true) die(json_encode($validCedula));
      $validEmail = $this->validarEmail();
      if($validEmail['res'] != true) die(json_encode($validEmail));

      $this->registraSistema();
    }

    private function registraSistema(){

      try{
				$this->conectarDB();

        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $new = $this->con->prepare("INSERT INTO usuario (cedula, nombre, apellido, correo, password, rol, status) VALUES (?,?,?,?,?,'1','1');");
        $new->bindValue(1, $this->cedula);
        $new->bindValue(2, $this->nombre); 
        $new->bindValue(3, $this->apellido);
        $new->bindValue(4, $this->email);
        $new->bindValue(5, $this->password);
        if($new->execute()){
          $resultado = ['resultado' => 'Registrado correctamente.'];

          $_SESSION['cedula'] = $this->cedula;
          $_SESSION['nombre'] = $this->nombre;
          $_SESSION['apellido'] = $this->apellido; 
          $_SESSION['correo'] = $this->email;
          $_SESSION['nivel'] = 1;
          $_SESSION['puesto'] = "Cliente";
          $_SESSION['fotoPerfil'] = 'assets/img/profile_photo.jpg';

        }else{
          $resultado = ['resultado' => 'error', 'msg' => 'Ha ocurrido un error al registrar.'];
        }
        parent::desconectarDB();
        die(json_encode($resultado));

      }catch(exection $error){
        die($error);
      }

    }

    public function getValidarCedula($cedula){
      if(preg_match_all("/^[0-9]{7,10}$/", $cedula) == false){
        $resultado = ['resultado' => 'Error de cedula' , 'error' => 'Cédula inválida.'];
        echo json_encode($resultado);
        die();
      }
      $this->cedula = $cedula;

      return $this->validarCedula();
    }

    private function validarCedula(){
      try{        
				$this->conectarDB();

        $new = $this->con->prepare("SELECT cedula FROM usuario WHERE status = 1 and cedula = ?");
        $new->bindValue(1, $this->cedula);
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_ASSOC);
        parent::desconectarDB();

        $resultado;
        if(isset($data[0]['cedula'])){
          $resultado = ['resultado' => 'Error de cedula' , 'error' => 'La cédula ya está registrada.', 'res' => false];
        }else{
          $resultado = ['resultado' => 'ok' , 'msg' => 'La cédula es válida.', 'res' => true];
        }
        return $resultado;

      }catch(\PDOException $error){
        die($error);
      }
    }

    public function getValidarEmail($email){
      if( preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false){
        $resultado = ['resultado' => 'Error de email' , 'error' => 'Correo inválido.'];
        echo json_encode($resultado);
        die();
      }
      $this->email = $email;

      return $this->validarEmail();
    }

    private function validarEmail(){
      try{
				$this->conectarDB();

        $new = $this->con->prepare("SELECT correo FROM usuario WHERE status = 1 and correo = ?");
        $new->bindValue(1, $this->email);
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_ASSOC);
        parent::desconectarDB();
        $resultado;
        if(isset($data[0]['correo'])){
          $resultado = ['resultado' => 'Error de email' , 'error' => 'El email ya está registrado.', 'res' => false];
        }else{
          $resultado = ['resultado' => 'ok' , 'msg' => 'El email es válido.', 'res' => true];
        }
        return $resultado;

      }catch(\PDOException $error){
        return $error;
      }
    }

  }

?>