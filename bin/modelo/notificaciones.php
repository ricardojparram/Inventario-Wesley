<?php  
	
	namespace modelo;
    use config\connect\DBConnect as DBConnect;

    class notificaciones extends DBConnect{

    	private $id;


		public function getNotificaciones(){
			try{
				parent::conectarDB();

				$this->mostraProductoVencido();

				$query = "SELECT n.id , n.titulo , n.fecha FROM notificaciones n WHERE n.status = 1 ORDER BY n.id DESC";

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


		private function mostraProductoVencido(){
			try{

           // Realizar la consulta a la base de datos
			$query = "SELECT CONCAT( ps.lote,': ' ,tp.nombrepro, ' ', pr.peso, ' ', m.nombre) AS producto, ABS(DATEDIFF(ps.fecha_vencimiento, NOW())) AS dias_vencidos, ps.cantidad FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida WHERE ps.cantidad > 0 AND ps.fecha_vencimiento < NOW()";
			$new = $this->con->prepare($query);
			$new->execute();
			$result = $new->fetchAll(\PDO::FETCH_OBJ);

           // Recorrer los resultados de la consulta
			foreach ($result as $row) {
				$producto = $row->producto;
				$diasVencidos = $row->dias_vencidos;
				$cantidad = $row->cantidad;

                // Construir el mensaje
				$mensaje = "El producto expiro hace $diasVencidos dias. Quedan $cantidad unidades. Se recomienda priorizar estos.";

				$query = "SELECT COUNT(*) as count FROM notificaciones n WHERE n.mensaje = ? AND n.status = 1";
				$new = $this->con->prepare($query);
				$new->bindValue(1, $mensaje);
				$new->execute();
				$count = $new->fetchColumn();


				if ($count == 0) {
    				$query = "INSERT INTO notificaciones (titulo, mensaje, fecha, status)
					VALUES (:titulo, :mensaje, NOW(), 1)";
					$new = $this->con->prepare($query);
					$new->bindValue(':titulo', "Producto $producto vencido");
					$new->bindValue(':mensaje', $mensaje);
					$new->execute();
				}

			}


			}catch(\PDOException $e){
				return $e;
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