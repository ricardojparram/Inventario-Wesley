<?php  

namespace modelo;
use config\connect\DBConnect as DBConnect;
use utils\validar;

class donativoInstituciones extends DBConnect{

	use validar;
	private $id;
	private $institucion;
	private $beneficiario;
	private $datos;
	private $producto;
	private $cantidad;


	public function getMostrarDonativosInstituciones($bitacora = false){
		try {
			parent::conectarDB();

			$query = "SELECT d.id_donaciones , d.fecha , di.rif_int , i.razon_social AS beneficiario FROM donaciones d INNER JOIN donativo_int di ON d.id_donaciones = di.id_donaciones INNER JOIN det_donacion dd ON dd.id_donaciones = d.id_donaciones INNER JOIN instituciones i ON i.rif_int = di.rif_int WHERE d.status = 1 GROUP BY d.id_donaciones;";
			$new = $this->con->prepare($query);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			return $data;

			parent::desconectarDB();

		} catch (\PDOException $e) {

		}

	}

	public function getDetalleDonacion($id){
		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
			return ['resultado' => 'Error de id','error' => 'id inválida.'];
		}

		$this->id = $id;
        
		return $this->detalleDonacion();
	}

	private function detalleDonacion(){
		try {
			parent::conectarDB();

			$new = $this->con->prepare('SELECT d.id_donaciones , dd.cantidad , tp.nombrepro FROM det_donacion dd INNER JOIN donaciones d ON d.id_donaciones = dd.id_donaciones INNER JOIN producto_sede ps ON ps.id_producto_sede = dd.id_producto_sede INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod WHERE d.status = 1 AND d.id_donaciones = ?');
			$new->bindValue(1, $this->id);
			$new->execute();

			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;

		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function selectInstituciones(){
		try {
			parent::conectarDB();

			$new = $this->con->prepare('SELECT i.rif_int , i.razon_social FROM instituciones i WHERE i.status = 1');
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;

		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function selectProductos(){
		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT ps.id_producto_sede, CONCAT(tp.nombrepro, ' ',pr.peso , '',m.nombre) AS producto , ps.lote FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN sede s ON s.id_sede = ps.id_sede INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida WHERE p.status = 1 AND s.status = 1 ORDER BY ps.fecha_vencimiento");

			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;

		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function detallesProductoFila($id){
		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
			return ['resultado' => 'Error de id','error' => 'id inválida.'];
		}

		$this->id = $id;

		return $this->productoFila();
	}

	private function productoFila(){
		try {
			parent::conectarDB();

			$new = $this->con->prepare('SELECT ps.cantidad FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto WHERE p.status = 1 AND ps.id_producto_sede = ?');

			$new->bindValue(1 ,$this->id);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;


		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function validarRif($rif){

		if (!$this->validarString('rif' , $rif))
			return $this->http_error(400, 'rif inválido.');


		$this->institucion = $rif;

		return $this->validRif();

	}

	private function validRif(){
		try {
			parent::conectarDB();

			$new = $this->con->prepare("SELECT i.rif_int FROM instituciones i WHERE i.rif_int = :rif AND i.status = 1");

			$new->bindValue(':rif', $this->institucion);
			$new->execute();
			$data = $new->fetchAll();
			parent::desconectarDB();

			$mensaje = 'El rif ' . $this->institucion . ' no existe';

			if(isset($data[0]['rif_int'])){
				return ['resultado' => 'rif valida', 'res' => true];

			}else{
				return ['resultado' => 'error', 'msg' => $mensaje, 'res' => false];
			}

		}catch (\PDOException $e) {
			return $e;
		}
	}

	private function validProductos(){
		try {
			parent::conectarDB();

			$mensaje = '';

			foreach ($this->datos as $producto){

				$id_producto_sede = $producto['producto'];
				$cantidad  = $producto['cantidad'];


				$new = $this->con->prepare("SELECT ps.cantidad , CONCAT(tp.nombrepro, ' ',pr.peso , '',m.nombre) AS producto , ps.lote FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida WHERE ps.id_producto_sede = :id_producto_sede");
				$new->bindValue(':id_producto_sede',  $id_producto_sede);
				$new->execute();

				$result = $new->fetchAll();


				if (empty($result)) {
					$mensaje .= "Error ID $id_producto_sede no existe. ";

				} else if ($result[0]['cantidad'] < $cantidad) {
					$producto = $result[0]['producto'];
					$mensaje .= "Error no hay suficiente $producto disponible.";

				}

			}

			if ($mensaje) {
				return ['resultado' => 'error', 'msg' => $mensaje, 'res' => false];
			} else {
				return ['resultado' => 'producto valido', 'res' => true];
			}

		}catch (\PDOException $e) {
			return $e;
		}
	}


	public function getRegistrarDonacion($rifInstitucion , $datos){

		if (!$this->validarString('rif', $rifInstitucion))
			return $this->http_error(400, 'rif inválido.');

		$estructura_productos = [
			'producto' => 'string',
			'cantidad' => 'string',
		];

		if (!$this->validarEstructuraArray($datos, $estructura_productos, true))
			return $this->http_error(400, 'Productos inválidos.');

		$this->institucion = $rifInstitucion;
		$this->datos = $datos; 

		$validarRif = $this->validRif();

		if ($validarRif['res'] === false) return $this->http_error(400, $validarRif['msg']);

		$validarProductos = $this->validProductos();

       if ($validarProductos['res'] === false) return $this->http_error(400, $validarProductos['msg']);

		return $this->registrarDonacion();

	}

	 private function registrarDonacion(){
	 	try {
		parent::conectarDB();

		$new = $this->con->prepare('INSERT INTO `donaciones`(`id_donaciones`, `fecha`, `status`) VALUES (DEFAULT , DEFAULT , 1)');
		$new->execute();

		$this->id = $this->con->lastInsertId();


		$new = $this->con->prepare('INSERT INTO `donativo_int`(`id_donativo_int`, `rif_int`, `id_donaciones`, `status`) VALUES (DEFAULT , ? , ? , 1)');
		$new->bindValue(1 , $this->institucion);
		$new->bindValue(2, $this->id);
		$new->execute();


		foreach ($this->datos as $dato) {
			$this->producto = $dato['producto'];
			$this->cantidad = $dato['cantidad'];

			$new = $this->con->prepare('INSERT INTO `det_donacion`(`id_detalle`, `id_producto_sede`, `cantidad`, `id_donaciones`) VALUES (DEFAULT , ? , ? , ?)');
			$new->bindValue(1 , $this->producto);
			$new->bindValue(2 , $this->cantidad);
			$new->bindValue(3 , $this->id);
			$new->execute();

			$new = $this->con->prepare('SELECT ps.cantidad FROM producto_sede ps WHERE ps.id_producto_sede = ?');
			$new->bindValue(1 , $this->producto);
			$new->execute();
			$data = $new->fetchAll();

			$NewCantidad = $data[0]['cantidad'] - $this->cantidad ;

			$new = $this->con->prepare("UPDATE producto_sede ps SET ps.cantidad = ? WHERE ps.id_producto_sede = ?");
			$new->bindValue(1, $NewCantidad);
			$new->bindValue(2, $this->producto);
			$new->execute();
		}

		parent::desconectarDB();
        
        return ['resultado' => 'registrado con exito'];
			
		} catch (\PDOException $e) {
			return $e;
		}
	 }

	  public function validarExistencia($id){
	 	if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
	 		return ['resultado' => 'Error de id','error' => 'id inválida.'];
	 	}

	 	$this->id = $id;

	 	return $this->validExistencia();
	 }
	 

	 private function validExistencia(){
	 	try {
	 		parent::conectarDB();
	 		$new = $this->con->prepare('SELECT d.id_donaciones FROM donaciones d WHERE d.status = 1 AND d.id_donaciones = ?');
	 		$new->bindValue(1,  $this->id);
	 		$new->execute();
	 		$data = $new->fetchAll();

	 		parent::desconectarDB();

	 		if(isset($data[0]["id_donaciones"])){
	 			return['resultado' => 'Si existe esta donacion.'];
	 		}else{
	 			return['resultado' => 'Error de donacion'];
	 		}
	 	} catch (\PDOException $e) {
	 		return $e;
	 	}
	 }


	public function getEliminarDonacion($id){
		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
			return ['resultado' => 'Error de id','error' => 'id inválida.'];
		}

		$this->id = $id;

		return $this->eliminarDonacion();
	}

	private function eliminarDonacion(){
		try {
		parent::conectarDB();

		$new = $this->con->prepare("SELECT ps.id_producto_sede, dd.cantidad , ps.cantidad as stock FROM det_donacion dd INNER JOIN producto_sede ps ON ps.id_producto_sede = dd.id_producto_sede WHERE dd.id_donaciones = ?");

		$new->bindValue(1, $this->id);
		$new->execute();
		$result = $new->fetchAll(\PDO::FETCH_OBJ);

		foreach ($result as $data){

			$stockAct = $data->stock;
			$cantidad = $data->cantidad;
			$idProductoSede = $data->id_producto_sede;

			$NewStock = $cantidad + $stockAct;

			$new = $this->con->prepare("UPDATE producto_sede ps SET ps.cantidad = ? WHERE ps.id_producto_sede = ?");
			$new->bindValue(1, $NewStock);
			$new->bindValue(2, $idProductoSede);
			$new->execute();

		}

		$new = $this->con->prepare('UPDATE donaciones d SET d.status = 0 WHERE d.id_donaciones = ?');
		$new->bindValue(1 , $this->id);
		$new->execute();

		$resultado = ['resultado' => 'Eliminado'];

		parent::desconectarDB();

		return $resultado;

		} catch (\PDOException $e) {
			return $e;
		}
	}




}

?>