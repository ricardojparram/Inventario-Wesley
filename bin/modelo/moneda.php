<?php

  namespace modelo;
  use config\connect\DBConnect as DBConnect;


class moneda extends DBConnect{
	 
	private $moneda;
	private $alcambio;
  private $id;
  private $idedit;



   
   public function getAgregarCambio($alcambio,$tipo){

   	 if(preg_match_all("/^[0-9]{1,30}$/", $tipo) != 1){
            $resultado = ['resultado' => 'Error' , 'error' => 'moneda inválido.'];
            return $resultado;
        }
     if(preg_match_all("/^([0-9]+\.+[0-9]|[0-9])+$/", $alcambio) != 1){
          $resultado = ['resultado' => 'Error' , 'error' => 'valor inválido.'];
          return $resultado;
        }
    
    $this->moneda = $tipo;
    $this->alcambio = $alcambio;

     return $this->agregarCambio(); 


   }
   
     private function agregarCambio(){
     try{
      parent::conectarDB();
      $new = $this->con->prepare("INSERT INTO `cambio`(`id_cambio`, `cambio`, `fecha`, `moneda`, `status`) VALUES (DEFAULT,?,DEFAULT,?,1)");
      $new->bindValue(1 , $this->alcambio);
      $new->bindValue(2 , $this->moneda);
      $new->execute();
      $idC = $this->con->lastInsertId();
      $resultado = ['resultado' => 'Registado con exito', 'idC' => $idC];
      $this->binnacle("Moneda",$_SESSION['cedula'],"Registró un Valor de Moneda.");
      parent::desconectarDB();
      return $resultado;

     }catch(\PDOexection $error){
    	return $error;
      }

   }
   public function getMostrarCambio($nombreMon){
   	try{
        parent::conectarDB();
       $new = $this->con->prepare("SELECT m.nombre, format(tabla_cambio.cambio,2,'de_DE') as cambio, tabla_cambio.fecha, tabla_cambio.id_cambio FROM moneda m LEFT JOIN ( SELECT c.cambio, c.fecha, c.id_cambio, c.moneda FROM cambio c WHERE c.status = 1 ORDER BY c.fecha ASC LIMIT 99999 ) as tabla_cambio ON tabla_cambio.moneda = m.id_moneda WHERE m.status = 1 AND m.id_moneda = ?");
      $new->bindValue(1 , $nombreMon);
       $new->execute();
       $data = $new->fetchAll();
       parent::desconectarDB();
       return $data;

     }catch(\PDOexection $error){

       return $error;

     }
  }

  public function SelectM(){
    try{
      parent::conectarDB();
       $new = $this->con->prepare("SELECT * FROM `moneda` WHERE status = 1");
       $new->execute();
       $data = $new->fetchAll();
       parent::desconectarDB();
       return $data;

     }catch(\PDOexection $error){

       return $error;

     }
  }

  public function getEliminarCambio($id){
   $this->id = $id;

   return $this->eliminarCambio();
  }

  private function eliminarCambio(){

    try {
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE `cambio` SET `status` = '0' WHERE `id_cambio` = ? and status = 1");
      $new->bindValue(1, $this->id);
      $new->execute();
      $resultado = ['resultado' => 'Eliminado'];
      $this->binnacle("Moneda",$_SESSION['cedula'],"Eliminó un Valor de Moneda.");
      parent::desconectarDB();
      return $resultado;
    }
    catch (\PDOException $error) {
      return $error;
    }
  }


  public function mostrarUnico($unico){
    $this->id = $unico;

    return $this->unico();
  }

  private function unico(){
    try {
      parent::conectarDB();
      $new = $this->con->prepare("SELECT * FROM `cambio` WHERE id_cambio = ?");
      $new->bindValue(1, $this->id);
      $new->execute();
      $datas = $new->fetchAll();
      
      parent::desconectarDB();
      return $datas;
      
    } catch (\PDOException $error) {
      return $error;
    }
  }

  public function getEditarCambio($alcambio,$moneda, $unico){

     if(preg_match_all("/^[0-9]{1,30}$/", $moneda) != 1){
            $resultado = ['resultado' => 'Error' , 'error' => 'Moneda inválido.'];
            return $resultado;
        }
     if(preg_match_all("/^([0-9]+\.+[0-9]|[0-9])+$/", $alcambio) != 1){
      $resultado = ['resultado' => 'Error' , 'error' => 'Cambio inválido.'];
      return $resultado;
        }
    
    $this->moneda = $moneda;
    $this->alcambio = $alcambio;
    $this->idedit = $unico;
    
    date_default_timezone_set("america/caracas");
    $this->fechaActual = date("Y-m-d G:i:s");

     return $this->editarCambio(); 


   }
   
     private function editarCambio(){
     try{
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE `cambio` SET `cambio`= ?,`moneda`= ?, `fecha`= ? WHERE id_cambio = ? and status = 1");
      $new->bindValue(1, $this->alcambio);
      $new->bindValue(2, $this->moneda);
      $new->bindValue(3, $this->fechaActual);
      $new->bindValue(4, $this->idedit);
      $new->execute();
      $data = $new->fetchAll();
      $resultado = ['resultado' => 'Editado'];
      $this->binnacle("Moneda",$_SESSION['cedula'],"Editó un Valor de Moneda.");
      parent::desconectarDB();
      return $resultado;    
      

     }catch(\PDOexection $error){
      return $error;
      }

   }

   public function getMoneda($bitacora = false){
      try{
        parent::conectarDB();
       $new = $this->con->prepare("SELECT m.id_moneda, m.nombre, tabla_cambio.cambio, tabla_cambio.fecha FROM moneda m 
                                    LEFT JOIN (
                                        SELECT c.cambio, c.fecha, c.moneda FROM cambio c 
                                        WHERE c.status = 1
                                        ORDER BY c.fecha ASC LIMIT 99999
                                    ) as tabla_cambio ON tabla_cambio.moneda = m.id_moneda
                                    WHERE m.status = 1
                                    GROUP BY m.id_moneda;");
       $new->execute();
       $data = $new->fetchAll();
       
       if($bitacora) $this->binnacle("Moneda",$_SESSION['cedula'],"Consultó listado.");
       parent::desconectarDB();
       return $data;

     }catch(\PDOexection $error){

       return $error;

     }
   }

   public function getAgregarMoneda($name){
    if(preg_match_all("/^[a-zA-ZÀ-ÿ ]{3,30}$/", $name) == false){
      $resultado = ['resultado' => 'error', 'error' => 'Nombre de Moneda Invalida'];
      return $resultado;
    }
    
    $this->moneda = $name;

    return $this->agregarMoneda();

   }
   
   private function agregarMoneda(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("INSERT INTO `moneda`(`id_moneda`, `nombre`, `status`) VALUES (DEFAULT,?,1)");
      $new->bindValue(1, $this->moneda);
      $new->execute();
      $idR = $this->con->lastInsertId();
      $resultado = ['resultado' => 'Registado con exito', 'idR' => $idR];
      $this->binnacle("Moneda",$_SESSION['cedula'],"Registró una Moneda.");
      parent::desconectarDB();
      return $resultado;

    }catch(\PDOexection $error){
      return $error;
    }
   }

   public function mostrarM($id){
    $this->id = $id;
    
    try{
      parent::conectarDB();
       $new = $this->con->prepare("SELECT * FROM `moneda` WHERE id_moneda = ?");
       $new->bindValue(1, $this->id);
       $new->execute();
       $data = $new->fetchAll();
       parent::desconectarDB();
      return $data;
     }catch(\PDOexection $error){

       return $error;

     }


   }

   public function getEditarM($nameEdit, $id){
     if(preg_match_all("/^[a-zA-ZÀ-ÿ ]{3,30}$/", $nameEdit) == false){
      return ['resultado' => 'error', 'error' => 'Nombre de Moneda Invalida'];
    }
    
    $this->id = $id;
    $this->moneda = $nameEdit;

    return $this->editarM();
  }

  private function editarM(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE moneda SET nombre = ? WHERE status = 1 AND id_moneda = ?");
      $new->bindValue(1, $this->moneda);
      $new->bindValue(2, $this->id);
      $new->execute();
      $resultado = ['resultado' => 'Actualizado con exito'];
      $this->binnacle("Moneda",$_SESSION['cedula'],"Editó una Moneda.");
      parent::desconectarDB(); 
      return $resultado;

    }catch(\PDOexection $error){
      return $error;
    }
  }

  public function getEliminarM($id){
    $this->id = $id;

    try{
      parent::conectarDB();
       $new = $this->con->prepare("UPDATE moneda SET status = 0 WHERE id_moneda = ? AND status = 1");
       $new->bindValue(1, $this->id);
       $new->execute();
       $data = ['resultado' => 'Eliminado con exito'];
       $this->binnacle("Moneda",$_SESSION['cedula'],"Eliminó una Moneda.");
       parent::desconectarDB();
       return $data;

     }catch(\PDOexection $error){

       return $error;

     }
  }

}
?>