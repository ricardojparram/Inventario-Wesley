<?php  

	namespace modelo;
	use config\connect\DBConnect as DBConnect;

	class sede extends DBConnect{

		private $nombre;
		private $telefono;
		private $direccion;
		private $id;
		private $idedit;
		

		public function __construct(){
			parent::__construct();    
		}
		
		public function getAgregarSede($nombre, $telefono, $direccion){
			
		if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $nombre) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Nombre invalido.'];
                return $resultado;
		}
		if (preg_match_all("/^[0-9]{10,30}$/", $telefono) == false) {
			$resultado = ['resultado' => 'error', 'error' => 'Telefono Invalido'];
			return $resultado;
		}

		if (preg_match_all("/[$%&|<>]/", $direccion) == true) {
			$resultado = ['resultado' => 'error', 'error' => 'Direccion inválida.'];
			return $resultado;
		}
		$this->nombre = $nombre;
		$this->telefono = $telefono;
		$this->direccion = $direccion;

		return $this->agregarSede();

}
private function agregarSede(){
	try{
		parent::conectarDB();
		$new = $this->con->prepare("INSERT INTO `sede`(`id_sede`, `nombre`, `telefono`, `direccion`, `status`) VALUES(DEFAULT,?,?,?,1)");
		$new->bindValue(1, $this->nombre);
		$new->bindValue(2, $this->telefono);
		$new->bindValue(3, $this->direccion);
		$new->execute();
		$data = $new->fetchAll();
		$resultado = ['resultado' => 'Registrado con exito'];
		parent::desconectarDB();
		return $resultado;

	}catch(\PDOexeption $error){
		return $error;
	}
}
	public function getMostrarSede($bitacora = false) {
		try{
			parent::conectarDB();
			$new = $this->con->prepare("SELECT s.id_sede , s.nombre , s.telefono , s.direccion  FROM sede s WHERE s.status = 1");
			$new-> execute();
			$data = $new->fetchAll();
			
			parent::desconectarDB();
			return $data;
		}catch(\PDOexeption $error){

			return $error;
		}
	}
public function getElimarSede($id){
	$this->id = $id;
	return $this->eliminarSede();
}
private function eliminarSede(){

	try{
		parent::conectarDB();
		$new = $this->con->prepare("UPDATE sede s SET s.status ='0' WHERE s.id_sede = ?");
		$new->bindValue(1, $this->id);
		$new->execute();
		$resultado = ['resultado'=>'Eliminado'];
		return $resultado;
		parent::desconectarDB();

	}catch(\PDOexceptio $error){
		return $error;
	}
}
public function mostrarSe($id){
	$this->idedit = $id;
	return $this->gol();

}
private function gol(){
	try{
		parent::conectarDB();
		$new = $this->con->prepare("SELECT s.nombre , s.telefono , s.direccion FROM sede s WHERE s.id_sede = ?");
		$new->bindValue(1,$this->idedit);
		$new->execute(); 
		$data = $new->fetchAll(\PDO::FETCH_OBJ);
		parent::conectarDB();
		return $data;
	}catch(\PDOexception $error){
		return $error;
	}
}

public function getEditarSede($nombre,$telefono,$direccion,$id){

if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $nombre) == false) {
		$resultado = ['resultado' => 'Error de nombre', 'error' => 'Nombre invalido.'];
		return $resultado;
}
if (preg_match_all("/^[0-9]{10,30}$/", $telefono) == false) {
	$resultado = ['resultado' => 'Error de telefono', 'error' => 'Telefono Invalido'];
	return $resultado;
}

if (preg_match_all("/[$%&|<>]/", $direccion) == true) {
	$resultado = ['resultado' => 'Error de direccion', 'error' => 'Direccion inválida.'];
	return $resultado;
}
	$this->nombre = $nombre;
	$this->telefono = $telefono;
	$this->direccion = $direccion;
	$this->idedit = $id;

	return $this->editarSede();
}
private function editarSede(){
	try{
		parent::conectarDB();
		$new = $this->con->prepare("UPDATE `sede` SET `nombre`=?,`telefono`=?,`direccion`=? WHERE  id_sede =?");
		$new->bindValue(1, $this->nombre);
		$new->bindValue(2,$this->telefono);
		$new->bindValue(3,$this->direccion);
		$new->bindValue(4,$this->idedit);
		$new->execute();
		$data = $new->fetchAll();

		$resultado =['resultado'=>'Editado'];
		parent ::desconectarDB();

		return $resultado;

	}catch (\PDOexception $error){
		return $error;
	}
}



	}
?>