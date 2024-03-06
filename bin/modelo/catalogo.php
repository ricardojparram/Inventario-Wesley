<?php  

	namespace modelo;
	use config\connect\DBConnect as DBConnect;

	class catalogo extends DBConnect{

		private $user;
		private $productos;
		private $cod_producto;
		private $cantidad;

		public function __construct(){
			parent::__construct();
		}

		public function mostrarCategorias(){
			try {
				
				 $this->conectarDB();
				 $new = $this->con->prepare("SELECT * FROM clase where des_clase != 'NO ASIGNADO';");
				 $new->execute();
				 $data = $new->fetchAll();
				 die(json_encode($data));

			} catch (\PDOException $e) {
				die($e);
			}
		}

		public function mostrarCatalogo(){
			try {

				$query='SELECT p.cod_producto, p.descripcion as nombre, p.descripcion, p.stock, p.p_venta, p.img, p.contraindicaciones, cl.des_clase, ps.cantidad as cantidad_pres, ps.medida as medida_pres, ps.peso as peso_pres FROM producto p
				        INNER JOIN clase cl ON cl.cod_clase = p.cod_clase
				        INNER JOIN presentacion ps ON ps.cod_pres = p.cod_pres
				        WHERE p.status = 1 and p.stock != 0;';


				$this->conectarDB();
				$new = $this->con->prepare($query); 
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				$this->desconectarDB();
				die(json_encode($data));

			} catch (\PDOException $e) {
				die($e);
			}
		}

	}

?>