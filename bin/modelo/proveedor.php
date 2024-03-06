<?php 
   
    namespace modelo;
    use config\connect\DBConnect as DBConnect;
   
    class proveedor extends DBConnect{

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
 
      public function mostrarProveedorAjax($bitacora){
        try{
          $this->conectarDB();
          $sql = "SELECT p.rif, p.razon_social, p.direccion, cp.telefono, cp.contacto, p.cod_prove FROM proveedor p
                  INNER JOIN contacto_prove cp ON cp.cod_prove = p.cod_prove
                  WHERE p.status = 1;";
          $new = $this->con->prepare($sql);
          $new->execute();
          $data = $new->fetchAll(\PDO::FETCH_OBJ);
          if($bitacora == "true") $this->binnacle("Proveedor",$_SESSION['cedula'],"Consultó listado.");
          $this->desconectarDB();
          die(json_encode($data));

        }catch(\PDOException $e){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }
      } 

      public function getDatosPro($rif, $direccion, $razon, $telefono, $contacto){

        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1){
          die(json_encode(['resultado' => 'Error de rif','msg' => 'Rif inválido.']));
        }
        if(preg_match_all("/^[a-zA-ZÀ-ÿ\s]{0,30}$/", $razon) != 1){
          die(json_encode(['resultado' => 'Error de nombre','msg' => 'Nombre inválido.']));
        }
        if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){7,160}$/', $direccion) != 1){
          die(json_encode(['resultado' => 'Error de direccion','msg' => 'Direccion inválida.']));
        }
        if(preg_match_all("/^[0-9]{10,30}$/", $telefono) != 1){
          die(json_encode(['resultado' => 'Error de telefono','msg' => 'Telefono inválido.']));
        }

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->telefono = $telefono;
        $this->contacto = $contacto;

        $this->registrarPro();

      }

      private function registrarPro(){

        try{
          $this->conectarDB();

          $new = $this->con->prepare("INSERT INTO proveedor(cod_prove,rif,direccion,razon_social,status) VALUES(DEFAULT,?,?,?,1)");
          $new->bindValue(1, $this->rif); 
          $new->bindValue(2, $this->direccion); 
          $new->bindValue(3, $this->razon);
          $new->execute();
          $lastInsertId = $this->con->lastInsertId();

          $new = $this->con->prepare("INSERT INTO contacto_prove(id_contacto_prove, telefono, contacto, cod_prove) VALUES (DEFAULT, ?, ?, ?)");
          $new->bindValue(1, $this->telefono);
          $new->bindValue(2, $this->contacto);
          $new->bindValue(3, $lastInsertId);
          $new->execute();
          $resultado = ['resultado' => 'ok', 'msg' => "Proveedor registrado."];
          $this->binnacle("Proveedor",$_SESSION['cedula'],"Registró el proveedor.");
          $this->desconectarDB();
          die(json_encode($resultado));            

        }catch(\PDOException $error){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        } 

      }

      public function getRif($rif){

        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1){
          die(json_encode(['resultado' => 'Error de rif','msg' => 'Rif inválido.']));
        }

        $this->rif = $rif;

        return $this->validarRif();
      }

      private function validarRif(){

        try {
          $this->conectarDB();
          $new = $this->con->prepare("SELECT rif FROM proveedor WHERE status = 1 and rif = ?");
          $new->bindValue(1, $this->rif);
          $new->execute();
          $data = $new->fetchAll();

          $resultado;
          if(isset($data[0]['rif'])){
            $resultado = ['resultado' => 'Error de rif', 'msg' => 'El rif ya está registrado.', 'res' => false];
          }else{
            $resultado = ['resultado' => 'Rif válido.', 'res' => true];
          }
          $this->desconectarDB();
          return $resultado;

        } catch (PDOException $e) {
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      }


      public function getItem($id){

        if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
          die(json_encode(['resultado' => 'Error de id','msg' => 'Id inválida.']));
        }

        $this->id = $id;

        $this->selectItem();
      }

      private function selectItem(){

        try{
          $this->conectarDB();
          $sql = "SELECT * FROM proveedor p 
          INNER JOIN contacto_prove cp ON p.cod_prove = cp.cod_prove 
          WHERE p.status = 1 and p.cod_prove = ? ;";
          $new = $this->con->prepare($sql);
          $new->bindValue(1, $this->id);
          $new->execute();
          $data = $new->fetchAll(\PDO::FETCH_OBJ);
          $this->desconectarDB();
          die(json_encode($data));

        }catch(\PDOException $e){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      }

      public function getEditar($rif, $direccion, $razon, $telefono, $contacto, $id){

        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1){
          die(json_encode(['resultado' => 'Error de rif','msg' => 'Rif inválido.']));
        }
        if(preg_match_all("/^[a-zA-ZÀ-ÿ\s]{0,30}$/", $razon) != 1){
          die($razon);
          die(json_encode(['resultado' => 'Error de nombre','msg' => 'Nombre inválido.']));
        }
        if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\"\/,.-]){7,160}$/', $direccion) != 1){
          die(json_encode(['resultado' => 'Error de direccion','msg' => 'Direccion inválida.']));
        }
        if(preg_match_all("/^[0-9]{10,30}$/", $telefono) != 1){
          die(json_encode(['resultado' => 'Error de telefono','msg' => 'Telefono inválido.']));
        }
        if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
          die(json_encode(['resultado' => 'Error de id','msg' => 'Id inválida.']));
        }

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->telefono = $telefono;
        $this->contacto = $contacto;
        $this->idedit = $id;

        $validarRif = $this->validarRif();
        if($validarRif['res'] === true) die(json_encode(["resultado" => "error", "El proveedor no existe"]));

        $this->editarProveedor();
      }

      private function editarProveedor(){

        try{
          $this->conectarDB();
          $sql = "UPDATE proveedor p
          INNER JOIN contacto_prove cp ON p.cod_prove = cp.cod_prove 
          SET p.rif= ?, p.razon_social = ? , p.direccion= ?, cp.telefono = ? , cp.contacto = ? 
          WHERE p.cod_prove = ?";
          $new = $this->con->prepare($sql);
          $new->bindValue(1, $this->rif);
          $new->bindValue(2, $this->razon);
          $new->bindValue(3, $this->direccion);
          $new->bindValue(4, $this->telefono);
          $new->bindValue(5, $this->contacto);
          $new->bindValue(6, $this->idedit);
          $new->execute();
          $resultado = ['resultado' => 'ok', "msg" => "Proveedor ha sido editado correctamente."];
          $this->binnacle("Proveedor",$_SESSION['cedula'],"Editó proveedor.");
          $this->desconectarDB();
          die(json_encode($resultado));

        }catch(\PDOException $error){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      } 


      public function getEliminar($id){
        if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
          die(json_encode(['resultado' => 'Error de id','msg' => 'Id inválida.']));
        }

        $this->id = $id;

        $this->eliminarProveedor();
      }

      private function eliminarProveedor(){
        try{
          $this->conectarDB();
          $new = $this->con->prepare("UPDATE proveedor SET status = 0 WHERE proveedor.cod_prove = ?; ");
          $new->bindValue(1, $this->id);
          $new->execute();
          $resultado = ['resultado' => 'ok', 'msg' => "Proveedor ha sido eliminado correctamente."];
          $this->binnacle("Proveedor",$_SESSION['cedula'],"Eliminó proveedor.");
          $this->desconectarDB();
          die(json_encode($resultado));

        }catch(\PDOException $e){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      }
  }

?>