<?php  

namespace modelo;
use config\connect\DBConnect as DBConnect;

class tipoEmpleado extends DBConnect{

	private $tipoEmpleado;
	private $id;

	public function getMostrarEmpleado($bitacora = false){
		try {
		parent::conectarDB();

		$query = 'SELECT te.tipo_em , te.nombre_e FROM tipo_empleado te WHERE te.status = 1';
		$new = $this->con->prepare($query);
		$new->execute();
		$data = $new->fetchAll(\PDO::FETCH_OBJ);

		parent::desconectarDB();

		return $data;

		} catch (\PDOException $e) {
			return $e;
		}
	}

	 public function validarTipoEmpleado($tipoEmpleado , $id){
      if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){3,30}$/', $tipoEmpleado) != 1){
          return['resultado'=> 'error de metodo', 'error'=>'metodo invalido'];         
      }

      $this->id = ($id === 'false') ? false : $id;
      $this->tipoEmpleado = $tipoEmpleado;

      return $this->validTipoEmpleado();

     }

     private function validTipoEmpleado(){
      try {
        parent::conectarDB();
        if($this->id === false) {
          $new = $this->con->prepare('SELECT te.tipo_em FROM tipo_empleado te WHERE te.status = 1 AND te.nombre_e = ?');
          $new->bindValue(1, $this->tipoEmpleado);
        }else{
          $new = $this->con->prepare('SELECT te.tipo_em FROM tipo_empleado te WHERE te.status = 1 AND te.nombre_e = ? AND te.tipo_em != ?');
          $new->bindValue(1, $this->tipoEmpleado);
          $new->bindValue(2, $this->id);
        }

        $new->execute();
        $data = $new->fetchAll();

        if(isset($data[0]['tipo_em'])) {
          $resultado = ['resultado' => 'error', 'msg' => 'El tipo empleado ya está registrado.', 'res' => false];
        }else{
          $resultado = ['resultado' => 'empleado valido', 'res' => true];
        }

        parent::desconectarDB();
        return $resultado;
        
      } catch (\PDOException $e) {
        return $e;
      }
     }

	public function getRegistrarEmpleado($tipoEmpleado){
		if (preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){3,30}$/', $tipoEmpleado) != 1) {
			return['resultado' => 'Error de tipo empleado', 'error' => 'tipo empleado invalido.'];
		}

		$this->tipoEmpleado = $tipoEmpleado;
        $this->id = false;
        $validarTipoEmpleado = $this->validTipoEmpleado();

        if ($validarTipoEmpleado['res'] === false) return ['resultado' => 'error', 'msg' => 'El tipo empleado ya está registrado.'];

		return $this->registrarEmpleado();
  	}

  	private function registrarEmpleado(){
  		try {
  		parent::conectarDB();

        do{
        $pk = $this->uniqueNumericID();
        $check = $this->con->prepare("SELECT COUNT(*) FROM `tipo_empleado` WHERE `tipo_em` = ?");
        $check->bindValue(1, $pk);
        $check->execute();
        $count = $check->fetchColumn();
        }while($count > 0);

  		$new = $this->con->prepare('INSERT INTO `tipo_empleado`(`tipo_em`, `nombre_e`, `status`) VALUES (? ,? ,1 )');
  		$new->bindValue(1, $pk);
  		$new->bindValue(2, $this->tipoEmpleado);
  		$new->execute();
  		$data = $new->fetchAll();

  		$resultado = ["resultado" => "registrado correctamente"];
        parent::desconectarDB();

        return $resultado;

  		} catch (\PDOException $e) {
  			return $e;
  		}
  	}

  	public function validarSelect($id){
  		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
  			return ['resultado' => 'Error de id','error' => 'id inválida.'];
  		}

  		$this->id = $id;

  		return $this->validSelect();

  	}

  	private function validSelect(){
  		try {
  			parent::conectarDB();
  			$new = $this->con->prepare('SELECT te.nombre_e FROM tipo_empleado te WHERE te.status = 1 AND te.tipo_em = ?');
  			$new->bindValue(1,  $this->id);
  			$new->execute();
  			$data = $new->fetchAll();

  			parent::desconectarDB();

  			if(isset($data[0]["nombre_e"])){
  				return['resultado' => 'Si existe este tipo de empleado.'];
  			}else{
  				return['resultado' => 'Error de empleado'];
  			}
  		} catch (\PDOException $e) {
  			return $e;
  		}
  	}

  	public function getMostrarEdit($id){
  	 if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
        return ['resultado' => 'Error de id','error' => 'id inválida.'];
      }
      $this->id = $id;

      return $this->mostrarEdit();
  	}

  	private function mostrarEdit(){
  		try{
  			parent::conectarDB();
  			$new = $this->con->prepare("SELECT te.nombre_e FROM tipo_empleado te WHERE te.status = 1 AND te.tipo_em = ?");
  			$new->bindValue(1, $this->id);
  			$new->execute();
  			$data = $new->fetchAll(\PDO::FETCH_OBJ);
  			parent::desconectarDB();
  			return $data;
  		}catch (\PDOexception $error) {
  			return $error;
  		}
  	}

  	public function getEditarEmpleado($tipoEmpleadoEdit , $id){
  		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
  			return ['resultado' => 'Error de id','error' => 'id inválida.'];
  		}
  		if (preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){3,30}$/', $tipoEmpleadoEdit) != 1) {
  			return['resultado' => 'Error de tipo empleado', 'error' => 'tipo empleado invalido.'];
  		}

  		$this->tipoEmpleado = $tipoEmpleadoEdit;
  		$this->id = $id;

  		$validarTipoEmpleado = $this->validTipoEmpleado();
  		if ($validarTipoEmpleado['res'] === false) return ['resultado' => 'error', 'msg' => 'El tipo empleado ya está registrado.'];

  		return $this->editarEmpleado();

  	}

  	private function editarEmpleado(){
  		try {
  			parent::conectarDB();
  			$new = $this->con->prepare("UPDATE tipo_empleado te SET te.nombre_e = ? WHERE te.status = 1 AND te.tipo_em = ?");
  			$new->bindValue(1, $this->tipoEmpleado);
  			$new->bindValue(2,$this->id);
  			$new->execute();

  			$resultado = ['resultado'=> 'Editado'];

  			parent::desconectarDB();
  			return $resultado;

  		} catch (\PDOException $e) {
  			return $e;
  		}
  	}

  	public function getEliminarEmpleado($id){
  		if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
  			return ['resultado' => 'Error de id','error' => 'id inválida.'];
  		}

  		$this->id = $id;

  		return $this->eliminarEmpleado();
  	}

  	private function eliminarEmpleado(){
  	 try {
     parent::conectarDB();

  	 $new = $this->con->prepare('UPDATE tipo_empleado te SET te.status = 0 WHERE te.status = 1 AND te.tipo_em = ?');
  	 $new->bindValue(1, $this->id);
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