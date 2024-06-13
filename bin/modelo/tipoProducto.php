<?php 

namespace modelo;

Use config\connect\DBConnect as DBConnect;

class tipoProducto extends DBConnect{

	private $tipoProducto;
	private $id;
	private $idEdit;
	
	public function __construct(){
        parent::__construct();
    }

    public function validarTipo($tipoProducto, $id){
        if(preg_match_all("/^[a-zA-ZÀ-ÿ]{3,30}$/", $tipoProducto) == false){
            return['resultado'=> 'error de tipo de producto', 'error'=>'tipo de producto invalido'];
        }
    
        $this->id = ($id ==='false')? false :$id;
        $this->tipoProducto = $tipoProducto;
    
        return $this->validTipo();
    }
    
    private function validTipo(){

        try {
            parent::conectarDB();
            if($this->id === false) {
                $new = $this->con->prepare('SELECT p.nombrepro  FROM tipo_producto p WHERE p.status = 1 AND p.nombrepro = ?');
                $new->bindValue(1,$this->tipoProducto);
            }else{
                $new = $this->con->prepare('SELECT p.nombrepro  FROM tipo_producto p WHERE p.status = 1 AND p.nombrepro = ? AND p.id_tipoprod !=?');
                $new->bindValue(1, $this->$tipoProducto);
                $new->bindValue(2, $this->id);
            }

            $new->execute();
			$data = $new->fetchAll();
	
			if(isset($data[0]['nombrepro'])) {
			  $resultado = ['resultado' => 'error', 'msg' => 'El tipo de producto ya esta registrado.', 'res' => false];
			}else{
			  $resultado = ['resultado' => 'Tipo de producto valido', 'res' => true];
			}
	
			parent::desconectarDB();
			return $resultado;
			
		  } catch (\PDOException $e) {
			return $e;
		  }
        }
    
        
   
    

    public function getAgregarTipoProducto($tipoProducto){
    	if(preg_match_all("/^[a-zA-ZÀ-ÿ]{3,30}$/", $tipoProducto) == false){
            $resultado = ['resultado' => 'Error de nombre' , 'error' => 'Nombre inválido.'];
        
            
        }

        $this->tipoProducto = $tipoProducto;

        $this->id = false;
        $validarTipo = $this->validTipo();
        if($validarTipo['res'] === false) return ['resultado' => 'error', 'msg' => 'El tipo de producto ya está registrado.']; 

        return $this->agregarTipoProducto();
    }

    private function agregarTipoProducto(){
    	try {
            parent::conectarDB();
    		$new = $this->con->prepare("INSERT INTO tipo_producto(id_tipoprod, nombrepro, status) VALUES (DEFAULT ,?,1)");
    		$new->bindValue(1, $this->tipoProducto);
            $new->execute();
            $data = $new->fetchAll();

            $resultado = ['resultado' => 'Registrado correctamente.'];
              echo json_encode($resultado);
               parent::desconectarDB();
              die();
    	} catch (\PDOException $error) {
    		return $error;
    	}
    }

    public function mostrarTipoProducto($bitacora = false){
    	try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT p.id_tipoprod , p.nombrepro   FROM tipo_producto p WHERE p.status = 1");
            $new->execute();
            $data = $new->fetchAll();
            echo json_encode($data);
            parent::desconectarDB();
            die();
    	} catch (\PDOException $error) {
    		return $error;
    		
    	}
    }

    public function getEliminar($id){
    	$this->id = $id;

    	$this->eliminarTipoProducto();
    }

    private function eliminarTipoProducto(){
    	try {
            parent::conectarDB();
    		$new = $this->con->prepare("UPDATE tipo_producto SET  status = 0  WHERE id_tipoprod = ?");
            $new->bindValue(1, $this->id);
            $new->execute();
            $resultado = ['resultado' => 'Eliminado'];
            echo json_encode($resultado);
            parent::desconectarDB();
            die();
    	} catch (\PDOException $error) {
    		return $error;
    	}
    }

    public function getItem($item){
    	$this->id = $item;

    	$this->item();
    }

    private function item(){
    	try {
            parent::conectarDB();
    		$new = $this->con->prepare("SELECT * FROM tipo_producto WHERE id_tipoprod = ?");
            $new->bindValue(1, $this->id);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            echo json_encode($data);
            parent::desconectarDB();
            die();
    	}catch (\PDOException $error) {
    		return $error;
    	}
    }

    public function getEditarTipoProducto($tipoProducto, $id){
    	if(preg_match_all("/^[a-zA-ZÀ-ÿ]{3,30}$/", $tipoProducto) == false){
            $resultado = ['resultado' => 'Error de nombre' , 'error' => 'Nombre inválido.'];
            
        }

        $this->tipoProducto = $tipoProducto;
        $this->idEdit = $id;

        return $this->editarTipoProducto();
    }

    private function editarTipoProducto(){
    	try {
            parent::conectarDB();
    		$new = $this->con->prepare("UPDATE tipo_producto SET nombrepro=  ? WHERE id_tipoprod =?");
    		$new->bindValue(1, $this->tipoProducto);
    		$new->bindValue(2, $this->idEdit);
            $new->execute();
            $data = $new->fetchAll();

            $resultado = ['resultado' => 'Editado correctamente.'];
              parent::desconectarDB();
    	} catch (\PDOException $error) {
    		return $error;
       } 
    }

}

?>