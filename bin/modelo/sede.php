<?php  

	namespace modelo;
	use config\connect\DBConnect as DBConnect;

	class sede extends DBConnect{

		private $telefono;
		private $direccion;
		private $id;
		private $nombre;

		public function __construct(){
			parent::__construct();    
		}

		
	}

?>