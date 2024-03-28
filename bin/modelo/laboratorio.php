<?php 
   
    namespace modelo;
    use config\connect\DBConnect as DBConnect;
   
    class laboratorio extends DBConnect{

      private $cod_lab;
      private $rif;
      private $direccion;
      private $razon;
      private $telefono;
      private $contacto;

      private $id;
      private $idedit;

      public function __construct(){
      	parent::__construct();

      }
 
      public function mostrarLaboratorios($bitacora){
        try{
          $this->conectarDB();
          $sql = "SELECT rif_laboratorio, razon_social, direccion FROM laboratorio 
                  WHERE status = 1;";
          $new = $this->con->prepare($sql);
          $new->execute();
          $data = $new->fetchAll(\PDO::FETCH_OBJ);
          if($bitacora == "true") $this->binnacle("Laboratorio",$_SESSION['cedula'],"Consultó listado.");
          $this->desconectarDB();
          return $data;

        }catch(\PDOException $e){
          return ['error' => $e->getMessage()];
        }
      } 

      public function getRegistrarLaboratorio($rif, $direccion, $razon){

        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1)
          return ['resultado' => 'error','msg' => 'Rif inválido.'];

        if(preg_match_all("/^[a-zA-ZÀ-ÿ]{5,30}$/", $razon) != 1)
          return ['resultado' => 'error','msg' => 'Nombre inválido.'];

        if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){7,160}$/', $direccion) != 1)
          return ['resultado' => 'error','msg' => 'Direccion inválida.'];

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;

        $this->idedit = false;
        $validarRif = $this->validarRif();
        if($validarRif['res']) return $validarRif;

        return $this->registrarLaboratorio();

      }

      private function registrarLaboratorio(){

        try{
          $this->conectarDB();
          $new = $this->con->prepare("INSERT INTO laboratorio(rif_laboratorio,direccion,razon_social,status) VALUES(?,?,?,1)");
          $new->bindValue(1, $this->rif); 
          $new->bindValue(2, $this->direccion); 
          $new->bindValue(3, $this->razon);
          if(!$new->execute())
            return ['resultado' => 'error', 'msg' => 'Ha ocurrido un error en la base de datos.'];

          return ['resultado' => 'ok', 'msg' => "Se ha registrado el laboratorio {$this->razon}."];            

        }catch(\PDOException $error){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        } 

      }

      public function getRif($rif, $idLab){
        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1)
          return ['resultado' => 'error','msg' => 'Rif inválido.'];

        $this->rif = $rif;
        
        return $this->validarRif();
      }

      private function validarRif(){

        try {
          $this->conectarDB();

          $sql = "SELECT rif_laboratorio FROM laboratorio
                  WHERE status = 1 AND rif_laboratorio = ?";
          $new = $this->con->prepare($sql);
          $new->bindValue(1, $this->rif);
          $new->execute();
          $data = $new->fetchAll();

          $this->desconectarDB();
          if(isset($data[0]['rif_laboratorio']))
            return ['resultado' => 'error', 'msg' => 'El rif ya está registrado.', 'res' => true];
          else
            return ['resultado' => 'ok', 'msg' => 'Rif válido.', 'res' => false];

        } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      }


      public function getItem($rif){

        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1)
          return ['resultado' => 'error','msg' => 'Rif inválido.'];
        
        $this->rif = $rif;

        return $this->selectItem();
      }

      private function selectItem(){

        try{
          $this->conectarDB();
          $sql = "SELECT rif_laboratorio, direccion, razon_social FROM laboratorio 
                  WHERE status = 1 and rif_laboratorio = ? ;";
          $new = $this->con->prepare($sql);
          $new->bindValue(1, $this->rif);
          $new->execute();
          $data = $new->fetchAll(\PDO::FETCH_OBJ);
          $this->desconectarDB();
          return $data;

        }catch(\PDOException $e){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      }

      public function getEditar($rif, $direccion, $razon, $id){

        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1)
          return ['resultado' => 'error','msg' => 'Rif inválido.'];
        
        if(preg_match_all("/^[a-zA-ZÀ-ÿ]{5,30}$/", $razon) != 1)
          return ['resultado' => 'error','msg' => 'Nombre inválido.'];
        
        if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\"\/,.-]){7,160}$/', $direccion) != 1)
          return ['resultado' => 'error','msg' => 'Direccion inválida.'];
        
        if(preg_match_all("/^[0-9]{7,10}$/", $id) != 1)
          return ['resultado' => 'error','msg' => 'Id inválida.'];

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->idedit = $id;

        if($this->idedit !== $this->rif){
          $validarRif = $this->validarRif();
          if($validarRif['res']) return ["resultado" => "error", "msg" => "Rif ya registrado"];
        }

        return $this->editarLaboratorio();
      }

      private function editarLaboratorio(){

        try{
          $this->conectarDB();
          $sql = "UPDATE laboratorio 
                  SET rif_laboratorio = ? , direccion = ? , razon_social = ?
                  WHERE rif_laboratorio = ?";
          $new = $this->con->prepare($sql);
          $new->bindValue(1, $this->rif);
          $new->bindValue(2, $this->direccion);
          $new->bindValue(3, $this->razon);
          $new->bindValue(4, $this->idedit);
          $new->execute();
          $this->binnacle("Laboratorio",$_SESSION['cedula'],"Editó laboratorio.");
          $this->desconectarDB();
          return ['resultado' => 'ok', "msg" => "Se ha editado correctamente el laboratorio {$this->rif}."];

        }catch(\PDOException $error){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      } 


      public function getEliminar($rif){
        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1)
          return ['resultado' => 'error','msg' => 'Id inválida.'];

        $this->rif = $rif;

        return $this->eliminarLaboratorio();
      }

      private function eliminarLaboratorio(){
        try{
          $this->conectarDB();
          $new = $this->con->prepare("UPDATE laboratorio SET status = 0 WHERE rif_laboratorio = ?; ");
          $new->bindValue(1, $this->rif);
          $new->execute();
          $this->binnacle("Laboratorio",$_SESSION['cedula'],"Eliminó laboratorio.");
          $this->desconectarDB();
          return ['resultado' => 'ok', "msg" => "Se ha eliminado correctamente el laboratorio {$this->rif}."];
        }catch(\PDOException $e){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      }

      public function getIdLaboratorioByRif($rif){
        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1){
          return ['resultado' => 'error','msg' => 'Rif inválido.'];
        }
        $this->rif = $rif;

        return $this->gettingIdTest();
      }

      private function gettingIdTest(){
        try {

          $this->conectarDB();
          $sql = "SELECT cod_lab FROM laboratorio WHERE rif = ? AND status = 1";
          $new = $this->con->prepare($sql);
          $new->bindValue(1, $this->rif);
          $new->execute();
          [$data] = $new->fetchAll(\PDO::FETCH_OBJ);
          
          return ['resultado' => "ok", "id"=> $data->cod_lab];

        } catch (\PDOException $e) {
          die($e);
        }
      }
  }

?>
