<?php

  namespace modelo;
  use config\connect\DBConnect as DBConnect;

  class metodo extends DBConnect{
      
    	private $metodo;
      private $id;

      public function getMostrarMetodo($bitacora = false){

        try{
          parent::conectarDB();
          $new = $this->con->prepare("SELECT fp.id_forma_pago , fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1");
          $new->execute();
          $data = $new->fetchAll(\PDO::FETCH_OBJ);
          parent::desconectarDB();
          return $data;
          
        }catch(\PDOexection $error){

         return $error;

       }
     }

     public function validarMetodo($metodo , $id){
      if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){3,30}$/', $metodo) != 1){
          return['resultado'=> 'error de metodo', 'error'=>'metodo invalido'];         
      }

      $this->id = ($id === 'false') ? false : $id;
      $this->metodo = $metodo;

      return $this->validMetodo();

     }

     private function validMetodo(){
      try {
        parent::conectarDB();
        if($this->id === false) {
          $new = $this->con->prepare('SELECT fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1 AND fp.tipo_pago = ?');
          $new->bindValue(1, $this->metodo);
        }else{
          $new = $this->con->prepare('SELECT fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1 AND fp.tipo_pago = ? AND fp.id_forma_pago != ?');
          $new->bindValue(1, $this->metodo);
          $new->bindValue(2, $this->id);
        }

        $new->execute();
        $data = $new->fetchAll();

        if(isset($data[0]['tipo_pago'])) {
          $resultado = ['resultado' => 'error', 'msg' => 'El metodo ya está registrado.', 'res' => false];
        }else{
          $resultado = ['resultado' => 'metodo valido', 'res' => true];
        }

        parent::desconectarDB();
        return $resultado;
        
      } catch (\PDOException $e) {
        return $e;
      }
     }

     public function validarSelect($id){
      if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
        return ['resultado' => 'Error de id','error' => 'id inválida.'];
      }
      
      $this->id = $id;

      return $this->validSelect();

     }

     private function validSelect(){
      try {
        parent::conectarDB();
        $new = $this->con->prepare('SELECT fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1 AND fp.id_forma_pago = ?');
        $new->bindValue(1,  $this->id);
        $new->execute();
        $data = $new->fetchAll();

        parent::desconectarDB();

        if(isset($data[0]["tipo_pago"])){
          return['resultado' => 'Si existe este metodo.'];
        }else{
          return['resultado' => 'Error de metodo'];
        }
      } catch (\PDOException $e) {
        return $e;
      }
     }

      public function getAgregarMetodo($metodo){
        if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){3,30}$/', $metodo) != 1){
          return['resultado'=> 'error de metodo', 'error'=>'metodo invalido'];         
        }
       
        $this->metodo = $metodo;

        $this->id = false;
        $validarMetodo = $this->validMetodo();
        if($validarMetodo['res'] === false){ return ['resultado' => 'error', 'msg' => 'El metodo ya está registrado.'] ;}

        return $this->agregarMetodo(); 

      }

      private function agregarMetodo(){
       try{
        parent::conectarDB();
        do{
        $pk = $this->uniqueNumericID();
        $check = $this->con->prepare("SELECT COUNT(*) FROM `forma_pago` WHERE `id_forma_pago` = ?");
        $check->bindValue(1, $pk);
        $check->execute();
        $count = $check->fetchColumn();
        }while($count > 0);
        
        $new = $this->con->prepare("INSERT INTO `forma_pago`(`id_forma_pago`, `tipo_pago`, `status`) VALUES (?,?,1)");

        $new->bindValue(1 , $pk);
        $new->bindValue(2 , $this->metodo);
        $new->execute();
        $data = $new->fetchAll();
        
        $resultado = ["resultado" => "registrado correctamente"];
        parent::desconectarDB();
        return $resultado;

        
      }catch(\PDOexection $error){
       return $error;
     }

   }


    public function mostrarEdit($id){

      if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
        return ['resultado' => 'Error de id','error' => 'id inválida.'];
      }
      $this->id = $id;

      return $this->selectEdit();

  }


   private function selectEdit(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("SELECT fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1 AND fp.id_forma_pago = ?");
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;
    }catch (\PDOexception $error) {
     return $error;
   }

 }

  public function getEditarMetodo($metodo, $id){
    if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){3,30}$/', $metodo) != 1){
      return ['resultado' => 'Error de metodo' , 'error' => 'metodo inválido.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
      return ['resultado' => 'Error de id','error' => 'id inválida.'];
    }

    $this->metodo = $metodo;
    $this->id = $id;

     $validarMetodo = $this->validMetodo();
     if($validarMetodo['res'] === false){ return ['resultado' => 'error', 'msg' => 'El metodo ya está registrado.'] ;}

    return $this->editarMetodo(); 

  }
  private function editarMetodo(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE forma_pago fp SET fp.tipo_pago = ? WHERE fp.status = 1 AND fp.id_forma_pago = ?");
      $new->bindValue(1, $this->metodo);
      $new->bindValue(2,$this->id);
      $new->execute();

      $resultado = ['resultado'=> 'Editado'];
      $this->binnacle("Metodo",$_SESSION['cedula'],"Editó un Valor de metodo.");

      parent::desconectarDB();
      return $resultado;

    }catch(\PDOexception $error){
      return$error;
    }

  }

    public function getEliminarMetodo($id){
      if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
      return ['resultado' => 'Error de id','error' => 'id inválida.'];
      }
      $this->id = $id;

      return $this->eliminarMetodo();
    }

    private function eliminarMetodo(){

     try{
      parent::conectarDB();

      $new = $this->con->prepare("UPDATE forma_pago fp SET fp.status = 0 WHERE fp.id_forma_pago = ?");
      $new->bindValue(1,$this->id);
      $new->execute();
      $resultado = ['resultado' => 'Eliminado'];
      parent::desconectarDB();

      return $resultado;
    } 
    catch (\PDOexception $error) {
      return $error;
    }

  }

}
?>