<?php  

namespace modelo;
use config\connect\DBConnect as DBConnect;
use utils\validar;

class tipoEmpleado extends DBConnect{

	use validar;
	private $tipoEmpleado;
	private $id;

	public function getMostrarEmpleado($bitacora = false){
		try {
		parent::conectarDB();

		$query = 'SELECT te.tipo_em , te.nombre_e FROM tipo_empleado te WHERE te.status = 1';
		$new = $this->con->prepare($query);
		$new->execute();
		$data = $new->fetchAll(\PDO::FETCH_OBJ);

		if ($bitacora)
        $this->binnacle("Tipo Empleado", $_SESSION['cedula'], "Consultó listado tipo de empleado.");

		parent::desconectarDB();

		return $data;

		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}

	 public function validarTipoEmpleado($tipoEmpleado , $id){
	 if (!$this->validarString('string' , $tipoEmpleado))
		return $this->http_error(400, 'Tipo empleado invalido.');

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
		return $this->http_error(500, $e->getMessage());
      }
     }

	public function getRegistrarEmpleado($tipoEmpleado){
		if (!$this->validarString('string' , $tipoEmpleado))
		return $this->http_error(400, 'Tipo empleado invalido.');

		$this->tipoEmpleado = $tipoEmpleado;
        $this->id = false;
        $validarTipoEmpleado = $this->validTipoEmpleado();

        if ($validarTipoEmpleado['res'] === false) return ['resultado' => 'error', 'msg' => 'El tipo empleado ya está registrado.'];

		return $this->registrarEmpleado();
  	}

  	private function registrarEmpleado(){
  		try {
  		parent::conectarDB();
      
  		$new = $this->con->prepare('INSERT INTO `tipo_empleado`(`tipo_em`, `nombre_e`, `status`) VALUES (DEFAULT ,? ,1 )');
  		$new->bindValue(1, $this->tipoEmpleado);
  		$new->execute();
  		$data = $new->fetchAll();

  		$resultado = ["resultado" => "registrado correctamente"];
		$this->binnacle("Tipo Empleado", $_SESSION['cedula'], "Registró un tipo de empleado.");
        parent::desconectarDB();

        return $resultado;

  		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
  		}
  	}

  	public function validarExistencia($id){
		if (!$this->validarString('entero', $id))
		return $this->http_error(400, 'id tipo empleado inválido.');

  		$this->id = $id;

  		return $this->validExistencia();

  	}

  	private function validExistencia(){
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
			return $this->http_error(500, $e->getMessage());
  		}
  	}

  	public function getMostrarEdit($id){
	if (!$this->validarString('entero', $id))
		return $this->http_error(400, 'id tipo empleado inválido.');

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
  		}catch (\PDOexception $e) {
			return $this->http_error(500, $e->getMessage());
  		}
  	}

  	public function getEditarEmpleado($tipoEmpleadoEdit , $id){
		if (!$this->validarString('entero', $id))
		return $this->http_error(400, 'id tipo empleado inválido.');

  		if (!$this->validarString('string' , $tipoEmpleadoEdit))
		return $this->http_error(400, 'Tipo empleado invalido.');

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
			$this->binnacle("Tipo Empleado", $_SESSION['cedula'], "Editó un tipo de empleado.");

  			parent::desconectarDB();
  			return $resultado;

  		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
  		}
  	}

  	public function getEliminarEmpleado($id){
		if (!$this->validarString('entero', $id))
		return $this->http_error(400, 'id tipo empleado inválido.');

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
	 $this->binnacle("Tipo Empleado", $_SESSION['cedula'], "Eliminó un tipo de empleado");
  	 parent::desconectarDB();

  	 return $resultado;

  	 } catch (\PDOException $e) {
		return $this->http_error(500, $e->getMessage());
  	 }
  	}


}

?>