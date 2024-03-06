<?php

  namespace modelo;
  use config\connect\DBConnect as DBConnect;


  class metodo extends DBConnect{
      
    	private $metodo;
      private $id;
      private $idedit;
      private $check;



     
     public function getAgregarMetodo($metodo){
       if(preg_match_all("/[$%&|<>0-9]/", $metodo) == true){
        $resultado = ['resultado'=> 'error de metodo', 'error'=>'metodo invalido'];
        echo json_encode($resultado);
        die();
      }

      
      $this->metodo = $metodo;

      $this->agregarMetodo(); 

    }

    private function agregarMetodo(){
     try{
      parent::conectarDB();
      $new = $this->con->prepare("INSERT INTO `tipo_pago`(`id_tipo_pago`, `des_tipo_pago`, `online`, `status`) VALUES (DEFAULT,?,0,1)");

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
    public function getMostrarMetodo($bitacora = false){

      try{
      parent::conectarDB();
       $new = $this->con->prepare("SELECT * FROM tipo_pago t WHERE t.status = 1");
       $new->execute();
       $data = $new->fetchAll(\PDO::FETCH_OBJ);
       echo json_encode($data);
      parent::desconectarDB();
      die();


     }catch(\PDOexection $error){

       return $error;

     }
    }

    public function getEliminarMetodo($id){
      $this->id = $id;

      $this->eliminarMetodo();
    }

    private function eliminarMetodo(){
     
     try{
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE `tipo_pago` SET `status` = '0' WHERE `id_tipo_pago` = ?");
      $new->bindValue(1,$this->id);
      $new->execute();
      $resultado = ['resultado' => 'Eliminado'];
      echo json_encode($resultado);
      parent::desconectarDB();
      die();
     } 
     catch (\PDOexception $error) {
      return $error;
     }

    }

    public function mostrarunicas($unicas){
      $this->id = $unicas;

      $this->unicas();

  }


   public function editarOnline($check , $id){

      if(preg_match_all("/^[0-9]{1,10}$/", $check) != 1){
        echo json_encode(['resultado' => 'Error de check','error' => 'check inválida.']);
        die();
      }

      if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
        echo json_encode(['resultado' => 'Error de id','error' => 'id inválida.']);
        die();
      }

      $this->id = $id;
      $this->check = $check;

      $this->editOnline();

   }

   private function editOnline(){
    try {
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE tipo_pago t SET t.online = ? WHERE t.id_tipo_pago = ? ");
      $new->bindValue(1, $this->check);
      $new->bindValue(2, $this->id);
      $new->execute();

      $resultado = ['resultado'=> 'check editado'];

      echo json_encode($resultado);

      parent::desconectarDB($resultado);
      die();
      
    } catch (PDOexception $e) {
      return $e;
    }

   }



    private function unicas(){
      try{
        parent::conectarDB();
        $new = $this->con->prepare("SELECT `id_tipo_pago`, `des_tipo_pago`, `status` FROM `tipo_pago` WHERE id_tipo_pago = ?");
        $new->bindValue(1, $this->id);
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);
        echo json_encode($data);
        parent::desconectarDB();
        die();
      }catch (\PDOexception $error) {
       return $error;
      }

  }
    public function getEditarMetodo($metodo, $unicas){
      if(preg_match_all("/[$%&|<>0-9]/", $metodo) == true){
              $resultado = ['resultado' => 'Error de metodo' , 'error' => 'metodo inválido.'];
              echo json_encode($resultado);
              die();
    }
    $this->metodo = $metodo;
    $this->idedit = $unicas;

        $this->editarMetodo(); 

  }
  private function editarMetodo(){
    try{
  parent::conectarDB();
  $new = $this->con->prepare("UPDATE `tipo_pago` SET `des_tipo_pago`= ? WHERE id_tipo_pago = ?");
  $new->bindValue(1, $this->metodo);
  $new->bindValue(2,$this->idedit);
  $new->execute();

        
  $resultado = ['resultado'=> 'Editado'];
  $this->binnacle("Metodo",$_SESSION['cedula'],"Editó un Valor de metodo.");
  echo json_encode($resultado);

  parent::desconectarDB();
  die();

  }catch(\PDOexception $error){
    return$error;
  }

}
}
?>