<?php 

  namespace modelo;
  use config\connect\DBConnect as DBConnect;

  class registro extends DBConnect{
    private $cedula;
    private $name;
    private $apellido;
    private $email;
    private $password;
    private $nivel;
    private $repass;

    public function __construct(){
      parent::__construct();
    } 

    public function getRegistrarSistema($cedula,$name,$apellido,$email,$password,$repass){

      if(preg_match_all("/^[a-zA-ZÀ-ÿ]{0,30}$/", $name) == false){
        $resultado = ['resultado' => 'Error de nombre' , 'error' => 'Nombre inválido.'];
        echo json_encode($resultado);
        die();
      }
      if(preg_match_all("/^[a-zA-ZÀ-ÿ]{0,30}$/", $apellido) == false){
        $resultado = ['resultado' => 'Error de apellido' , 'error' => 'Apellido inválido.'];
        echo json_encode($resultado);
        die();
      }
      if(preg_match_all("/^[0-9]{7,10}$/", $cedula) == false){
        $resultado = ['resultado' => 'Error de cedula' , 'error' => 'Cédula inválida.'];
        echo json_encode($resultado);
        die();
      }
      if(preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false){
        $resultado = ['resultado' => 'Error de email' , 'error' => 'Correo inválida.'];
        echo json_encode($resultado);
        die();
      }
      if(preg_match_all("/^[A-Za-z0-9 *?=&_!¡()@#]{3,30}$/", $password) == false) {
        $resultado = ['resultado' => 'Error de contraseña' , 'error' => 'Correo inválida.'];
        echo json_encode($resultado);
        die();
      }
      if($password != $repass) {
        $resultado = ['resultado' => 'Error de repass' , 'error' => 'Las contraseñas no coinciden.'];
        echo json_encode($resultado);
        die();
      }

      $this->name = $name;
      $this->apellido = $apellido;
      $this->cedula = $cedula;
      $this->email = $email;
      $this->password = $password;
      $this->repass = $repass;

      $validCedula = $this->validarCedula();
      if($validCedula['res'] != true) die(json_encode($validCedula));
      $validEmail = $this->validarEmail();
      if($validEmail['res'] != true) die(json_encode($validEmail));

      $this->registraSistema();
    }

    private function registraSistema(){

      try{
        $this->key = parent::KEY();
				$this->iv = parent::IV();
				$this->cipher = parent::CIPHER();
				$this->conectarDB();

        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $new = $this->con->prepare("INSERT INTO `usuario`(`cedula`, `nombre`, `apellido`, `correo`, `password`, `rol`, `status`) VALUES (?,?,?,?,?,4,1)");
        $new->bindValue(1, $this->cedula);
        $new->bindValue(2, $this->name); 
        $new->bindValue(3, $this->apellido);
        $new->bindValue(4, $this->email);
        $new->bindValue(5, $this->password);
        $new->execute();
        parent::desconectarDB();
        $resultado = ['resultado' => 'Registrado correctamente.'];
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
        $this->key = parent::KEY();
				$this->iv = parent::IV();
				$this->cipher = parent::CIPHER();
				$this->conectarDB();

				$this->cedula = openssl_encrypt($this->cedula, $this->cipher, $this->key, 0, $this->iv);
        $new = $this->con->prepare("SELECT `cedula` FROM `usuario` WHERE `status` = 1 and `cedula` = ?");
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
        $this->key = parent::KEY();
				$this->iv = parent::IV();
				$this->cipher = parent::CIPHER();
				$this->conectarDB();

        $this->email = openssl_encrypt($this->email, $this->cipher, $this->key, 0, $this->iv);
        $new = $this->con->prepare("SELECT `correo` FROM `usuario` WHERE `status` = 1 and `correo` = ?");
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