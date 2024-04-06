<?php 

  namespace modelo; 
  use FPDF as FPDF;
  use config\connect\DBConnect as DBConnect;

  class ventas extends DBConnect{

  	  private $id;
      private $cedula;
      private $monto;
      private $codigoP;
      private $cantidad;
      private $metodo;
      private $moneda;

      public function getMostrarVentas($bitacora = false){
        try{

         parent::conectarDB();

         $query = "
         SELECT v.num_fact, v.monto_fact , pe.cedula AS cedula, pe.nombres AS nombre , v.fecha, (SELECT CONCAT(v.monto_dolares, ' ', m.nombre) FROM moneda m WHERE UPPER(m.nombre) = 'DOLAR') AS total_divisa
         FROM venta v 
         INNER JOIN venta_personal vpe ON vpe.num_fact = v.num_fact 
         LEFT JOIN personal pe ON pe.cedula = vpe.cedula 
         WHERE v.status = 1

         UNION ALL

         SELECT v.num_fact, v.monto_fact,  pa.ced_pac AS cedula, pa.nombre AS nombre , v.fecha, (SELECT CONCAT(v.monto_dolares, ' ', m.nombre) FROM moneda m WHERE UPPER(m.nombre) = 'DOLAR') AS total_divisa
         FROM venta v 
         INNER JOIN venta_pacientes vpa ON vpa.num_fact = v.num_fact 
         LEFT JOIN pacientes pa ON pa.ced_pac = vpa.ced_pac 
         WHERE v.status = 1";

         $new = $this->con->prepare($query);
         $new->execute();
         $data = $new->fetchAll(\PDO::FETCH_OBJ);

         parent::desconectarDB();

        return $data;

      }catch(\PDOexection $error){

       return $error;     
     }  
   }

   public function detalleProductos($id){
    if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
      return ['resultado' => 'Error de id','error' => 'id inválida.'];
    }

    $this->id = $id;

    return $this->detalleP();
  }

  private function detalleP(){
    try {

      parent::conectarDB();

      $query = "SELECT CONCAT(tp.nombrepro, ' ',pr.peso , '',m.nombre) AS producto , vp.cantidad , vp.precio_actual , vp.num_fact FROM venta_producto vp INNER JOIN producto_sede ps ON ps.id_producto_sede = vp.id_producto_sede INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida WHERE vp.num_fact = 1";

      $new = $this->con->prepare($query);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      return $data;


    } catch (\PDOException $e) {
      return $e;
    }
  }

   public function getMostrarClientes(){
    try {

    parent::conectarDB();

    $query = "SELECT 'Paciente' AS tipo, p.ced_pac AS cedula, p.nombre AS nombre, p.apellido AS apellido FROM pacientes p WHERE p.status = 1 UNION ALL SELECT 'Personal' AS tipo, pe.cedula AS cedula, pe.nombres AS nombre, pe.apellidos AS apellido FROM personal pe WHERE pe.status = 1";

    $new = $this->con->prepare($query);
    $new->execute();
    $data = $new->fetchAll(\PDO::FETCH_OBJ);

    return $data;


    } catch (\PDOException $e) {
      return $e;
    }
  }

  public function valorDolar(){
    try {

      parent::conectarDB();

      $query = "SELECT m.valor FROM moneda m WHERE UPPER(m.nombre) = 'DOLAR'";

      $new = $this->con->prepare($query);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      return $data;


    } catch (\PDOException $e) {
      return $e;
    }
  }

  public function selectProductos(){
    try {
      parent::conectarDB();

      $query = "SELECT ps.id_producto_sede, CONCAT(tp.nombrepro, ' ',pr.peso , '',m.nombre) AS producto , ps.lote FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida INNER JOIN sede s ON s.id_sede = ps.id_sede INNER JOIN compra_producto cp ON cp.id_producto_sede = ps.id_producto_sede INNER JOIN compra c ON c.orden_compra = cp.orden_compra WHERE p.status = 1 AND s.status = 1 AND c.status = 1 ORDER BY ps.fecha_vencimiento";

      $new = $this->con->prepare($query);

      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      parent::desconectarDB();

      return $data;

    } catch (\PDOException $e) {
      return $e;
    }
  }

  public function selectTipoPago(){
    try {
      parent::conectarDB();

      $query = 'SELECT fp.id_forma_pago, fp.tipo_pago FROM forma_pago fp WHERE fp.status = 1';

      $new = $this->con->prepare($query);

      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      parent::desconectarDB();

      return $data;

    } catch (\PDOException $e) {
      return $e;
    }
  }

  public function detallesProductoFila($id){
    if(preg_match_all("/^[0-9]{1,10}$/", $id) != 1){
      return ['resultado' => 'Error de id','error' => 'id inválida.'];
    }

    $this->id = $id;

    return $this->productoFila();
  }

  private function productoFila(){
    try {
      parent::conectarDB();

      $new = $this->con->prepare('SELECT ps.cantidad , ROUND((cp.precio_compra / cp.cantidad),2) AS precio FROM producto_sede ps INNER JOIN compra_producto cp ON cp.id_producto_sede = ps.id_producto_sede INNER JOIN compra c ON c.orden_compra = c.orden_compra WHERE ps.cantidad > 0 AND c.status = 1 AND ps.id_producto_sede = ? GROUP BY ps.id_producto_sede');

      $new->bindValue(1 ,$this->id);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      parent::desconectarDB();

      return $data;


    } catch (\PDOException $e) {
      return $e;
    }
  }




}

?>