<?php

  namespace modelo;
  use config\connect\DBConnect as DBConnect;


  class metodo extends DBConnect{
      
    	private $metodo;
      private $id;
      private $idedit;


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
      if(preg_match_all("/[$%&|<>0-9]/", $metodo) == true){
          return['resultado'=> 'error de metodo', 'error'=>'metodo invalido'];         
      }

      $this->id = ($id === 'false') ? false : $id;
      $this->metodo = $metodo;

      return $this->validMetodo();

     }

     private function validMetodo(){
      try {
        $this->conectarDB();
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

        $this->desconectarDB();
        return $resultado;
        
      } catch (\PDOException $e) {
        return $e;
      }
     }

      public function getAgregarMetodo($metodo){
        if(preg_match_all("/[$%&|<>0-9]/", $metodo) == true){
          return['resultado'=> 'error de metodo', 'error'=>'metodo invalido'];         
        }
       
        $this->metodo = $metodo;

        return $this->agregarMetodo(); 

      }

      private function agregarMetodo(){
       try{
        parent::conectarDB();
        $pk = $this->uniqueID();
        $new = $this->con->prepare("INSERT INTO `forma_pago`(`id_forma_pago`, `tipo_pago`, `status`) VALUES (5,?,1)");

        $new->bindValue(1 , $this->metodo);
        $new->execute();
        $data = $new->fetchAll();
        
        $resultado = ["resultado" => "registrado correctamente"];
        echo json_encode($resultado);
        parent::desconectarDB();
        die();

        
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
    if(preg_match_all("/[$%&|<>0-9]/", $metodo) == true){
      return['resultado' => 'Error de metodo' , 'error' => 'metodo inválido.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
      return ['resultado' => 'Error de id','error' => 'id inválida.'];
    }

    $this->metodo = $metodo;
    $this->idedit = $id;

    return $this->editarMetodo(); 

  }
  private function editarMetodo(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE forma_pago fp SET fp.tipo_pago = ? WHERE fp.status = 1 AND fp.id_forma_pago = ?");
      $new->bindValue(1, $this->metodo);
      $new->bindValue(2,$this->idedit);
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