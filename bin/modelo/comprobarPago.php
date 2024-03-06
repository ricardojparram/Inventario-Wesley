<?php  

	namespace modelo;
	use config\connect\DBConnect;

	class comprobarPago extends DBConnect{

		private $id_pago;
		private $estado;

		public function __construct(){
			parent::__construct();
		}

		public function mostrarPagos($bitacora){
			try{
				$this->key = parent::KEY();
                $this->iv = parent::IV();
                $this->cipher = parent::CIPHER();

				$this->conectarDB();
				$query='SELECT p.id_pago, v.num_fact, c.cedula, v.fecha, CONCAT(c.nombre, " ", c.apellido) as nombre_cliente, p.monto_total, p.status FROM pago p 
						INNER JOIN venta v ON v.num_fact = p.num_fact
						INNER JOIN cliente c ON c.cedula = v.cedula_cliente
						WHERE v.online = 1;';
				$new = $this->con->prepare($query);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				foreach ($data as $item) {
					$item->cedula = openssl_decrypt($item->cedula, $this->cipher, $this->key, 0, $this->iv);
				}


				if($bitacora === "true") $this->binnacle("Comprobar pagos",$_SESSION['cedula'],"Consultó listado.");
				$this->desconectarDB();
				return $data;
				
			}catch(\PDOexection $e){
				print("Error!: ".$e);
				die();
			}
		}

		public function getComprobacion($id_pago, $estado){
			if(preg_match_all("/^[0-9]{1,10}$/", $id_pago) != 1)
				return ['resultado' => 'error','error' => 'Id de pago inválida.'];

			if(preg_match_all("/^[0-9]{1,10}$/", $estado) != 1)
				return ['resultado' => 'error','error' => 'Id de estado inválida.'];

			$this->id_pago = $id_pago;
			$this->estado = $estado;

			return $this->comprobarPago();
		}

		private function comprobarPago(){
			try{
				$this->conectarDB();

				$sql = "UPDATE pago SET status = ? WHERE id_pago = ?";
				$new = $this->con->prepare($sql);
				$new->bindValue(1, $this->estado);
				$new->bindValue(2, $this->id_pago);
				$resultado;
				if(!$new->execute()){
					$resultado = ['resultado' => 'error', 'msg' => 'Ha ocurrido un error en la base de datos.'];
				}

				$resultado = ['resultado' => 'ok', 'msg' => 'Se ha actualizado el estado del pago.'];
				$this->desconectarDB();
				return $resultado;

			}catch(\PDOexection $e){
				print("Error!: ".$e);
				die();
			}

		}

		public function getDetallePago($id_pago){
			if(preg_match_all("/^[0-9]{1,10}$/", $id_pago) != 1)
				return ['resultado' => 'error','error' => 'Id de pago inválida.'];

			$this->id_pago = $id_pago;
			return $this->detallePago();
		}

		private function detallePago(){
			try {
				$this->conectarDB();
				$sql = "SELECT tp.des_tipo_pago, p.num_fact, dp.monto_pago, datos_cobro.banco_cobro, datos_cliente.banco_cliente, c.cambio FROM pago p
						INNER JOIN detalle_pago dp ON p.id_pago = dp.id_pago
						INNER JOIN tipo_pago tp ON dp.id_tipo_pago = tp.id_tipo_pago
						INNER JOIN cambio c ON c.id_cambio = dp.id_cambio
						LEFT JOIN (
						    SELECT dc.id_datos_cobro, b.nombre as banco_cobro FROM datos_cobro_farmacia dc
						    INNER JOIN banco b ON b.id_banco = dc.id_banco
						) as datos_cobro ON datos_cobro.id_datos_cobro = dp.id_datos_cobro
						LEFT JOIN (
						  SELECT b.id_banco, b.nombre as banco_cliente FROM banco b 
						    INNER JOIN detalle_pago dp ON dp.id_banco_cliente = b.id_banco
						) as datos_cliente ON datos_cliente.id_banco = dp.id_banco_cliente
						WHERE p.id_pago = ?";
				$new = $this->con->prepare($sql);
				$new->bindValue(1, $this->id_pago);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				return $data;

			} catch (\PDOexection $e){
				print("Error!: ".$e);
				die();
			}
		}

	}

?>