<?php 
   
    namespace modelo;
    use config\connect\DBConnect as DBConnect;

    class presentacion extends DBConnect{

      private $cod_pres;
      private $medida;
      private $cantidad;
      private $peso;

      public function __construct(){
        parent::__construct();

      }


      public function mostrarPresentacionAjax(){

      try {
            parent::conectarDB();
        $query = "SELECT p.cod_pres, p.cantidad, m.nombre, p.peso FROM presentacion p, medida m WHERE p.status = 1 and  p.id_medida = m.id_medida ";
            $new = $this->con->prepare($query);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            echo json_encode($data);
            parent::desconectarDB();
            die();
      } catch (\PDOException $error) {
        return $error;
        
      }
      }

       public function mostrarMedida(){
        try{
      parent::conectarDB();
      $new = $this->con->prepare("SELECT * FROM medida m  WHERE m.status = 1");
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;

    }catch(\PDOexection $error){

     return $error;   
   } 
  } 


      public function getDatosPres($medida,$cantidad,$peso){

        $this->medida = $medida;
        $this->cantidad = $cantidad;
        $this->peso = $peso;

        return $this->registrarPres();

      }

      private function registrarPres(){


        try{
              $this->conectarDB();
              $new = $this->con->prepare("INSERT INTO presentacion(cod_pres,id_medida,cantidad,peso,status) VALUES(DEFAULT,?,?,?,1)");
              
              $new->bindValue(1, $this->medida); 
              $new->bindValue(3, $this->cantidad); 
              $new->bindValue(2, $this->peso); 
              $new->execute();
              $this->desconectarDB();

              return ("La presentacion ha sido registrada");
            

       }catch(\PDOException $error){
         return $error;
        }  
      }

  public function getPres($id){
      $this->id = $id;

      $this->selectPres();
    }

    private function selectPres(){

      try{
        $this->conectarDB();
        $new = $this->con->prepare("SELECT * FROM presentacion WHERE status = 1 and cod_pres = ? ;");
        $new->bindValue(1, $this->id);
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);
        $this->desconectarDB();
        echo json_encode($data);

        die();

        }catch(\PDOException $e){ 
          return $e;
        }

    }

   public function getEditar($medida, $cantidad, $peso, $id){

   

        $this->medida= $medida;
        $this->cantidad = $cantidad;
        $this->peso = $peso;
        $this->idedit = $id;

        $this->editarPresentacion();
    }

    private function editarPresentacion(){

        try{
            $this->conectarDB();
            $new = $this->con->prepare("UPDATE presentacion SET id_medida = ?, cantidad= ?, peso= ? WHERE cod_pres= ?");
            $new->bindValue(1, $this->medida);
            $new->bindValue(2, $this->cantidad);
            $new->bindValue(3, $this->peso);
            $new->bindValue(4, $this->idedit);
            $new->execute();
            $resultado = ['resultado' => 'Editado'];
            $this->desconectarDB();
            echo json_encode($resultado);
            die();

        }catch(\PDOException $error){
            echo json_encode($error);
            die();
        } 

    } 


  public function getEliminar($id){
      
      $this->id = $id;

      $this->eliminarPres();
    }

    private function eliminarPres(){
      try{
        $this->conectarDB();
        $new = $this->con->prepare("
            UPDATE presentacion SET status = 0 WHERE cod_pres = ?; 
          ");
        $new->bindValue(1, $this->id);
        $new->execute();
        $resultado = ['resultado' => 'Eliminado'];
        $this->desconectarDB();
        echo json_encode($resultado);
        die();

      }catch(\PDOException $e){
        return $e;
      }

    }

  }

?>