<?php  
	
	namespace modelo;
    use config\connect\DBConnect as DBConnect;

    class notificaciones extends DBConnect{

    	private $id;


		public function getNotificaciones(){
			try{
				parent::conectarDB();

				$query = "SELECT n.id , n.titulo , n.mensaje , n.fecha FROM notificaciones n WHERE n.status = 1";

				$new = $this->con->prepare($query);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				
				return $data;

				parent::desconectarDB();

			}catch(\PDOException $e){
				print "¡Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}

		public function mostrarDetalleNotificacion($id){
			if(preg_match_all("/^[0-9]{1,15}$/", $id) != 1){
            die("Error de id!");
           }

 			$this->id = $id;

 			return $this->detalleNotificacion();
		}

		private function detalleNotificacion(){
			try{
				parent::conectarDB();

				$query = "SELECT n.titulo , n.mensaje , n.fecha FROM notificaciones n WHERE n.status = 1 AND n.id = ?";

				$new = $this->con->prepare($query);
				$new->bindValue(1, $this->id);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				
				die(json_encode($data));

				parent::desconectarDB();

			}catch(\PDOException $e){
				print "¡Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}

		public function notificacionVista($id){

		   if(preg_match_all("/^[0-9]{1,15}$/", $id) != 1){
            die("Error de id!");
           }

 			$this->id = $id;

 			$this->editarStatusNotificacion();
   
		}

		private function editarStatusNotificacion(){
			 try{
				$this->conectarDB();

				$query = "UPDATE notificaciones n SET status = 0 WHERE n.status = 1 AND n.id = ?";

				$new = $this->con->prepare($query);
				$new->bindValue(1, $this->id);
				$new->execute();
				$new->fetchAll(\PDO::FETCH_OBJ);

				$this->desconectarDB();
				die(json_encode(['resultado' => 'notificaciones eliminada.']));

			}catch(\PDOException $e){
				print "¡Error!: " . $e->getMessage() . "<br/>";
				die();
			}

		}


	}

?>