<?php
namespace modelo;
use config\connect\DBConnect as DBConnect;


class medida extends DBConnect{

	private $id_medida;
	private $nombre;
	private $status;

	function __construct(){
		parent::__construct();
	}

	public function getAgregarMedida($medida){
		if(preg_match_all("/^[a-zA-Z]{0,30}$/", $medida) == false){
            $resultado = ['resultado' => 'Error de nombre' , 'error' => 'Nombre inválido.'];
            echo json_encode($resultado);
            die();
        }

        $this->medida = $medida;

        $this->AgregarMedida();
	}

 private function agregarMedida(){
 	try{
    parent::conectarDB();
 		$new = $this->con->prepare("INSERT INTO `medida`(`id_medida`, `nombre`, `status`) VALUES (DEFAULT,?,1)");
 		$new->bindValue(1, $this->medida);
 		$new->execute();
 		$data = $new->fetchAll();

 		$resultado = ['resultado' => 'Registrado con exito'];
 		echo json_encode($resultado);
     parent::desconectarDB();
 		die();
 	}catch(\PDOexection $error){
 		return $error;
 	}
 }
 public function getMostrarMedida(){

   	try{
      parent::conectarDB();
     $new = $this->con->prepare("SELECT `m.id_medida`,`m.nombre`, `m.status`FROM `medida m` WHERE m.status = 1");
     $new->execute();
     $data = $new->fetchAll();
     echo json_encode($data);
     parent::desconectarDB();
     die();

    }catch(\PDOexection $error){

     return $error;

    }
  }



public function getEliminartipo($id){
	$this->id = $id;

	$this->eliminartipo();
}

private function eliminartipo(){

	try{
    parent::conectarDB();
	 $new = $this->con->prepare("UPDATE tipo SET status = '0' WHERE cod_tipo = ?");
	 $new->bindValue(1, $this->id);
	 $new->execute();
	 $resultado = ['resultado' => 'Eliminado'];
      echo json_encode($resultado);
      parent::desconectarDB();
      die();
	}catch (\PDOException $error) {
      return $error;
    }
}
public function mostrarlot($lott){
	$this->idedit = $lott;

	$this->gol();
}
private function gol(){
	try{
    parent::conectarDB();
		$new = $this->con->prepare("SELECT * FROM tipo WHERE cod_tipo = ?");
		$new->bindValue(1, $this->idedit);
		$new->execute();
		$data = $new->fetchAll();
		echo json_encode($data);
    parent::desconectarDB();
		die();
	}catch(\PDOException $error){
		return $error;
	}
}
public function getEditarTipo($tipo, $id){
	if(preg_match_all("/^[a-zA-Z]{3,30}$/", $tipo) == false){
            $resultado = ['resultado' => 'Error de tipo de Producto' , 'error' => 'Tipo inválido.'];
            echo json_encode($resultado);
            die();

        }
        $this->tipo = $tipo;
        $this->idedit = $id;

        $this->editarTipo();

}
private function editarTipo(){
	try {
    parent::conectarDB();
		$new = $this->con->prepare("UPDATE `tipo` SET `des_tipo`= ? WHERE cod_tipo = ?");

      $new->bindValue(1, $this->tipo);
      $new->bindValue(2, $this->idedit);
      $new->execute();
      $data = $new->fetchAll();
      
      $resultado = ['resultado' => 'Editado'];
      echo json_encode($resultado);  
      parent::desconectarDB();    
      die();
	} catch (\PDOexception $error) {
		return $error;
	}
}



}

?>