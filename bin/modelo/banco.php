<?php 

namespace modelo;

use config\connect\DBConnect as DBConnect;

class banco extends DBConnect{

    private $id;
	private $nombre;
	private $cedulaRif;
	private $tipoP;
	private $telefono;
	private $cuentaBank;
	private $codBank;


	public function mostrarBank($bitacora = false){

		try{
			parent::conectarDB();
			$sql = 'SELECT df.id_datos_cobro , df.rif_cedula, df.telefono , b.codigo , b.nombre FROM datos_cobro_farmacia df INNER JOIN banco b ON b.id_banco = df.id_banco WHERE df.status = 1';

			$new = $this->con->prepare($sql);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);
			if($bitacora) $this->binnacle("Banco",$_SESSION['cedula'],"Consultó listado.");
			parent::desconectarDB();
			return $data;

		}catch(\PDOException $e){
			die($e);
		}

	}

	public function datosBanco(){
		try{
			parent::conectarDB();
			
			$new = $this->con->prepare('SELECT b.id_banco, b.nombre, b.codigo, b.status FROM banco b WHERE b.status = 1');
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);
			return $data;
			parent::desconectarDB();


		}catch(\PDOException $e){
			die($e);
		}
	}

	public function SelecTipoPago(){
		try{
        	parent::conectarDB();
			$sql = 'SELECT tp.id_tipo_pago, tp.des_tipo_pago , tp.online FROM tipo_pago tp WHERE tp.online = 1 and tp.status = 1';

			$new = $this->con->prepare($sql);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);
			parent::desconectarDB();
			return $data;

		}catch(\PDOException $e){
			die($e);
		}

	}

	public function validarTipoP($id){
     
		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
			return['resultado' => 'Error de id','error' => 'Id inválida.'];	
		}

        $this->id = $id;

        return $this->validarT();
	}

	private function validarT(){

		try{
			parent::conectarDB();
			$new = $this->con->prepare("SELECT * FROM tipo_pago tp WHERE tp.online = 1 and tp.status = 1 and tp.id_tipo_pago = ?");
			$new->bindValue(1, $this->id);
			$new->execute();
			$data = $new->fetchAll();

			parent::desconectarDB();

			if(isset($data[0]['id_tipo_pago'])) {
				return['resultado' => 'Codigo tipo válido.'];
			}else{
				return['resultado' => 'Error de tipoP', 'error' => 'El tipo pago no está registrado.'];
			}

		}catch(\PDOException $e){
			die($e);
		}
	}

	public function ValidarDatos($tipoP, $tipo ,$cedulaRif , $nombre ,$id){
		if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s,.-]){3,50}$/", $tipoP) != 1){
			return['resultado' => 'Error de Tipo pago','error' => 'Tipo pago inválida.'];
		}

		if(preg_match_all("/^(?=.*[0-9])(?=.*[-])[0-9-]{1,25}$|^[0-9]{3,50}$/", $tipo) !== 1){
			return['resultado' => 'Error telefono o cuenta','error' => 'Tipo pago inválida.'];
		}

		if(preg_match_all("/^[0-9]{7,10}$/", $cedulaRif) != 1) {
			return['resultado' => 'Error de rif o cedula','error' => 'Rif o cedula inválido.'];
		}
		if (preg_match_all("/^[0-9]{1,10}$/", $nombre) != 1) {
			return['resultado' => 'Error de id banco','error' => 'id banco inválido.'];
		}

		$this->tipoP = $tipoP;
		$this->nombre = $tipo;
		$this->cedulaRif = $cedulaRif;
		$this->codBank = $nombre;

		if ($id != null) {
		  
		  $this->id = $id;

		 return $this->validDatosEdit();

		}else{

		 return $this->validDatos();

		}


	}

	private function validDatos(){
		try {
			parent::conectarDB();
			$query = "SELECT * FROM (SELECT 'Pago movil' as  tipo_pago, id_datos_cobro as id, rif_cedula, telefono as tipo, id_banco as banco FROM datos_cobro_farmacia
				WHERE telefono IS NOT NULL
				UNION 
				SELECT 'Transferencia' as tipo_pago, id_datos_cobro, rif_cedula, num_cuenta, id_banco as banco FROM datos_cobro_farmacia
				WHERE num_cuenta IS NOT NULL
				) as detalle
				WHERE detalle.tipo_pago = ? AND detalle.tipo = ? AND detalle.rif_cedula = ? AND detalle.banco = ?";

			$new = $this->con->prepare($query);
			$new->bindValue(1, $this->tipoP);
			$new->bindValue(2, $this->nombre);
			$new->bindValue(3, $this->cedulaRif);
			$new->bindValue(4, $this->codBank);
			$new->execute();
			$data = $new->fetchAll();

			parent::desconectarDB();
            
			if (isset($data[0])) {
				return['resultado' => 'Error Datos', 'error' => 'Cedula ya registrada al mismo tipo de pago y banco'];
			}else{
				return['resultado' => 'Datos validos'];
			}
		} catch (\PDOException $e) {
			die($e);
		}

	}

	private function validDatosEdit(){
		try {
		parent::conectarDB();
		$query = "SELECT * FROM (SELECT 'Pago movil' as  tipo_pago, id_datos_cobro as id, rif_cedula, telefono as tipo, id_banco as banco FROM        	  datos_cobro_farmacia
				WHERE telefono IS NOT NULL
				UNION 
				SELECT 'Transferencia' as tipo_pago, id_datos_cobro, rif_cedula, num_cuenta, id_banco as banco FROM datos_cobro_farmacia
				WHERE num_cuenta IS NOT NULL
				) as detalle
				WHERE detalle.tipo_pago = ? AND detalle.tipo = ? AND detalle.rif_cedula = ? AND detalle.banco = ? AND detalle.id != ?";

		$new = $this->con->prepare($query);
		$new->bindValue(1, $this->tipoP);
		$new->bindValue(2, $this->nombre);
		$new->bindValue(3, $this->cedulaRif);
		$new->bindValue(4, $this->codBank);
		$new->bindValue(5, $this->id);
		$new->execute();
		$data = $new->fetchAll();

		parent::desconectarDB();

		if (isset($data[0])) {
			return['resultado' => 'Error Datos', 'error' => 'Cedula ya registrada al mismo tipo de pago y banco'];
		}else{
			return['resultado' => 'Datos validos'];
		}

		} catch (\PDOException $e) {
			die($e);
		}
	}

	public function getRegistrarBanco($datos){

		$datosA = $datos;


		switch ($datosA[0]) {

			case 'Transferencia' :


			if(preg_match_all("/^[0-9]{1,10}$/", $datosA[1]) !== 1){
				return['resultado' => 'Error de Nombre banco','error' => 'Nombre banco inválida.'];
			}

			if(preg_match_all("/^[0-9]{7,10}$/", $datosA[2]) != 1) {
				return['resultado' => 'Error de rif o cedula','error' => 'Rif o cedula inválido.'];
			}
			if(preg_match_all("/^(?=.*[0-9])(?=.*[-])[0-9-]{1,25}$/", $datosA[3]) != 1){
				return['resultado' => 'Error de cuenta de Banco','error' => 'Cuenta de Banco inválida.'];
			}

			$this->nombre = $datosA[1];
			$this->cedulaRif = $datosA[2];
			$this->cuentaBank = $datosA[3];
			$validarDatos = $this->ValidarDatos($datosA[0], $this->cuentaBank ,$this->cedulaRif , $this->nombre , null);
			if($validarDatos['resultado'] == 'Error Datos') return['resultado' => 'Error Datos', 'error' => 'Cedula ya registrada al mismo tipo de pago y banco'];

            return $this->registrarBanco();
			break;

			case 'Pago movil' :
			

			if(preg_match_all("/^[0-9]{1,10}$/", $datosA[1]) !== 1){
				return['resultado' => 'Error de Nombre banco','error' => 'Nombre banco inválida.'];
			}

			if(preg_match_all("/^[0-9]{7,10}$/", $datosA[2]) != 1) {
				return['resultado' => 'Error de rif o cedula','error' => 'Rif o cedula inválido.'];
			}

			if(preg_match_all("/^[0-9]{10,30}$/", $datosA[3]) != 1){
				return['resultado' => 'Error de telefono','error' => 'telefono inválida.'];
			}
            
			$this->nombre = $datosA[1];
			$this->cedulaRif = $datosA[2];
			$this->telefono = $datosA[3];

			$validarDatos = $this->ValidarDatos($datosA[0], $this->telefono ,$this->cedulaRif , $this->nombre , null);
			if($validarDatos['resultado'] == 'Error Datos') return['resultado' => 'Error Datos', 'error' => 'Cedula ya registrada al mismo tipo de pago y banco'];

            return $this->registrarBanco();
			break;

			default:
			return "Error";
			break;
		} 

	}

	private function registrarBanco(){

		try {
		parent::conectarDB();
        $new = $this->con->prepare("INSERT INTO `datos_cobro_farmacia`(`id_datos_cobro`, `num_cuenta`, `rif_cedula`, `telefono`, `id_banco`, `status`) VALUES (default, ? , ? , ? , ? , 1)");
        $new->bindValue(1 ,$this->cuentaBank);
        $new->bindValue(2 ,$this->cedulaRif);
        $new->bindValue(3 ,$this->telefono);
        $new->bindValue(4 ,$this->nombre);

        $new->execute();
        $data = $new->fetchAll(); 
        $resultado = ['resultado' => 'banco registrado.'];
        $this->binnacle("Banco",$_SESSION['cedula'],"Registró un Banco .");
        parent::desconectarDB();
        return $resultado;
		} catch (\PDOException $e) {
			die($e);
		}

	}

	public function validarSelect($id){

		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
			return['resultado' => 'Error de id','error' => 'id inválida.'];
		}

		$this->id = $id;
        parent::conectarDB();
		$new = $this->con->prepare("SELECT * FROM datos_cobro_farmacia df WHERE df.id_datos_cobro = ? AND df.status = 1");
		$new->bindValue(1, $this->id);
		$new->execute();
		$data = $new->fetchAll();
		parent::desconectarDB();

		if(isset($data[0]["id_banco"])){
			return['resultado' => 'Si existe esa banco.'];
		}else{
			return['resultado' => 'Error de banco'];
		}

	}

	public function rellenarEdit($id){

		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
			return['resultado' => 'Error de id','error' => 'id inválida.'];
		}

        $this->id = $id;

        return $this->selectItem();

	}

	private function selectItem(){

		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT * FROM datos_cobro_farmacia df WHERE df.status = 1 and df.id_datos_cobro = ?");
			$new->bindValue(1, $this->id);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);
			parent::desconectarDB();
			return $data;

		}catch (\PDOException $e) {
			die($e);
		}
	}

	public function getEditarBanco($datos , $id){
		
		$datosA = $datos;

		switch ($datosA[0]) {

			case 'Transferencia' :

            if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
				return['resultado' => 'Error de id','error' => 'id inválida.'];
			}

			if(preg_match_all("/^[0-9]{1,10}$/", $datosA[1]) !== 1){
				return['resultado' => 'Error de Nombre banco','error' => 'Nombre banco inválida.'];
			}

			if(preg_match_all("/^[0-9]{7,10}$/", $datosA[2]) != 1) {
				return['resultado' => 'Error de rif o cedula','error' => 'Rif o cedula inválido.'];
			}
			if(preg_match_all("/^(?=.*[0-9])(?=.*[-])[0-9-]{1,25}$/", $datosA[3]) != 1){
				return['resultado' => 'Error de cuenta de Banco','error' => 'Cuenta de Banco inválida.'];
			}
            
            $this->id = $id;
			$this->nombre = $datosA[1];
			$this->cedulaRif = $datosA[2];
			$this->cuentaBank = $datosA[3];

			$validarDatos = $this->ValidarDatos($datosA[0], $this->cuentaBank ,$this->cedulaRif , $this->nombre , $this->id);
			if($validarDatos['resultado'] == 'Error Datos') return['resultado' => 'Error Datos', 'error' => 'Cedula ya registrada al mismo tipo de pago y banco'];

            return $this->editarBanco();
			break;

			case 'Pago movil' :

			 if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
				return['resultado' => 'Error de Tipo pago','error' => 'Tipo pago inválida.'];
			}

			if(preg_match_all("/^[0-9]{1,10}$/", $datosA[1]) !== 1){
				return['resultado' => 'Error de Nombre banco','error' => 'Nombre banco inválida.'];
			}

			if(preg_match_all("/^[0-9]{7,10}$/", $datosA[2]) != 1) {
				return['resultado' => 'Error de rif o cedula','error' => 'Rif o cedula inválido.'];
			}
			if(preg_match_all("/^[0-9]{10,30}$/", $datosA[3]) != 1){
				return['resultado' => 'Error de telefono','error' => 'telefono inválida.'];
			}
            
            $this->id = $id;
			$this->nombre = $datosA[1];
			$this->cedulaRif = $datosA[2];
			$this->telefono = $datosA[3];

			$validarDatos = $this->ValidarDatos($datosA[0], $this->telefono ,$this->cedulaRif , $this->nombre , $this->id);
			if($validarDatos['resultado'] == 'Error Datos') return['resultado' => 'Error Datos', 'error' => 'Cedula ya registrada al mismo tipo de pago y banco'];

            return $this->editarBanco();
			break;

			default:
			return "Error Edit";
			break;
		} 
	}

    private function editarBanco(){
       try {
       	parent::conectarDB();
       	$new = $this->con->prepare("UPDATE datos_cobro_farmacia df SET `num_cuenta`= ? ,`rif_cedula`= ? ,`telefono`= ?,`id_banco`= ? WHERE df.status = 1 AND df.id_datos_cobro  = ?");

       	$new->bindValue(1 ,$this->cuentaBank);
       	$new->bindValue(2 ,$this->cedulaRif);
        $new->bindValue(3 ,$this->telefono);
       	$new->bindValue(4 ,$this->nombre);
       	$new->bindValue(5 ,$this->id);
       	$new->execute();
       	$data = $new->fetchAll();

       	$resultado = ['resultado' => 'banco editado.'];
       	$this->binnacle("Banco",$_SESSION['cedula'],"Editó un Banco .");
       	parent::desconectarDB();
       	return $resultado;

       } catch (\PDOException $e) {
       	die($e);
       }

    }

    public function getEliminarBanco($id){
    	if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
    		return['resultado' => 'Error de banco','error' => 'banco inválida.'];
    	}

    	$this->id = $id;
		$validarId = $this->validarSelect($id);
		if($validarId['resultado'] == 'Error de banco') return['resultado' => 'Error de banco'];

    	return $this->eliminarBanco();

    }

    private function eliminarBanco(){
    	try {
        parent::conectarDB();
    	$new = $this->con->prepare("UPDATE datos_cobro_farmacia df SET df.status = 0 WHERE df.id_datos_cobro = ?");
    	$new->bindValue(1, $this->id);
    	$new->execute();
    	$data = $new->fetchAll();
        $resultado = ['resultado' => 'Eliminado'];
        $this->binnacle("Banco",$_SESSION['cedula'],"Eliminó un Banco .");
        parent::desconectarDB();
        return $resultado;
	
    	} catch (\PDOException $e) {
    		die($e);	
    	}
    }


}

?>