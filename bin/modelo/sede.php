<?php  

	namespace modelo;
	use config\connect\DBConnect as DBConnect;

	class sede extends DBConnect{

		private $nombre;
		private $telefono;
		private $direccion;
		private $id;
		

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
		$new = $this->con->prepare("INSERT INTO `sede`(`id_sede`, `nombre`, `telefono`, `direccion`, `status`) VALUES(DEFAULT ?,?,?,1)");
		$new->bindValue(1, $this->nombre);
		$new->bindValue(2, $this->telefono);
		$new->bindValue(3, $this->direccion);
		$new->execute();
		$data = $new->fetchAll();
		$resultado = ['resultado' => 'Registrado con exito'];
		parent::desconectarDB();
		return $resultado;

	}catch(\PDOexecute $error){
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
		}catch(\PDOexection $error){

			return $error;
		}
	}









	}
?>