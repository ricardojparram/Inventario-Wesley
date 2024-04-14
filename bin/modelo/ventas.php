<?php 

  namespace modelo; 
  use FPDF as FPDF;
  use config\connect\DBConnect as DBConnect;
  use utils\validar;

  class ventas extends DBConnect{

      use validar;
  	  private $id;
      private $cedula;
      private $tipoCliente;
      private $monto;
      private $dolares;
      private $datosProducto;
      private $datosTipoPago;
      
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

    $this->id = $id;

    return $this->detalleP();
  }

  private function detalleP(){
    try {

      parent::conectarDB();

      $query = "SELECT CONCAT(tp.nombrepro, ' ',pr.peso , '',m.nombre) AS producto , vp.cantidad , vp.precio_actual , vp.num_fact FROM venta_producto vp INNER JOIN producto_sede ps ON ps.id_producto_sede = vp.id_producto_sede INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida WHERE vp.num_fact = ?";

      $new = $this->con->prepare($query);
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      return $data;


    } catch (\PDOException $e) {
      return $e;
    }
  }

   public function detalleTipo($id){

    $this->id = $id;

    return $this->detalleT();
  }

  private function detalleT(){
    try {

      parent::conectarDB();

      $query = "SELECT fp.tipo_pago, dp.referencia , dp.monto_pago , v.num_fact FROM detalle_pago dp INNER JOIN forma_pago fp ON fp.id_forma_pago = dp.id_forma_pago INNER JOIN pagos_recibidos pr ON pr.id_pago = dp.id_pago INNER JOIN venta v ON v.num_fact = pr.num_fact WHERE v.num_fact = ?";

      $new = $this->con->prepare($query);
      $new->bindValue(1, $this->id);
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

  public function getRegistrarVenta($cedula , $tipoCliente , $montoTotal , $totalDolares , $datosProducto , $datosTipoPago){
    if (!$this->validarString('documento', $cedula))
      return $this->http_error(400, 'Cedula inválido.');

    if (!$this->validarString('nombre', $tipoCliente))
      return $this->http_error(400, 'Tipo Cliente inválido.');

    if (!$this->validarString('decimnal', $montoTotal))
      return $this->http_error(400, 'Monto total inválido.');

    if (!$this->validarString('decimnal', $totalDolares))
      return $this->http_error(400, 'Monto total en dolares inválido.');

    $estructura_productos = [
      'producto' => 'string',
      'cantidad' => 'string',
      'precio' => 'string',
    ];

    if (!$this->validarEstructuraArray($datosProducto, $estructura_productos, true))
      return $this->http_error(400, 'Productos inválidos.');

    $estructura_tipo = [
      'TipoPago' => 'string',
      'referencia' => 'string',
      'precioTipo' => 'string',
    ];

    if (!$this->validarEstructuraArray($datosTipoPago, $estructura_tipo, true))
      return $this->http_error(400, 'Datos Tipo Pago inválidos.');

    $this->cedula = $cedula;
    $this->tipoCliente = $tipoCliente;
    $this->monto = $montoTotal;
    $this->dolares = $totalDolares;
    $this->datosProducto = $datosProducto;
    $this->datosTipoPago = $datosTipoPago;

    return $this->registrarVenta();

  }


  private function registrarVenta(){
    try {
      parent::conectarDB();
      
      $new = $this->con->prepare('SELECT `num_fact`FROM `venta` ORDER BY `num_fact` DESC LIMIT 1');
      $new->execute();
      $data = $new->fetchAll();

      if ($data) {
        $factura = $this->generarNumeroFactura($data[0]['num_fact']);
      } else {
        $factura = 'N°-A00000';
      }


      $new = $this->con->prepare('INSERT INTO `venta`(`num_fact`, `monto_fact`, `monto_dolares`, `fecha`, `status`) VALUES (?,?,?,DEFAULT,1)');
      $new->bindValue(1, $factura);
      $new->bindValue(2, $this->monto);
      $new->bindValue(3, $this->dolares);
      $new->execute();

      $queryPersonal = "INSERT INTO `venta_personal`(`id_venta`, `cedula`, `num_fact`) VALUES (DEFAULT,?,?)";
      $queryPaciente = "INSERT INTO `venta_pacientes`(`id_venta`, `num_fact`, `ced_pac`) VALUES (DEFAULT,?,?)";

      if($this->tipoCliente === 'Personal'){
        $new = $this->con->prepare($queryPersonal);
        $new->bindValue(1,  $this->cedula);
        $new->bindValue(2, $factura);
        $new->execute();
      }else{
        $new = $this->con->prepare($queryPaciente);
        $new->bindValue(1,  $factura);
        $new->bindValue(2,  $this->cedula);
        $new->execute();
      }

      foreach ($this->datosProducto as $datos){

       $new = $this->con->prepare('INSERT INTO `venta_producto`(`id_venta_p`, `num_fact`, `id_producto_sede`, `cantidad`, `precio_actual`) VALUES (DEFAULT,?,?,?,?)');
       $new->bindValue(1, $factura);
       $new->bindValue(2, $datos['producto']);
       $new->bindValue(3, $datos['cantidad']);
       $new->bindValue(4, $datos['precio']);
       $new->execute();

       $this->actualizarCantidad($datos['producto'] , $datos['cantidad']);
        
      }

      $new = $this->con->prepare('INSERT INTO `pagos_recibidos`(`id_pago`, `num_fact`, `status`) VALUES (DEFAULT,?,1)');
      $new->bindValue(1, $factura);
      $new->execute();
      $this->id = $this->con->lastInsertId();

      foreach ($this->datosTipoPago as $datosTipo){

       $new = $this->con->prepare('INSERT INTO `detalle_pago`(`id_det_pago`, `id_pago`, `id_forma_pago`, `referencia`, `monto_pago`) VALUES (DEFAULT,?,?,?,?)');
       $new->bindValue(1, $this->id);
       $new->bindValue(2, $datosTipo['TipoPago']);
       $new->bindValue(3, $datosTipo['referencia']);
       $new->bindValue(4, $datosTipo['precioTipo']);
       $new->execute();
        
      }

       return ['resultado' => 'ok', 'msg' => 'Se ha registrado la venta correctamente'];
      
    } catch (\PDOException $e) {
      return $e;
    }
  }

  private function actualizarCantidad($id_producto_sede , $cantidad){
    try {
      parent::conectarDB();
      $new = $this->con->prepare('SELECT ps.cantidad FROM producto_sede ps WHERE ps.id_producto_sede = ?');
      $new->bindValue(1 , $id_producto_sede);
      $new->execute();
      $data = $new->fetchAll();

      $NewCantidad = $data[0]['cantidad'] - $cantidad ;

      $new = $this->con->prepare("UPDATE producto_sede ps SET ps.cantidad = ? WHERE ps.id_producto_sede = ?");
      $new->bindValue(1, $NewCantidad);
      $new->bindValue(2, $id_producto_sede);
      $new->execute();

    } catch (\PDOException $e) {
      return $e;
    }
  }

  public function validarFactura($id){

    $this->id = $id;

    return $this->validFactura();
  }

  private function validFactura(){
    try {
      parent::conectarDB();
      $new = $this->con->prepare('SELECT v.num_fact FROM venta v WHERE v.status = 1 and v.num_fact = ?');
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();

      if(isset($data[0]["num_fact"])){
        return ['resultado' => 'Si existe esa venta.'];

      }else{
       return['resultado' => 'Error de venta'];
     }
      
    }catch (\PDOException $e) {
      return $e;
    }
  }

  public function getAnularVenta($id){

    $this->id = $id;

    return $this->anularVenta();

  }

  private function anularVenta(){
      try{

      parent::conectarDB();

      $new = $this->con->prepare("SELECT ps.id_producto_sede, vp.cantidad , ps.cantidad as stock FROM venta_producto vp INNER JOIN producto_sede ps ON ps.id_producto_sede = vp.id_producto_sede WHERE vp.num_fact = ?");
      
      $new->bindValue(1, $this->id);
      $new->execute();
      $result = $new->fetchAll(\PDO::FETCH_OBJ);

      foreach ($result as $data){

        $stockAct = $data->stock;
        $cantidad = $data->cantidad;
        $idProductoSede = $data->id_producto_sede;

        $NewStock = $cantidad + $stockAct;

        $new = $this->con->prepare("UPDATE producto_sede ps SET ps.cantidad = ? WHERE ps.id_producto_sede = ?");
        $new->bindValue(1, $NewStock);
        $new->bindValue(2, $idProductoSede);
        $new->execute();
        
      }

      $new = $this->con->prepare("UPDATE venta v SET v.status = 0 WHERE v.num_fact = ?");
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
    
      parent::desconectarDB();

      return ['resultado' => 'Venta eliminada.'];

    }
    catch(\PDOexection $error){
      return $error;
    }
  }




}

?>