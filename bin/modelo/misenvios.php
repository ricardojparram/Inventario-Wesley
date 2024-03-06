<?php 

  namespace modelo; 
  use config\connect\DBConnect as DBConnect;

  class misenvios extends DBConnect{

      private $id;
      private $cedula;
      private $monto;
      private $codigoP;
     private $cantidad;
      private $metodo;
      private $moneda;


      function __construct(){
       parent::__construct();
     }


    //---------------------------------MOSTRAR VENTA--------------------------------

    public function MostrarVentas(){
    $bitacora = "false";

    $cedula=$_SESSION['cedula'];
    

      try{
        $query = "SELECT v.cedula_cliente , v.num_fact ,v.fecha , tp.des_tipo_pago , v.monto , CONCAT(IF(MOD(v.monto / cm.cambio, 1) >= 0.5, CEILING(v.monto / cm.cambio), FLOOR(v.monto / cm.cambio) + 0.5), ' ', m.nombre) as 'total_divisa' FROM venta v INNER JOIN tipo_pago tp ON v.cod_tipo_pago = tp.cod_tipo_pago INNER JOIN cambio cm ON cm.id_cambio = v.cod_cambio INNER JOIN moneda m ON cm.moneda = m.id_moneda WHERE v.status = 1 and v.cedula_cliente = ?";

        $new = $this->con->prepare($query);
        $new->bindValue(1, $cedula);
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);
        echo json_encode($data);
        if($bitacora) $this->binnacle("Ventas",$_SESSION['cedula'],"Consultó listado.");
        die();
      }catch(\PDOexection $error){

       return $error;     
     }  
    }

     //---------------------------------EXPORTAR FACTURA--------------------------------

    public function ExportarFactura($id){

      if(preg_match_all("/^[0-9]{1,15}$/", $id) != 1){
        return "Error de id!";
      }

      $this->id = $id;
      
      return $this->exportar();
    }

    private function exportar(){
      try{

      }catch(\PDOexection $error){
        die($error);
      }
    }

     //--------------------------------- VALIDAR CLIENTE --------------------------------

     public function validarCliente($cedula){
     
      if(preg_match_all("/^[0-9]{3,30}$/", $cedula) != 1){
        return "Error de cedula!";
      }
      
       $this->cedula = $cedula;

       return $this->validarC();

     }

     private function validarC(){
       $new = $this->con->prepare("SELECT `cedula` FROM `cliente` WHERE `status` = 1 and `cedula` = ?");
       $new->bindValue(1, $this->cedula);
       $new->execute();
       $data = $new->fetchAll();

       if(isset($data[0]["cedula"])){
        echo json_encode(['resultado' => 'cedula valida.']);
        die();
       }else{
        echo json_encode(['resultado' => 'Error de cedula', 'error' => 'La cedula no está registrado.']);
        die();
       }

     }

     //---------------------------------DETALLES PRODUCTOS POR VENTA--------------------------------

    public function getDetalleV($id){
      try{
        $this->id = $id;
        $new = $this->con->prepare("SELECT p.descripcion , vp.cantidad , vp.precio_actual , v.num_fact FROM venta_producto vp INNER JOIN producto p ON vp.cod_producto = p.cod_producto INNER JOIN venta v ON v.num_fact = vp.num_fact WHERE v.status = 1 AND v.num_fact = ?");
        $new->bindValue(1, $this->id);
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);
        echo json_encode($data);
        die();

      }catch(\PDOexection $error){
        return $error;
      }
    }

  
     //---------------------------------MOSTRA CANTIDAD Y PRECIO DE PRODUCTO--------------------------------

    public function productoDetalle($id){
      $this->producto = $id;

      try {
        $new = $this->con->prepare("SELECT p_venta, stock FROM producto WHERE cod_producto = ? and status = 1");
        $new->bindValue(1, $this->producto);
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);
        
        echo json_encode($data);
        die();

      } catch (\PDOException $e) {
        return $e;
      }
    }

    public function getMostrarMoneda(){
     try{
      $new = $this->con->prepare("SELECT * FROM( SELECT c.id_cambio, m.nombre, c.cambio FROM cambio c INNER JOIN moneda m ON c.moneda = m.id_moneda WHERE c.status = 1 ORDER BY c.fecha DESC LIMIT 9999999) as tabla GROUP BY tabla.nombre");
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      echo json_encode($data);
      die();

    }catch(\PDOexection $error){

     return $error;   

   } 
 }


     //---------------------------------MOSTRAR METODO DE PAGO--------------------------------

    public function getMostrarMetodo(){
      try{
        $new = $this->con->prepare("SELECT * FROM `tipo_pago` WHERE status = 1");
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);
        return $data;

      }catch(\PDOexection $error){

       return $error;   

     }   
    }
    
     //---------------------------------MOSTRAR CLIENTE--------------------------------
    
    public function getMostrarCliente(){
      try{
        $new = $this->con->prepare("SELECT * FROM `cliente` WHERE status = 1");
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);
        return $data;

      }catch(\PDOexection $error){

       return $error;   

     }   
    }
    
  }

?>