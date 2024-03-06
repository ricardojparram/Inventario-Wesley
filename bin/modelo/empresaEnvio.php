<?php

namespace modelo;
use config\connect\DBConnect as DBConnect;

class empresaEnvio extends DBConnect{

	private $id;
	private $rif;
	private $nombre;
	private $contacto;


    public function mostrarEmpresas($bitacora = false){
    	try {
    	  parent::conectarDB();
    	  $sql = 'SELECT * FROM empresa_envio e WHERE e.status = 1';
          $new = $this->con->prepare($sql);
          $new->execute();
          $data = $new->fetchAll(\PDO::FETCH_OBJ);
          if($bitacora) $this->binnacle("Empresa de envio",$_SESSION['cedula'],"Consultó listado.");
          parent::desconectarDB();
          return $data;

    	} catch (\PDOException $e) {
    		return $e;
    	}
    }

    public function validarRif($rif, $id){
    	if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1){
    		return['resultado' => 'Error de rif','error' => 'Rif inválido.'];
    	}

    	$this->rif = $rif;

    	if ($id != null) {
    		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
    			return['resultado' => 'Error de id','error' => 'Id inválida.'];
    		}
            $this->id = $id;

    		return $this->vRifEdit();

    	}else{

    		return $this->vRif();

    	}

    }

    private function vRif(){
    	try {
    		parent::conectarDB();
    		$new = $this->con->prepare('SELECT * FROM empresa_envio e WHERE e.status = 1 and e.rif = ? ');
    		$new->bindValue(1, $this->rif);
    		$new->execute();
    		$data = $new->fetchAll();
            parent::desconectarDB();
    		if(isset($data[0]['rif'])) {
    			return['resultado' => 'Error Datos', 'error' => 'El rif ya está registrado.'];
    		}else{
    			return['resultado' => 'Datos validos.'];
    		}

    	} catch (\PDOException $e) {
    		return $e;
    	}
    }

    private function vRifEdit(){
    	try {
    		parent::conectarDB();
    		$new = $this->con->prepare('SELECT * FROM empresa_envio e WHERE e.status = 1 and e.rif = ? and e.id_empresa != ?');
    		$new->bindValue(1, $this->rif);
    		$new->bindValue(2, $this->id);
    		$new->execute();
    		$data = $new->fetchAll();
            parent::desconectarDB();

    		if(isset($data[0]['rif'])) {
    			return['resultado' => 'Error Datos', 'error' => 'El rif ya está registrado.'];
    		}else{
    			return['resultado' => 'Datos validos.'];
    		}

    	} catch (\PDOException $e) {
    		return $e;
    	}
    }

    public function getRegistrarEmpresa($rif, $nombre, $contacto){
    	if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1){
    		return['resultado' => 'Error de rif','error' => 'Rif inválido.'];
    	}
    	if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s,.-]){3,50}$/", $nombre) !== 1){
			return['resultado' => 'Error de nombre','error' => 'nombre inválida.'];
		}
		if($contacto != null) {
			if(preg_match_all("/^[0-9]{10,30}$/", $contacto) != 1){
				return['resultado' => 'Error de telefono','error' => 'Telefono inválido.'];
			}
		}

		$this->rif = $rif;
		$this->nombre = $nombre;
		$this->contacto = $contacto;

		$validarRif = $this->vRif();
		if($validarRif['resultado'] == 'Error Datos') return['resultado' => 'Error Datos', 'error' => 'El rif ya está registrado.'];
		return $this->registrarEmpresa();

    }

    private function registrarEmpresa(){
    	try {
    	parent::conectarDB();
    	$new = $this->con->prepare('INSERT INTO `empresa_envio`(`id_empresa`, `rif`, `nombre`, `contacto`, `status`) VALUES (DEFAULT,?,?,?,1)');
    	$new->bindValue(1, $this->rif);
    	$new->bindValue(2, $this->nombre);
    	$new->bindValue(3, $this->contacto);
    	$new->execute();
    	$data = $new->fetchAll();
    	$resultado = ['resultado' => 'Empresa registrado.'];
        $this->binnacle("Empresa de envío",$_SESSION['cedula'],"Registró empresa de envío .");
        parent::desconectarDB();
		return $resultado;

    	} catch (\PDOException $e) {
    		return $e;
    	}
    }

    	public function validarSelect($id){

		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
			return['resultado' => 'Error de id','error' => 'id inválida.'];
		}

		$this->id = $id;
        parent::conectarDB();

		$new = $this->con->prepare("SELECT * FROM empresa_envio e WHERE e.status = 1 AND e.id_empresa = ?");
		$new->bindValue(1, $this->id);
		$new->execute();
		$data = $new->fetchAll();
        parent::desconectarDB();

		if(isset($data[0]["id_empresa"])){
			return['resultado' => 'Si existe esa empresa.'];
			
		}else{
			return['resultado' => 'Error de empresa'];
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
			$new = $this->con->prepare("SELECT * FROM empresa_envio e WHERE e.status = 1 AND e.id_empresa = ?");
			$new->bindValue(1, $this->id);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);
			parent::desconectarDB();
			return $data;

		}catch (\PDOException $e) {
			return $e;
		}
	} 

	public function getEditarEmpresa($rifEdit, $nameEdit, $contactoEdit ,$id){
		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
			return['resultado' => 'Error de id','error' => 'id inválida.'];
		}
		if(preg_match_all("/^[0-9]{7,10}$/", $rifEdit) != 1){
			return['resultado' => 'Error de rif','error' => 'Rif inválido.'];
		}
		if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s,.-]){3,50}$/", $nameEdit) !== 1){
			return['resultado' => 'Error de nombre','error' => 'nombre inválida.'];
		}
		if($contactoEdit != null) {
			if(preg_match_all("/^[0-9]{10,30}$/", $contactoEdit) != 1){
				return['resultado' => 'Error de telefono','error' => 'Telefono inválido.'];
			}
		}

		$this->rif = $rifEdit;
		$this->nombre = $nameEdit;
		$this->contacto = $contactoEdit;
		$this->id = $id;
		$validarRif = $this->vRifEdit();
		if($validarRif['resultado'] == 'Error Datos') return['resultado' => 'Error Datos', 'error' => 'El rif ya está registrado.'];
		
        return $this->editarEmpresa();

	}

	private function editarEmpresa(){
		try {
			parent::conectarDB();
			$new = $this->con->prepare('UPDATE empresa_envio e SET `rif`= ?,`nombre`= ? ,`contacto`= ? WHERE e.status = 1 AND e.id_empresa = ?');
			$new->bindValue(1, $this->rif);
			$new->bindValue(2, $this->nombre);
			$new->bindValue(3, $this->contacto);
			$new->bindValue(4, $this->id);
			$new->execute();
			$data = $new->fetchAll();
			$resultado = ['resultado' => 'Empresa editado.'];
			$this->binnacle("Empresa de envío",$_SESSION['cedula'],"Editó empresa de envío .");
			parent::desconectarDB();
            return $resultado;

		} catch (\PDOException $e) {
			return $e;
		}

	}

	public function getEliminarEmpresa($id){
		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
			return['resultado' => 'Error de id','error' => 'id inválida.'];
		}

		$this->id = $id;

		$validarId = $this->validarSelect($id);
		if($validarId['resultado'] == 'Error de empresa') return['resultado' => 'Error de empresa'];
		return $this->eliminarEmpresa();

	}

	private function eliminarEmpresa(){
		try{
        parent::conectarDB();
		$new = $this->con->prepare("UPDATE empresa_envio e SET `status`= 0 WHERE e.status = 1 AND e.id_empresa = ?");
		$new->bindValue(1, $this->id);
		$new->execute();
		$this->binnacle("Empresa de envío",$_SESSION['cedula'],"Eliminó empresa de envío .");
		$resultado = ['resultado' => 'Empresa eliminada.'];
		parent::desconectarDB();
		return $resultado;


		} catch (\PDOException $e) {
			return $e;
		}
	}

}

 ?>