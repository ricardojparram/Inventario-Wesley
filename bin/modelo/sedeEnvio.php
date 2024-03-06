<?php  

	namespace modelo;
	use config\connect\DBConnect as DBConnect;

	class sedeEnvio extends DBConnect{

		private $empresa;
		private $estado;
		private $ubicacion;
		private $id;
		private $nombre;

		public function __construct(){
			parent::__construct();    
		}

		public function registrarSedes(){
			try {
				
				$opts = [
					"ssl" => [
						"verify_peer"=>false,
						"verify_peer_name"=>false,
					],
				];

				$context = stream_context_create($opts);

				$url = "https://api.mrwve.com/maps/buscar_agencia.php?id=*&id=*";
				$response = file_get_contents($url, false, $context);
				['agencias' => $data] = json_decode($response, true);
				// echo "<pre>";
				// print_r($data);
				// echo "<pre>";
				// die();
				$this->conectarDB();
				$sql = "INSERT INTO sede_envio(id_sede, ubicacion, id_estado, id_empresa, nombre, status) VALUES (?, ?, ?, 1, ?, 1)";
				$this->conectarDB();
				foreach ($data as $agencia) {
					$estado = $agencia['ID_ESTADO'];
					$nombre = ucwords(mb_strtolower($agencia['NOMBRE']));
					$direccion = $agencia['DIRECCION'];
					$pk = $this->uniqueID();
					$new = $this->con->prepare($sql);
					$new->bindValue(1, $pk);
					$new->bindValue(2, $direccion);
					$new->bindValue(3, $estado);
					$new->bindValue(4, $nombre);
					$new->execute();
				}
				$this->desconectarDB();
				die('lito');
				die(json_encode($response));

			} catch (\PDOException $e) {
				die($e);
			}
		}

		public function mostrarSedes($bitacora = false){
			try{
				$this->conectarDB();
				$sql = "SELECT s.id_sede, e.nombre as empresa, s.nombre as sede, s.ubicacion, s.id_empresa, ev.nombre as estado FROM sede_envio s
						INNER JOIN empresa_envio e ON s.id_empresa = e.id_empresa
						INNER JOIN estados_venezuela ev ON ev.id_estado = s.id_estado
						WHERE s.status = 1;";
				$new = $this->con->prepare($sql);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				if($bitacora ===  "true") $this->binnacle("Sede de envío",$_SESSION['cedula'],"Consultó listado.");
				$this->desconectarDB();
				return $data;

			}catch(\PDOException $e){
				return $e;
			}
		}

		public function selectEmpresas(){
			try{
				$this->conectarDB();
				$sql = "SELECT id_empresa, nombre, rif  FROM empresa_envio WHERE status = 1";
				$new = $this->con->prepare($sql);
				$new->execute();
				$this->desconectarDB();
				return $new->fetchAll(\PDO::FETCH_OBJ);

			}catch(\PDOException $e){
				die($e);
			}
		}

		public function selectEstados(){
			try{
				$this->conectarDB();
				$new = $this->con->prepare("SELECT id_estado, nombre FROM estados_venezuela");
				$new->execute();
				$this->desconectarDB();
				return $new->fetchAll(\PDO::FETCH_OBJ);

			}catch(\PDOException $e){
				die($e);
			}
		}

		public function getValidarEmpresa($empresa){
			if(preg_match_all("/^[0-9]{1,10}$/", $empresa) != 1)
				return ['resultado' => 'error','error' => 'Id inválida.'];

			$this->empresa = $empresa;

			return $this->validarEmpresa();
		}

		public function validarEmpresa(){

			try {
				$this->conectarDB();
				$new = $this->con->prepare('SELECT * FROM empresa_envio WHERE id_empresa = ? AND status = 1');
				$new->bindValue(1,$this->empresa);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				$this->desconectarDB();
				return isset($data[0]->id_empresa);

			} catch (\PDOException $e) {
				die($e);
			}
		}

		public function getRegistrarSede($empresa, $estado, $nombre, $ubicacion){
			if(preg_match_all('/^[a-zA-ZÀ-ÿ0-9\s+,.\-\/\'"#]{7,200}$/', $ubicacion) != 1)
				return ['resultado' => 'error','error' => 'Direccion inválida.'];
			
			if(preg_match_all('/^[a-zA-ZÀ-ÿ0-9\s,.\-\/\'"#]{7,70}$/', $nombre) != 1)
				return ['resultado' => 'error','error' => 'Nombre de sede inválido.'];
			
			if(preg_match_all("/^[0-9]{1,5}$/", $estado) != 1)
				return ['resultado' => 'error','error' => 'Estado inválido.'];
			
			if(preg_match_all("/^[0-9]{1,5}$/", $empresa) != 1)
				return ['resultado' => 'error','error' => 'Empresa inválido.'];
			

			$this->empresa = $empresa;
			$this->estado = $estado;
			$this->ubicacion = $ubicacion;
			$this->nombre = $nombre;

			if($this->validarEmpresa($this->empresa) != true)
				return ['resultado' => false, 'msg' => 'La empresa no existe.'];

			return $this->registrarSede();

		}

		private function registrarSede(){

			try{
				$this->conectarDB();
				$pk = $this->uniqueID();
				$new = $this->con->prepare("INSERT INTO sede_envio(id_sede, nombre, ubicacion, id_estado, id_empresa, status)
											VALUES (? ,? ,? ,? ,?, 1)");
				$new->bindValue(1, $pk); 
				$new->bindValue(2, $this->nombre); 
				$new->bindValue(3, $this->ubicacion); 
				$new->bindValue(4, $this->estado); 
				$new->bindValue(5, $this->empresa); 

				if($new->execute()){
					$resultado = ['resultado' => true, 'msg' => 'Sede registrada.'];
					$this->binnacle("Sede de envío",$_SESSION['cedula'],"Registró sede de envío .");
				}else{
					die('xd');
				}
				$this->desconectarDB();
				return $resultado;

			}catch(\PDOException $error){
				return $error;
			} 

		}

		public function getSede($id){

			if(preg_match_all("/^[a-fA-F0-9]{10}$/", $id) != 1)
				return ['resultado' => 'error','error' => 'Id inválida.'];

			$this->id = $id;

			return $this->selectSede();
		}

		private function selectSede(){

			try{
				$this->conectarDB();
				$sql = "SELECT nombre, ubicacion, id_estado, id_empresa FROM sede_envio WHERE id_sede = ? AND status = 1;";
				$new = $this->con->prepare($sql);
				$new->bindValue(1, $this->id);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				$this->desconectarDB();
				if(!isset($data[0])){
					return false;
				}else{
					return $data[0];
				}

			}catch(\PDOException $e){
				return $e;
			}

		}

		public function getEditarSede($empresa, $estado, $nombre, $ubicacion, $id){

			if(preg_match_all('/^[a-zA-ZÀ-ÿ0-9\s,.\-\/\'"#]{7,200}$/', $ubicacion) != 1)
				return ['resultado' => 'error','error' => 'Direccion inválida.'];
			
			if(preg_match_all('/^[a-zA-ZÀ-ÿ0-9\s,.\-\/\'"#]{4,70}$/', $nombre) != 1)
				return ['resultado' => 'error','error' => 'Nombre de sede inválido.'];
			
			if(preg_match_all("/^[0-9]{1,5}$/", $estado) != 1)
				return ['resultado' => 'error','error' => 'Estado inválido.'];
			
			if(preg_match_all("/^[0-9]{1,5}$/", $empresa) != 1)
				return ['resultado' => 'error','error' => 'Empresa inválido.'];
			
			if(preg_match_all("/^[a-fA-F0-9]{10}$/", $id) != 1)
				return ['resultado' => 'error','error' => 'Id inválida.'];

			$this->empresa = $empresa;
			$this->estado = $estado;
			$this->ubicacion = $ubicacion;
			$this->nombre = $nombre;
			$this->id = $id;

			if($this->validarEmpresa($this->empresa) !== true)
				return ['resultado' => false, 'msg' => 'La empresa no existe.'];				

			if($this->selectSede() != false)
				return $this->editarSede();

		}

		private function editarSede(){

			try{
				$this->conectarDB();
				$sql = "UPDATE sede_envio SET nombre= ?, ubicacion = ?, id_estado = ?,id_empresa = ?
						WHERE id_sede = ?";
				$new = $this->con->prepare($sql);
				$new->bindValue(1, $this->nombre);
				$new->bindValue(2, $this->ubicacion);
				$new->bindValue(3, $this->estado);
				$new->bindValue(4, $this->empresa);
				$new->bindValue(5, $this->id);
				$new->execute();

				$this->binnacle("Sede de envío",$_SESSION['cedula'],"Editó sede de envío.");
				$this->desconectarDB();

				return ['resultado' => true, 'msg' => 'Sede de envío editada.'];

			}catch(\PDOException $error){
				echo json_encode($error);
				die();
			}

		} 


		public function getEliminarSede($id){
			if(preg_match_all("/^[a-fA-F0-9]{10}$/", $id) != 1)
				return ['resultado' => 'error','error' => 'Id inválida.'];

			$this->id = $id;

			if($this->selectSede() != true)
				return ['resultado' => true, 'msg' => 'La sede de envío no existe.'];

			return  $this->eliminarSede();
		}

		private function eliminarSede(){
			try{
				$this->conectarDB();
				$new = $this->con->prepare("UPDATE sede_envio SET status = 0 WHERE id_sede = ?;");
				$new->bindValue(1, $this->id);
				$new->execute();
				$this->binnacle("Sede de envío",$_SESSION['cedula'],"Eliminó sede de envío.");
				$this->desconectarDB();

				return ['resultado' => true, 'msg' => 'Se eliminó la sede correctamente.'];

			}catch(\PDOException $e){
				return $e;
			}

		}

		public function getIdSedeByName($name){
			if(preg_match_all('/^[a-zA-ZÀ-ÿ0-9\s,.\-\/\'"#]{4,70}$/', $name) != 1){
				return ['resultado' => 'error','msg' => 'Nombre inválido.'];
			}
			$this->nombre = $name;

			return $this->gettingIdTest();
		}

		private function gettingIdTest(){
			try {

				$this->conectarDB();
				$sql = "SELECT id_sede FROM sede_envio WHERE nombre = ? AND status = 1";
				$new = $this->con->prepare($sql);
				$new->bindValue(1, $this->nombre);
				$new->execute();
				[$data] = $new->fetchAll(\PDO::FETCH_OBJ);

				return ['resultado' => "ok", "id"=> $data->id_sede];

			} catch (\PDOException $e) {
				die($e);
			}
		}

	}

?>