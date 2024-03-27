<?php  

namespace modelo;
use config\connect\DBConnect as DBConnect;

class donativoPaciente extends DBConnect{

	private $id;
	private $paciente;
	private $beneficiario;
	private $datos;
	private $producto;
	private $cantidad;


	public function getMostrarDonativosPacientes($bitacora = false){
		try {
			parent::conectarDB();

			$query = 'SELECT d.id_donaciones , d.beneficiario , d.fecha , dp.ced_pac FROM donaciones d INNER JOIN donativo_pac dp ON d.id_donaciones = dp.id_donaciones INNER JOIN det_donacion dd ON dd.id_donaciones = d.id_donaciones WHERE d.status = 1 GROUP BY d.id_donaciones';
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
		
		$new = $this->con->prepare('SELECT d.id_donaciones , dd.cantidad , tp.nombrepro FROM det_donacion dd INNER JOIN donaciones d ON d.id_donaciones = dd.id_donaciones INNER JOIN producto p ON p.cod_producto = dd.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod WHERE d.status = 1 AND d.id_donaciones = ?');
		$new->bindValue(1, $this->id);
		$new->execute();

		$data = $new->fetchAll(\PDO::FETCH_OBJ);

		parent::desconectarDB();

		return $data;

		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function selectPacientes(){
		try {
		parent::conectarDB();

		$new = $this->con->prepare('SELECT p.ced_pac , p.nombre , p.apellido FROM pacientes p WHERE p.status = 1');
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
			$new = $this->con->prepare('SELECT ps.id_producto_sede, tp.nombrepro , ps.lote , ps.id_sede , ps.fecha_vencimiento , ps.cantidad FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN sede s ON s.id_sede = ps.id_sede WHERE p.status = 1 AND s.status = 1 ORDER BY ps.fecha_vencimiento');

			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;

		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function selectSedes(){
		try {
			parent::conectarDB();

			$new = $this->con->prepare('SELECT s.id_sede , s.nombre FROM sede s WHERE s.status = 1');
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

			$new = $this->con->prepare('SELECT ps.cantidad , (pres.cantidad * ps.cantidad) AS unidades , (ps.cantidad * pres.cantidad) - COALESCE(SUM(dd.cantidad), 0) AS unidades_restantes FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN presentacion pres ON p.cod_pres = pres.cod_pres LEFT JOIN det_donacion dd ON dd.cod_producto = p.cod_producto WHERE p.status = 1 AND ps.id_producto_sede = ?');

			$new->bindValue(1 ,$this->id);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;


		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function getRegistrarDonacion($cedulaPaciente , $beneficiario , $datos){
		if(preg_match_all("/^[0-9]{7,10}$/", $cedulaPaciente) != 1){
			return ['resultado' => 'Error de id','error' => 'id inválida.'];
		}
		if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){3,30}$/', $beneficiario) != 1){
          return['resultado'=> 'error de metodo', 'error'=>'metodo invalido'];         
       }

       foreach ($datos as $dato) {
       	if (!is_array($dato) || !array_key_exists('producto', $dato) || !array_key_exists('unidades', $dato)) {
       		return ['resultado' => 'Error en los datos', 'error' => 'Formato de datos incorrecto en el array.'];
       	}
       }

       $this->paciente = $cedulaPaciente;
       $this->beneficiario = $beneficiario;
       $this->datos = $datos; 

       return $this->registrarDonacion();

	}

	private function registrarDonacion(){
		try {
		parent::conectarDB();

		/* Registrar donación*/
		$new = $this->con->prepare('INSERT INTO `donaciones`(`id_donaciones`, `beneficiario`, `fecha`, `status`) VALUES (DEFAULT , ? , DEFAULT , 1)');
		$new->bindValue(1 , $this->beneficiario);
		$new->execute();

		$this->id = $this->con->lastInsertId();

		/* Registrar paciente */

		$new = $this->con->prepare('INSERT INTO `donativo_pac`(`id_donativopac`, `ced_pac`, `id_donaciones`) VALUES (DEFAULT , ? , ?)');
		$new->bindValue(1 , $this->paciente);
		$new->bindValue(2, $this->id);
		$new->execute();

		/* Registrar Detalle de donación */

		foreach ($this->datos as $dato) {
			$res = $this->con->prepare('SELECT ps.cod_producto FROM producto_sede ps WHERE ps.id_producto_sede = ?');
			$res->bindValue(1 , $dato['producto']);
			$res->execute();
			$this->producto = $res->fetchColumn();
			$this->unidades = $dato['unidades'];

			$new = $this->con->prepare('INSERT INTO `det_donacion`(`id_detalle`, `cod_producto`, `cantidad`, `id_donaciones`) VALUES (DEFAULT , ? , ? , ?)');
			$new->bindValue(1 , $this->producto);
			$new->bindValue(2 , $this->unidades);
			$new->bindValue(3 , $this->id);
			$new->execute();
		}

		parent::desconectarDB();
        
        return 'Chale ya sirve';
			
		} catch (\PDOException $e) {
			return $e;
		}
	}


}

?>