<?php  

	namespace modelo;
	use config\connect\DBConnect;

	class envios extends DBConnect{

		private $id_envio;
		private $estado;

		public function __construct(){
			parent::__construct();
		}

		public function mostrarEnvios($bitacora){
			try{
				$this->conectarDB();
				$this->key = parent::KEY();
				$this->iv = parent::IV();
				$this->cipher = parent::CIPHER();
				$query='SELECT v.num_fact, e.id_envio, e.status, e.fecha_envio, e.fecha_entrega, 
						CONCAT(c.nombre," ", c.apellido) as nombre_cliente, c.cedula, 
						CONCAT(emp.nombre," ",sd.ubicacion) as sede_empresa FROM envio e 
						INNER JOIN venta v ON v.id_envio = e.id_envio
						INNER JOIN cliente c ON c.cedula = v.cedula_cliente
						INNER JOIN sede_envio sd ON sd.id_sede = e.id_sede
						INNER JOIN empresa_envio emp ON emp.id_empresa = sd.id_empresa';
				$new = $this->con->prepare($query);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				foreach ($data as $item) {
					$item->cedula = openssl_decrypt($item->cedula, $this->cipher, $this->key, 0, $this->iv);
				}

				if($bitacora === "true") $this->binnacle("Envios",$_SESSION['cedula'],"Consultó listado.");
				$this->desconectarDB();
				return $data;
				
			}catch(\PDOexection $e){
				print("Error!: ".$e);
				die();
			}
		}

		public function getComprobacion($id_envio, $estado){
			if(preg_match_all("/^[0-9]{1,10}$/", $id_envio) != 1)
				return ['resultado' => 'error','error' => 'Id inválida.'];

			if(preg_match_all("/^[0-9]{1,10}$/", $estado) != 1)
				return ['resultado' => 'error','error' => 'Status inválido.'];

			$this->id_envio = $id_envio;
			$this->estado = $estado;

			return $this->asignarEstadoEnvio();
		}

		private function asignarEstadoEnvio(){
			try{
				$this->conectarDB();
 				
 				$sql = [
 					"1" => "UPDATE envio SET status = ?, fecha_entrega = NOW() WHERE id_envio = ?;",
 					"2" => "UPDATE envio SET status = ?, fecha_envio = NOW() WHERE id_envio = ?;",
 					"3" => "UPDATE envio SET status = ?, fecha_envio = NULL, fecha_entrega = NULL WHERE id_envio = ?;"
 				];

				$new = $this->con->prepare($sql[$this->estado]);
				$new->bindValue(1, $this->estado);
				$new->bindValue(2, $this->id_envio);
				$resultado;
				if(!$new->execute()){
					$resultado = ['resultado' => 'error', 'msg' => 'Ha ocurrido un error en la base de datos.'];
				}

				$this->binnacle("Envios",$_SESSION['cedula'],"Modificó estado del envío #$this->id_envio.");
				$resultado = ['resultado' => 'ok', 'msg' => "Se ha actualizado el estado del envio correctamente."];
				$this->desconectarDB();
				return $resultado;

			}catch(\PDOexection $e){
				print("Error!: ".$e);
				die();
			}

		}


		public function calcularPrecioEnvio(){

			$url = "http://agencias.com.ve/sys/ajax.php";

			$body = [
				"courier" => "MRW", 
				"cmd" => "calculo_tarifa", 
				"origen" => "",
				"destino" => "",
				"formapago" => "enorigen",
				"tipoenvio" => "age",
				"peso" => "1",
				"size_x" => "15",
				"size_y" => "15",
				"size_z" => "5",
				"valorseguro" => ""
			];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded;charset=UTF-8'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);

			if($response === false) {
				$resultado = ['resultado' => 'error', 'msg' => "No se ha podido conseguir el precio de envío."];
				die(json_encode($resultado));
			}

			curl_close($ch);

			$dom = new  \DOMDocument();
			$dom->loadHTML($response);

			$padre = $dom->getElementsByTagName('tbody')->item(0);
			$hijo = $padre->getElementsByTagName('tr')->item(4);

			$html = $dom->saveHTML($hijo);

			$textoCompleto = $dom->saveHTML($hijo);

			preg_match('/Bs\.\s*([\d\.]+)/', $textoCompleto, $matches);
			$total_envio = $matches[0];

			$precio = number_format(floatval(str_replace('Bs. ', '', $total_envio)), 2);

			$resultado = ['resultado' => 'ok','precio_solo' => $precio, 'precio_bs' => "$total_envio"];
			return $resultado;

		}


	}

?>