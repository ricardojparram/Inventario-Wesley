<?php  

namespace modelo;
use config\connect\DBConnect as DBConnect;

class donativoInstituciones extends DBConnect{

	private $id;
	private $institucion;
	private $beneficiario;
	private $datos;
	private $producto;
	private $cantidad;


	public function getMostrarDonativosInstituciones($bitacora = false){
		try {
			parent::conectarDB();

			$query = 'SELECT d.id_donaciones , d.beneficiario , d.fecha , di.rif_int FROM donaciones d INNER JOIN donativo_int di ON d.id_donaciones = di.id_donaciones INNER JOIN det_donacion dd ON dd.id_donaciones = d.id_donaciones INNER JOIN instituciones i ON i.rif_int = di.rif_int WHERE d.status = 1 GROUP BY d.id_donaciones';
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
			$new = $this->con->prepare('SELECT ps.id_producto_sede, tp.nombrepro , ps.lote FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN sede s ON s.id_sede = ps.id_sede WHERE p.status = 1 AND s.status = 1 ORDER BY ps.fecha_vencimiento');

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

			$new = $this->con->prepare('SELECT ps.cantidad , (pres.cantidad * ps.cantidad) AS unidades , (ps.cantidad * pres.cantidad) - COALESCE(SUM(dd.cantidad), 0) AS unidades_restantes FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN presentacion pres ON p.cod_pres = pres.cod_pres INNER JOIN det_donacion dd ON dd.id_producto_sede = ps.id_producto_sede WHERE p.status = 1 AND ps.id_producto_sede = ?');

			$new->bindValue(1 ,$this->id);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;


		} catch (\PDOException $e) {
			return $e;
		}
	}


	public function getRegistrarDonacion($rifInstitucion , $beneficiario , $datos){
		if(preg_match_all("/^[VEJ]-[A-Z0-9]{7,12}$/", $rifInstitucion) != 1){
			return ['resultado' => 'Error cedula','error' => 'cedula inválida.'];
		}
		if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){3,30}$/', $beneficiario) != 1){
			return['resultado'=> 'error de beneficiario', 'error'=>'beneficiario invalido'];         
		}

		foreach ($datos as $dato) {
			if (!is_array($dato) || !array_key_exists('producto', $dato) || !array_key_exists('unidades', $dato)) {
				return ['resultado' => 'Error en los datos', 'error' => 'Formato de datos incorrecto en el array.'];
			}
		}

		$this->institucion = $rifInstitucion;
		$this->beneficiario = $beneficiario;
		$this->datos = $datos; 

		return $this->registrarDonacion();

	}

	 private function registrarDonacion(){
	 	try {
		parent::conectarDB();

		$new = $this->con->prepare('INSERT INTO `donaciones`(`id_donaciones`, `beneficiario`, `fecha`, `status`) VALUES (DEFAULT , ? , DEFAULT , 1)');
		$new->bindValue(1 , $this->beneficiario);
		$new->execute();

		$this->id = $this->con->lastInsertId();


		$new = $this->con->prepare('INSERT INTO `donativo_int`(`id_donativo_int`, `rif_int`, `id_donaciones`, `status`) VALUES (DEFAULT , ? , ? , 1)');
		$new->bindValue(1 , $this->institucion);
		$new->bindValue(2, $this->id);
		$new->execute();


		foreach ($this->datos as $dato) {
			$this->producto = $dato['producto'];
			$this->unidades = $dato['unidades'];

			$new = $this->con->prepare('INSERT INTO `det_donacion`(`id_detalle`, `id_producto_sede`, `cantidad`, `id_donaciones`) VALUES (DEFAULT , ? , ? , ?)');
			$new->bindValue(1 , $this->producto);
			$new->bindValue(2 , $this->unidades);
			$new->bindValue(3 , $this->id);
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