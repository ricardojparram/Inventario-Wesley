<?php  

namespace modelo;
use config\connect\DBConnect as DBConnect;

class donativoPaciente extends DBConnect{

	private $id;
	private $paciente;
	private $producto;
	private $cantidad;
	private $cantidadUnitaria;


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


}

?>