<?php  
	
	namespace modelo;
    use config\connect\DBConnect as DBConnect;

    class notificaciones extends DBConnect{

    	private $id;

    	public function __construct(){
			parent::__construct();
		}

		public function registrarNotificaciones(){
			try {
				$this->conectarDB();

				$productos_vencidos = $this->getProductosVencidos();
				$productos_stock_bajo = $this->getProductosStockBajo();
				$dia_de_inventario = $this->getDiaDeInventario();

				$this->registrarNoficacion($productos_vencidos , $productos_stock_bajo, $dia_de_inventario);

				$this->desconectarDB();

				echo json_encode(['resultado' => 'notificaciones registradas.']);
			    die();


			} catch (\PDOException $e) {
				print "¡Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}

		public function getNotificaciones(){
			try{
				$this->conectarDB();

				$query = "SELECT `id`, `tipo_notificacion`, `mensaje`, `fecha`, `stock`, `dia_de_inventario` FROM notificaciones n WHERE n.status = 1";

				$new = $this->con->prepare($query);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				echo json_encode($data);
				$this->desconectarDB();
				die();

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


		private function registrarNoficacion($productos_vencidos , $productos_stock_bajo, $dia_de_inventario){
			try{

		   $fechaHoy = date('Y-m-d');
         
		   $new = $this->con->prepare('INSERT INTO notificaciones(id, tipo_notificacion, mensaje, fecha, stock , dia_de_inventario, status) VALUES (default,?,?,?,?,?,1)');

  			foreach ($productos_vencidos as $data) {
				$nombre = $data->descripcion;
				$dias_vencidos = abs($data->dias_vencidos);
                $mensaje = "El producto: {$nombre}.expiro hace {$dias_vencidos} dias";

                $valid = $this->con->prepare('SELECT n.mensaje FROM notificaciones n WHERE  n.mensaje = ? AND n.status = 1');
                $valid->bindValue(1, $mensaje);
                $valid->execute();
                $Valid = $valid->fetchAll();

                if (!isset($Valid[0]['mensaje'])) {

                    $new->bindValue(1, 'productos_vencidos');
					$new->bindValue(2, $mensaje);
					$new->bindValue(3, $fechaHoy);
					$new->bindValue(4, null);
					$new->bindValue(5, null);
					$new->execute();
                    }
			
				}

			foreach ($productos_stock_bajo as $data) {
				$nombre = $data->descripcion;
				$stock = $data->stock;
                $mensaje = "Faltan {$stock} unidades para que se agote el producto: {$nombre}";

                $valid = $this->con->prepare('SELECT n.mensaje FROM notificaciones n WHERE  n.mensaje = ? AND n.status = 1');
                $valid->bindValue(1, $mensaje);
                $valid->execute();
                $Valid = $valid->fetchAll();

                if (!isset($Valid[0]['mensaje'])) {

					$new->bindValue(1, 'productos_stock_bajo');
					$new->bindValue(2, $mensaje);
					$new->bindValue(3, $fechaHoy);
					$new->bindValue(4, null);
					$new->bindValue(5, null);
					$new->execute();
					}
				}

			foreach ($dia_de_inventario as $data) {
				$nombre = $data->descripcion;
				$stock = $data->stock;
			    $dia_inventario = $data->dia_inventario;
                $mensaje = "Quedan " . round($stock / $dia_inventario) . " dias de inventario del producto: <strong>" . $nombre . "</strong>";

                $valid = $this->con->prepare('SELECT n.mensaje FROM notificaciones n WHERE  n.mensaje = ? AND n.status = 1');
                $valid->bindValue(1, $mensaje);
                $valid->execute();
                $Valid = $valid->fetchAll();

                if (!isset($Valid[0]['mensaje'])) {

					$new->bindValue(1, 'dia_de_inventario');
					$new->bindValue(2, $mensaje);
					$new->bindValue(3, $fechaHoy);
					$new->bindValue(4, $stock);
					$new->bindValue(5, $dia_inventario);
					$new->execute();
				  }
				}


			}catch(\PDOException $e) {
				print "¡Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}


		public function agregarNotificacion($mensaje, $nombre){			
			try {

			$this->conectarDB();
			$fechaHoy = date('Y-m-d');

			$data = json_decode($mensaje, true);

			$cedula = $data['cedula'];
			$name = $data['nombre'];
			$apellido = $data['apellido'];
			$resultado = $data['resultado'];

			$mensaje = $resultado . ' de: ' . $name . ' ' . $apellido . ' CI: ' . $cedula;

			 $new = $this->con->prepare('INSERT INTO notificaciones(id, tipo_notificacion, mensaje, fecha, stock , dia_de_inventario, status) VALUES (default,?,?,?,?,?,1)');

			 $new->bindValue(1, $nombre);
			 $new->bindValue(2, $mensaje);
			 $new->bindValue(3, $fechaHoy);
			 $new->bindValue(4, null);
			 $new->bindValue(5, null);
			 $new->execute();

			 $this->desconectarDB();
			 echo json_encode(['resultado' => 'notificacion registrada']);
			 die();

			} catch (\PDOException $e) {
				die($e);
			}
		}

		private function getProductosVencidos(){
			try {

				$query="SELECT p.descripcion, DATEDIFF(p.vencimiento, NOW()) AS dias_vencidos,
						TIMEDIFF(DATE_FORMAT(p.vencimiento, '%Y-%m-%d %H:%i:%s'), NOW()) AS horas_vencidas FROM producto p 
						WHERE p.vencimiento < NOW() AND p.stock > 0 AND p.status = 1";
				$new = $this->con->prepare($query);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				return $data;
			} catch (\PDOException $e) {
				die($e);
			}
		}

		private function getDiaDeInventario(){
			try {

				$query="SELECT p.descripcion , v.fecha , p.stock , SUM(vp.cantidad) as cantidadXmes,
						SUM(vp.cantidad)/30 as dia_inventario FROM producto p 
						INNER JOIN venta_producto vp ON p.cod_producto = vp.cod_producto 
						INNER JOIN venta v ON v.num_fact = vp.num_fact 
						WHERE MONTH(v.fecha) = (MONTH(NOW())-1) AND p.status = 1 AND v.status = 1 
						GROUP BY p.cod_producto";
				$new2 = $this->con->prepare($query);
				$new2->execute();
				return $new2->fetchAll(\PDO::FETCH_OBJ);

			} catch (\PDOException $e) {
				die($e);
			}
		}

		private function getProductosStockBajo(){
			try {
				
				$query="SELECT p.cod_producto , p.descripcion , p.stock FROM producto p 
						WHERE p.stock <= 10 AND p.status = 1";
				$new = $this->con->prepare($query);
				$new->execute();
				return $new->fetchAll(\PDO::FETCH_OBJ);

			} catch (\PDOException $e) {
				die($e);
			}
		}

	}

?>