<?php 
   
    namespace modelo;
    use config\connect\DBConnect as DBConnect;
   
    class transferencia extends DBConnect{

      private $id_transferencia;
      private $id_sede;
      private $id_lote;
      private $cantidad;
      private $fecha;

      public function mostrarTransferencias($bitacora){
        try{
          $this->conectarDB();
          $sql = "SELECT id_transferencia, id_sede, id_lote, cantidad, fecha FROM transferencia WHERE status = 1";
          $new = $this->con->prepare($sql);
          $new->execute();
          $data = $new->fetchAll(\PDO::FETCH_OBJ);
          // if($bitacora == "true") $this->binnacle("Transferencia",$_SESSION['cedula'],"Consultó listado.");
          $this->desconectarDB();
          return $data;

        }catch(\PDOException $e){
          return ['error' => $e->getMessage()];
        }
      } 

      public function getDatosLab($rif, $direccion, $razon, $telefono, $contacto){

        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1){
          return ['resultado' => 'error','msg' => 'Rif inválido.'];
        }
        if(preg_match_all("/^[a-zA-ZÀ-ÿ]{5,30}$/", $razon) != 1){
          return ['resultado' => 'error','msg' => 'Nombre inválido.'];
        }
        if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){7,160}$/', $direccion) != 1){
          return ['resultado' => 'error','msg' => 'Direccion inválida.'];
        }
        if(preg_match_all("/^[0-9]{10,30}$/", $telefono) != 1){
          return ['resultado' => 'error','msg' => 'Telefono inválido.'];
        }
        if(preg_match_all("/^[^';]*$/", $contacto) != 1){
          return ['resultado' => 'error','msg' => 'Contacto inválido.'];
        }

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->telefono = $telefono;
        $this->contacto = $contacto;

        $this->idedit = false;
        $validarRif = $this->validarRif();
        if($validarRif['res'] === false) return ["resultado" => "error", "msg" => "Rif ya registrado"];

        return $this->registrarLab();

      }

      private function registrarLab(){

        try{
          $this->conectarDB();
          $pk = $this->uniqueID();
          $new = $this->con->prepare("INSERT INTO laboratorio(cod_lab,rif,direccion,razon_social,status) VALUES(?,?,?,?,1)");
          $new->bindValue(1, $pk);
          $new->bindValue(2, $this->rif); 
          $new->bindValue(3, $this->direccion); 
          $new->bindValue(4, $this->razon);
          $new->execute();

          $new = $this->con->prepare("INSERT INTO contacto_lab(id_contacto_lab, telefono, contacto, cod_lab) VALUES (DEFAULT, ?, ?, ?)");
          $new->bindValue(1, $this->telefono);
          $new->bindValue(2, $this->contacto);
          $new->bindValue(3, $pk);
          $new->execute();
          $resultado = ['resultado' => 'ok', 'msg' => "Laboratorio registrado."];
          $this->binnacle("Laboratorio",$_SESSION['cedula'],"Registró laboratorio.");
          $this->desconectarDB();
          return $resultado;            

        }catch(\PDOException $error){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        } 

      }

      public function getRif($rif, $idLab){

        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1){
          return ['resultado' => 'error','msg' => 'Rif inválido.'];
        }

        $this->idedit = ($idLab === "false") ? false : $idLab;
        $this->rif = $rif;

        return $this->validarRif();
      }

      private function validarRif(){

        try {
          $this->conectarDB();

          if($this->idedit === false){
            $new = $this->con->prepare('SELECT rif FROM laboratorio WHERE status = 1 and rif = ?');
            $new->bindValue(1, $this->rif);
          }else{
            $new = $this->con->prepare('SELECT rif FROM laboratorio WHERE status = 1 and rif = ? AND cod_lab != ?');
            $new->bindValue(1, $this->rif);
            $new->bindValue(2, $this->idedit);
          }
          
          $new->execute();
          $data = $new->fetchAll();

          $resultado;
          if(isset($data[0]['rif'])){
            $resultado = ['resultado' => 'error', 'msg' => 'El rif ya está registrado.', 'res' => false];
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

        if(preg_match_all("/^[a-fA-F0-9]{10}$/", $id) != 1){
          return ['resultado' => 'error','msg' => 'Id inválida.'];
        }

        $this->id = $id;

        return $this->selectItem();
      }

      private function selectItem(){

        try{
          $this->conectarDB();
          $sql = "SELECT * FROM laboratorio l 
          INNER JOIN contacto_lab cl ON l.cod_lab = cl.cod_lab 
          WHERE l.status = 1 and l.cod_lab = ? ;";
          $new = $this->con->prepare($sql);
          $new->bindValue(1, $this->id);
          $new->execute();
          $data = $new->fetchAll(\PDO::FETCH_OBJ);
          $this->desconectarDB();
          return $data;

        }catch(\PDOException $e){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      }

      public function getEditar($rif, $direccion, $razon, $telefono, $contacto, $id){

        if(preg_match_all("/^[0-9]{7,10}$/", $rif) != 1){
          return ['resultado' => 'error','msg' => 'Rif inválido.'];
        }
        if(preg_match_all("/^[a-zA-ZÀ-ÿ]{5,30}$/", $razon) != 1){
          return ['resultado' => 'error','msg' => 'Nombre inválido.'];
        }
        if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\"\/,.-]){7,160}$/', $direccion) != 1){
          return ['resultado' => 'error','msg' => 'Direccion inválida.'];
        }
        if(preg_match_all("/^[0-9]{10,30}$/", $telefono) != 1){
          return ['resultado' => 'error','msg' => 'Telefono inválido.'];
        }
        if(preg_match_all("/^[a-fA-F0-9]{10}$/", $id) != 1){
          return ['resultado' => 'error','msg' => 'Id inválida.'];
        }
        if(preg_match_all("/^[^';]*$/", $contacto) != 1){
          return ['resultado' => 'error','msg' => 'Contacto inválido.'];
        }

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->telefono = $telefono;
        $this->contacto = $contacto;
        $this->idedit = $id;

        $validarRif = $this->validarRif();
        if($validarRif['res'] === false) return ["resultado" => "error", "msg" => "Rif ya registrado"];

        return $this->editarLaboratorio();
      }

      private function editarLaboratorio(){

        try{
          $this->conectarDB();
          $sql = "UPDATE laboratorio l
          INNER JOIN contacto_lab cl ON l.cod_lab = cl.cod_lab
          SET l.rif = ? , l.direccion = ? , l.razon_social = ? , cl.telefono = ?, cl.contacto = ?
          WHERE l.cod_lab = ?";
          $new = $this->con->prepare($sql);
          $new->bindValue(1, $this->rif);
          $new->bindValue(2, $this->direccion);
          $new->bindValue(3, $this->razon);
          $new->bindValue(4, $this->telefono);
          $new->bindValue(5, $this->contacto);
          $new->bindValue(6, $this->idedit);
          $new->execute();
          $resultado = ['resultado' => 'ok', "msg" => "Laboratorio editado correctamente."];
          $this->binnacle("Laboratorio",$_SESSION['cedula'],"Editó laboratorio.");
          $this->desconectarDB();
          return $resultado;

        }catch(\PDOException $error){
          print "¡Error!: " . $e->getMessage() . "<br/>";
          die();
        }

      } 


      public function getEliminar($id){
        if(preg_match_all("/^[a-fA-F0-9]{10}$/", $id) != 1){
          return ['resultado' => 'error','msg' => 'Id inválida.'];
        }

        $this->id = $id;

        return $this->eliminarLaboratorio();
      }

      private function eliminarLaboratorio(){
        try{
          $this->conectarDB();
          $new = $this->con->prepare("UPDATE laboratorio SET status = 0 WHERE laboratorio.cod_lab = ?; ");
          $new->bindValue(1, $this->id);
          $new->execute();
          $resultado = ['resultado' => 'ok', 'msg' => "Laboratorio eliminado correctamente."];
          $this->binnacle("Laboratorio",$_SESSION['cedula'],"Eliminó laboratorio.");
          $this->desconectarDB();
          return $resultado;

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
