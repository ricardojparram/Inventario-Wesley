<?php 

namespace modelo;

Use config\connect\DBConnect as DBConnect;

class clase extends DBConnect{

	private $clase;
	private $id;
	private $idEdit;
	
	public function __construct(){
        parent::__construct();
    }

    public function getAgregarClase($clase){
    	if(preg_match_all("/^[a-zA-ZÀ-ÿ]{3,30}$/", $clase) == false){
            $resultado = ['resultado' => 'Error de nombre' , 'error' => 'Nombre inválido.'];
            echo json_encode($resultado);
            die();
        }

        $this->clase = $clase;

        $this->agregarClase();
    }

    private function agregarClase(){
    	try {
            parent::conectarDB();
    		$new = $this->con->prepare("INSERT INTO `clase`(`id_clase`, `nombre_c`, `status`) VALUES (DEFAULT ,?,1)");
    		$new->bindValue(1, $this->clase);
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

    public function mostrarClase($bitacora = false){
    	try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT c.id_clase, c.nombre_c  FROM  clase c  WHERE c.status = 1");
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

    	$this->eliminarClase();
    }

    private function eliminarClase(){
    	try {
            parent::conectarDB();
    		$new = $this->con->prepare("UPDATE clase SET status = 0 WHERE id_clase = ?");
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
    		$new = $this->con->prepare("SELECT * FROM clase WHERE id_clase = ?");
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

    public function getEditarClase($clase, $id){
    	if(preg_match_all("/^[a-zA-ZÀ-ÿ]{3,30}$/", $clase) == false){
            $resultado = ['resultado' => 'Error de nombre' , 'error' => 'Nombre inválido.'];
            
        }

        $this->clase = $clase;
        $this->idEdit = $id;

        return $this->editarClase();
    }

    private function editarClase(){
    	try {
            parent::conectarDB();
    		$new = $this->con->prepare("UPDATE `clase` SET `nombre_c` = ?  WHERE  id_clase = ?");
    		$new->bindValue(1, $this->clase);
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