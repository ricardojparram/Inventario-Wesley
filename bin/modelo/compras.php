<?php 

	namespace modelo;
    use config\connect\DBConnect as DBConnect;

	class compras extends DBConnect{




		private $proveedor;
		private $orden;
		private $fecha;
		private $montoT;
		private $cambio;

		private $producto;
		private $cantidad;
		private $precio;
		private $id;

		public function __construct(){
			parent::__construct();
		}

		public function mostrarCompras($bitacora = false){

			try {

				parent::conectarDB();


				$query = "SELECT c.cod_compra, c.fecha ,c.orden_compra, p.razon_social, format (c.monto_total,2, 'de_DE') as total, CONCAT(IF(MOD(format(c.monto_total,2,'de_DE') / format(cm.cambio,2,'de_DE'), 1) >= 0.5, CEILING(c.monto_total / cm.cambio), FLOOR(format(c.monto_total,2,'de_DE') / format( cm.cambio,2,'de_DE') ) + 0.5), ' ', m.nombre) as 'total_divisa' FROM compra c INNER JOIN proveedor p ON c.cod_prove = p.cod_prove INNER JOIN cambio cm ON cm.id_cambio = c.id_cambio INNER JOIN moneda m ON cm.moneda = m.id_moneda WHERE c.status = 1";

				$new = $this->con->prepare($query);
				$new->execute();
				$data = $new->fetchAll();

				echo json_encode($data);


				if($bitacora) $this->binnacle("Compras",$_SESSION['cedula'],"Consultó listado.");

				parent::desconectarDB();
				die();

			} catch (\PDOException $e) {
				return $e;
			}

		}

		public function mostrarProveedor(){
			try {

				parent::conectarDB();

				$new = $this->con->prepare("SELECT cod_prove, razon_social FROM proveedor WHERE status = 1");
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				parent::desconectarDB();
				return $data;

			} catch (\PDOException $e) {
				return $e;
			}
		}

		public function mostrarSelect(){
			try {
				parent::conectarDB();
				$new = $this->con->prepare("SELECT cod_producto , descripcion FROM producto WHERE status = 1");
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				
				echo json_encode($data);
				parent::desconectarDB();
				die();

			} catch (\PDOException $e) {
				return $e;
			}
		}

		public function mostrarMoneda(){
			try{
				parent::conectarDB();
				$new = $this->con->prepare("
					SELECT * FROM(
						SELECT c.id_cambio, m.nombre, c.cambio FROM cambio c 
						INNER JOIN moneda m ON c.moneda = m.id_moneda 
						WHERE c.status = 1 
						ORDER BY c.fecha DESC LIMIT 9999999
					) as tabla 
					GROUP BY tabla.nombre");
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);

				echo json_encode($data);
				parent::desconectarDB();
				die();

			}catch(\PDOexection $error){

				return $error;   

			} 
		}

		public function productoDetalle($id){
			if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
				die(json_encode(['error' => 'Id inválida.']));
			}
			$this->producto = $id;

			try {
				parent::conectarDB();
				$new = $this->con->prepare("SELECT p_venta, stock FROM producto WHERE cod_producto = ? and status = 1");
				$new->bindValue(1, $this->producto);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				
				echo json_encode($data);
				parent::desconectarDB();
				die();

			} catch (\PDOException $e) {
				return $e;
			}
		}

		public function getOrden($orden){
			if(preg_match_all("/^[0-9]{1,30}$/", $orden) != 1){
				die(json_encode(['resultado' => 'Error de orden', 'error' => 'Orden inválida']));
			}
			$this->orden = abs($orden);

			$this->validarOrden();
		}

		private function validarOrden(){
			try {
				parent::conectarDB();
				$new = $this->con->prepare("SELECT orden_compra FROM compra WHERE status = 1 AND orden_compra = ?;");
				$new->bindValue(1, $this->orden);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);

				parent::desconectarDB();

 
				if(isset($data[0]->orden_compra)){
					die(json_encode(['resultado' => 'Error de orden', 'error' => 'Orden de compra ya registrada.']));
				}else{
					die(json_encode(['resultado' => 'Orden válida']));
				}

			} catch (\PDOException $e) {
				
			}
		}

		public function getCompras($prove, $orden, $fecha, $cambio, $montoT){

			if(preg_match_all("/^[0-9]{1,10}$/", $prove) != 1){
				die(json_encode(['error' => 'Proveedor inválido.']));
			}
			if(preg_match_all("/^[0-9]{1,30}$/", $orden) != 1){
				die(json_encode(['error' => 'Orden inválida']));
			}
			if(preg_match_all("/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/", $fecha) != 1){
				die(json_encode(['error' => 'Fecha inválida.']));
			}
			if(!is_numeric($cambio)){
				die(json_encode(['error' => 'Moneda inválida.']));
			}
			if(!is_numeric($montoT)){
				die(json_encode(['error' => 'Monto inválido.']));
			}

			$this->proveedor = abs($prove);
			$this->orden = abs($orden);
			$this->fecha = $fecha;
			$this->cambio = abs($cambio);
			$this->montoT = abs($montoT);

			$this->registrarCompras();
		}

		private function registrarCompras(){

			try{


                parent::conectarDB();

				$new = $this->con->prepare("SELECT orden_compra FROM compra WHERE status = 1 AND orden_compra = ?;");
				$new->bindValue(1, $this->orden);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);

				if(isset($data[0]->orden_compra)){
					die(json_encode(['resultado' => 'Error de orden', 'error' => 'Orden de compra ya registrada.']));
				}

				$new = $this->con->prepare('INSERT INTO compra (orden_compra, fecha, id_cambio, monto_total, status , cod_prove) VALUES (?, ?, ?, ?, 1, ?)');
				$new->bindValue(1, $this->orden);
				$new->bindValue(2, $this->fecha);
				$new->bindValue(3, $this->cambio);
				$new->bindValue(4, $this->montoT);
				$new->bindValue(5, $this->proveedor);
				$new->execute();

				$this->id = $this->con->lastInsertId();
				echo json_encode(['resultado' => 'Orden registrada.', 'id' => $this->id]);
				parent::desconectarDB();



				die();

			}catch(\PDOException $e){
				return $e;
			}

		}

		public function getProducto($cantidad, $precio, $producto, $idcompra){
			if(!is_numeric($cantidad) || !is_numeric($precio) || !is_numeric($producto) || !is_numeric($idcompra)){
				die(['error' => 'Parámetros de producto inválido.']);
			}
			$this->cantidad = $cantidad;
			$this->precio = $precio;
			$this->producto = $producto;
			$this->id = $idcompra;

			$this->registrarProducto();
		}

		private function registrarProducto(){

			try {
				parent::conectarDB();


				$new = $this->con->prepare('INSERT INTO compra_producto (cod_compra, cod_producto, cantidad, precio_compra) 
											VALUES (?, ?, ?, ?) ');
				$new->bindValue(1, $this->id);
				$new->bindValue(2, $this->producto);
				$new->bindValue(3, $this->cantidad);
				$new->bindValue(4, $this->precio);
				$new->execute();

				$new = $this->con->prepare('SELECT stock FROM producto WHERE cod_producto = ?');
				$new->bindValue(1, $this->producto);
				$new->execute();
				$stock= $new->fetchAll(\PDO::FETCH_OBJ);
				$stockActual = $stock[0]->stock;

				$stockActual += $this->cantidad;
				$new = $this->con->prepare('UPDATE producto SET	stock = ? WHERE cod_producto = ?');
				$new->bindValue(1, $stockActual);
				$new->bindValue(2, $this->producto);
				$new->execute();
				echo "Stock actualizado";
				parent::desconectarDB();
				die();	
				
			} catch (\PDOException $e) {
				return $e;
			}

		}

		public function getDetalleCompra($id){
			if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
				die(json_encode(['error' => 'Id inválida.']));
			}
			$this->id = $id;

			$this->detalleCompra();
		}

		private function detalleCompra(){
			try {
				parent::conectarDB();

				$new = $this->con->prepare('SELECT cp.cantidad, cp.precio_compra, c.orden_compra, p.descripcion FROM compra_producto cp 
											INNER JOIN producto p 
											ON p.cod_producto = cp.cod_producto
											INNER JOIN compra c 
											ON c.cod_compra = cp.cod_compra
											WHERE cp.cod_compra = ?');

				$new = $this->con->prepare('SELECT cp.cantidad, cp.precio_compra, c.orden_compra, p.descripcion FROM compra_producto cp INNER JOIN producto p ON p.cod_producto = cp.cod_producto INNER JOIN compra c ON c.cod_compra = cp.cod_compra WHERE cp.cod_compra = ?');

				$new->bindValue(1, $this->id);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				echo json_encode($data);
				parent::desconectarDB();
				die();
				
			} catch (\PDOException $e) {
				return $e;
			}

		}

		public function getEliminarCompra($id){
			if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
				die(json_encode(['error' => 'Id inválida.']));
			}
			$this->id = $id;

			$this->eliminarCompra();
		}

		private function eliminarCompra(){
			try {
		
	
				parent::conectarDB();

				$query="SELECT cp.cod_producto AS producto, cp.cantidad, p.stock FROM compra_producto cp
						INNER JOIN producto p
						ON cp.cod_producto = p.cod_producto
						WHERE cp.cod_compra = ?";

		         		$new = $this->con->prepare($query);
				$new->bindValue(1, $this->id);
				$new->execute();
				$compra = $new->fetchAll(\PDO::FETCH_OBJ);

				foreach ($compra as $producto) {
					
					$cantidad = $producto->cantidad;
					$stock = $producto->stock;
					$id = $producto->producto;

					$newStock = $stock - $cantidad ;

					$new = $this->con->prepare("UPDATE producto SET stock = ? WHERE cod_producto = ?");
					$new->bindValue(1, $newStock);
					$new->bindValue(2, $id);
					$new->execute();

				}

				$new = $this->con->prepare('UPDATE compra SET status = 0 WHERE cod_compra = ?');
				$new->bindValue(1, $this->id);
				$new->execute();


                parent::desconectarDB();

				die(json_encode(['resultado' => 'Compra anulada.']));

			} catch (\PDOException $e) {
				return $e;
			}

		}


	}


?>