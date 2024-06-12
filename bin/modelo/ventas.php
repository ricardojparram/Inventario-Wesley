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
         INNER JOIN pagos_recibidos pr ON pr.num_fact = v.num_fact
         LEFT JOIN personal pe ON pe.cedula = vpe.cedula 
         WHERE v.status = 1 AND pr.status = 1

         UNION ALL

         SELECT v.num_fact, v.monto_fact,  pa.ced_pac AS cedula, pa.nombre AS nombre , v.fecha, (SELECT CONCAT(v.monto_dolares, ' ', m.nombre) FROM moneda m WHERE UPPER(m.nombre) = 'DOLAR') AS total_divisa
         FROM venta v 
         INNER JOIN venta_pacientes vpa ON vpa.num_fact = v.num_fact 
         INNER JOIN pagos_recibidos pr ON pr.num_fact = v.num_fact
         LEFT JOIN pacientes pa ON pa.ced_pac = vpa.ced_pac 
         WHERE v.status = 1 AND pr.status = 1;";

         $new = $this->con->prepare($query);
         $new->execute();
         $data = $new->fetchAll(\PDO::FETCH_OBJ);

         if ($bitacora)
         $this->binnacle("Ventas", $_SESSION['cedula'], "Consultó listado ventas.");

         parent::desconectarDB();

        return $data;

      }catch(\PDOexection $e){

       return $e;     

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

      $query = "SELECT ps.id_producto_sede, CONCAT(tp.nombrepro, ' ',pr.peso , '',m.nombre) AS producto , ps.lote FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida INNER JOIN sede s ON s.id_sede = ps.id_sede INNER JOIN compra_producto cp ON cp.id_producto_sede = ps.id_producto_sede INNER JOIN compra c ON c.orden_compra = cp.orden_compra WHERE p.status = 1 AND s.status = 1 AND c.status = 1 AND ps.cantidad > 0 ORDER BY ps.fecha_vencimiento;";

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

    if (!$this->validarString('entero', $id))
      return $this->http_error(400, 'id producto inválido.');

    $this->id = $id;

    return $this->productoFila();
  }

  private function productoFila(){
    try {
      parent::conectarDB();

      $new = $this->con->prepare('SELECT ps.cantidad , cp.precio_compra AS precio FROM producto_sede ps INNER JOIN compra_producto cp ON cp.id_producto_sede = ps.id_producto_sede INNER JOIN compra c ON c.orden_compra = c.orden_compra WHERE ps.cantidad > 0 AND c.status = 1 AND ps.id_producto_sede = ? GROUP BY ps.id_producto_sede');

      $new->bindValue(1 ,$this->id);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);

      parent::desconectarDB();

      return $data;


    } catch (\PDOException $e) {
      return $e;
    }
  }

  public function validarCedula($cedula, $tipoCliente){

    if (!$this->validarString('nombre', $tipoCliente))
      return $this->http_error(400, 'Tipo Cliente inválido.');

    $tipo = [
        'Paciente' => 'cedula',
        'Personal' => 'documento',
    ];

    if (!$this->validarString($tipo[$tipoCliente] , $cedula))
      return $this->http_error(400, 'Cedula inválido.');


    $this->cedula = $cedula;

    return $this->validCedula();

  }

    private function validCedula(){
      try {
        parent::conectarDB();

        $new = $this->con->prepare("SELECT 'Paciente' AS tipo, p.ced_pac AS cedula FROM pacientes p WHERE p.ced_pac = :cedula AND p.status = 1 UNION ALL SELECT 'Personal' AS tipo, pe.cedula AS cedula FROM personal pe WHERE pe.cedula = :cedula AND pe.status = 1");
        $new->bindValue(':cedula', $this->cedula);
        $new->execute();
        $data = $new->fetchAll();
        parent::desconectarDB();

        $mensaje = 'La cedula ' . $this->cedula . ' no existe';

        if(isset($data[0]['cedula'])){
          return ['resultado' => 'cedula valida', 'res' => true];

        }else{
         return ['resultado' => 'error', 'msg' => $mensaje, 'res' => false];
       }

     }catch (\PDOException $e) {
      return $e;
    }
  }

  private function validProductos(){
    try {
      parent::conectarDB();

      $mensaje = '';

      foreach ($this->datosProducto as $producto){

        $id_producto_sede = $producto['producto'];
        $cantidad  = $producto['cantidad'];


        $new = $this->con->prepare("SELECT ps.cantidad , CONCAT(tp.nombrepro, ' ',pr.peso , '',m.nombre) AS producto , ps.lote FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida WHERE ps.id_producto_sede = :id_producto_sede");
        $new->bindValue(':id_producto_sede',  $id_producto_sede);
        $new->execute();

        $result = $new->fetchAll();

  
        if (empty($result)) {
          $mensaje .= "Error ID $id_producto_sede no existe. ";

        } else if ($result[0]['cantidad'] < $cantidad) {
          $producto = $result[0]['producto'];
          $mensaje .= "Error no hay suficiente $producto disponible. ";

        }

      }

      if ($mensaje) {
        return ['resultado' => 'error', 'msg' => $mensaje, 'res' => false];
      } else {
        return ['resultado' => 'producto valido', 'res' => true];
      }
      
    }catch (\PDOException $e) {
      return $e;
    }
  }

    private function validTipoPago(){
    try {
      parent::conectarDB();

      $mensaje = '';

      foreach ($this->datosTipoPago as $tipoPago){

        $TipoPago = $tipoPago['TipoPago'];

        $new = $this->con->prepare("SELECT fp.tipo_pago FROM forma_pago fp WHERE fp.id_forma_pago = :id_forma_pago AND fp.status = 1");
        $new->bindValue(':id_forma_pago',  $TipoPago);
        $new->execute();

        $result = $new->fetchAll();

        if (empty($result)) $mensaje = "Error de tipo de pago no existe";
        
      }

      if ($mensaje) {
        return ['resultado' => 'error', 'msg' => $mensaje, 'res' => false];
      } else {
        return ['resultado' => 'tipo de pago valido', 'res' => true];
      }
      
    }catch (\PDOException $e) {
      return $e;
    }
  }

  public function getRegistrarVenta($cedula , $tipoCliente , $montoTotal , $totalDolares , $datosProducto , $datosTipoPago){

    if (!$this->validarString('nombre', $tipoCliente))
      return $this->http_error(400, 'Tipo Cliente inválido.');

    $tipo = [
        'Paciente' => 'cedula',
        'Personal' => 'documento',
    ];

    if (!$this->validarString($tipo[$tipoCliente] , $cedula))
      return $this->http_error(400, 'Cedula inválido.');


    if (!$this->validarString('decimal', $montoTotal))
      return $this->http_error(400, 'Monto total inválido.');

    if (!$this->validarString('decimal', $totalDolares))
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

    $validarCedula = $this->validCedula();

    if ($validarCedula['res'] === false) return $this->http_error(400, $validarCedula['msg']);

    $validarProductos = $this->validProductos();

    if ($validarProductos['res'] === false) return $this->http_error(400, $validarProductos['msg']);

    $validarTipoPago = $this->validTipoPago();

    if ($validarTipoPago['res'] === false) return $this->http_error(400, $validarTipoPago['msg']);

    return $this->registrarVenta();

  }


  private function registrarVenta(){
    try {
      parent::conectarDB();
      
      $new = $this->con->prepare('SELECT `num_fact`FROM `venta` ORDER BY `num_fact` DESC LIMIT 1');
      $new->execute();
      $data = $new->fetchAll();

      $factura = ($data)? $this->generarNumeroFactura($data[0]['num_fact']) : 'N°-A00000';

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
       $this->inventario_historial("Venta", "", "x", "", $datos['producto'], $datos['cantidad']);
        
      }

      $status = (count(array_filter($this->datosTipoPago, function($datosTipo) {
        return isset($datosTipo['referencia']) && !empty($datosTipo['referencia']);
      })) > 0) ? 0 : 1;

      $new = $this->con->prepare('INSERT INTO `pagos_recibidos`(`id_pago`, `num_fact`, `status`) VALUES (DEFAULT,?,?)');
      $new->bindValue(1, $factura);
      $new->bindValue(2, $status);
      $new->execute();
      $this->id = $this->con->lastInsertId();

      foreach ($this->datosTipoPago as $datosTipo){

       $new = $this->con->prepare('INSERT INTO `detalle_pago`(`id_det_pago`, `id_pago`, `id_forma_pago`, `referencia`, `monto_pago`) VALUES (DEFAULT,?,?,?,?)');
       $new->bindValue(1, $this->id);
       $new->bindValue(2, $datosTipo['TipoPago']);
       $new->bindValue(3, isset($datosTipo['referencia']) ? $datosTipo['referencia'] : NULL);
       $new->bindValue(4, $datosTipo['precioTipo']);
       $new->execute();
        
      }


      $this->binnacle("Ventas", $_SESSION['cedula'], "Registró una venta.");

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


  private function validFactura(){
    try {
      parent::conectarDB();
      $new = $this->con->prepare('SELECT v.num_fact FROM venta v WHERE v.status = 1 and v.num_fact = ?');
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();

      if(isset($data[0]["num_fact"])){
      return ['resultado' => 'venta valida', 'res' => true];

      }else{
       return ['resultado' => 'error', 'msg' => 'La venta no existe', 'res' => false];
     }
      
    }catch (\PDOException $e) {
      return $e;
    }
  }

  public function getAnularVenta($id){

     if (!$this->validarString('factura', $id))
      return $this->http_error(400, 'factura inválido.');

    $this->id = $id;

    $validarFactura = $this->validFactura();

    if ($validarFactura['res'] === false) return $this->http_error(400, $validarFactura['msg']);

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
     
      $this->binnacle("Ventas", $_SESSION['cedula'], "Anulo una venta.");
      parent::desconectarDB();
      
      return ['resultado' => 'Venta eliminada'];

    }
    catch(\PDOexection $error){
      return $error;
    }
  }

  public function ExportarTicket($id){

    if (!$this->validarString('factura', $id))
      return $this->http_error(400, 'factura inválido.');

      $this->id = $id;

      $validarFactura = $this->validFactura();

      if ($validarFactura['res'] === false) return $this->http_error(400, $validarFactura['msg']);
      
      return $this->exportar();
    }

    private function exportar(){
      try{
       parent::conectarDB();

       $query = "SELECT v.num_fact, v.monto_fact, pe.cedula AS cedula, pe.nombres AS nombre, pe.apellidos AS apellido, v.fecha, pe.telefono AS telefono, pe.direccion AS direccion, v.monto_dolares AS total_divisa
          FROM venta v 
          INNER JOIN venta_personal vpe ON vpe.num_fact = v.num_fact 
          LEFT JOIN personal pe ON pe.cedula = vpe.cedula 
          WHERE v.status = 1 AND v.num_fact = :num_fact

          UNION ALL

          SELECT v.num_fact, v.monto_fact, pa.ced_pac AS cedula, pa.nombre AS nombre, pa.apellido AS apellido, v.fecha, '' AS telefono, '' AS direccion, v.monto_dolares AS total_divisa
          FROM venta v 
          INNER JOIN venta_pacientes vpa ON vpa.num_fact = v.num_fact 
          LEFT JOIN pacientes pa ON pa.ced_pac = vpa.ced_pac 
          WHERE v.status = 1 AND v.num_fact = :num_fact ";

       $new = $this->con->prepare($query);
       $new->bindValue(':num_fact' , $this->id);
       $new->execute();
       $dataV = $new->fetchAll();

       $queryP = "SELECT CONCAT(tp.nombrepro, ' ',pr.peso , '',m.nombre) AS descripcion , vp.cantidad , vp.precio_actual , vp.num_fact FROM venta_producto vp INNER JOIN producto_sede ps ON ps.id_producto_sede = vp.id_producto_sede INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida WHERE vp.num_fact = ?";
       $new = $this->con->prepare($queryP);
       $new->bindValue(1 , $this->id);
       $new->execute();
       $dataP = $new->fetchAll();
      
       $nombre = 'Ticket_'.$dataV[0]['num_fact'].'_'.$dataV[0]['cedula'].'.pdf';
       
       $pdf = new FPDF();
       $pdf->SetMargins(4,10,4);
       $pdf->AddPage();
       
       $pdf->SetFont('Arial','B',10);
       $pdf->SetTextColor(0,0,0);
       $pdf->MultiCell(0,5,utf8_decode(strtoupper('Centro Medico Wesley')),0,'C',false);
       $pdf->SetFont('Arial','',9);
       $pdf->MultiCell(0,5,utf8_decode('Rif: 1234567'),0,'C',false);
       $pdf->MultiCell(0,5,utf8_decode('Dirreción: Barrio José Félix Ribas, Barquisimeto-Estado Lara.'),0,'C',false);
       $pdf->MultiCell(0,5,utf8_decode('Teléfono: 04120502369'),0,'C',false);
       $pdf->MultiCell(0,5,utf8_decode('Correo: MediSalud@gmail.com'),0,'C',false);

       $pdf->Ln(1);
       $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
       $pdf->Ln(5);

       $pdf->MultiCell(0,5,utf8_decode('Fecha: '. $dataV[0]['fecha']),0,'C',false);
       $pdf->SetFont('Arial','B',10);
       $pdf->MultiCell(0,5,utf8_decode(strtoupper("Ticket Nro: ". $dataV[0]['num_fact'])),0,'C',false);
       $pdf->SetFont('Arial','',9);

       $pdf->Ln(1);
       $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
       $pdf->Ln(5);

       $pdf->MultiCell(0,5,utf8_decode("Cliente: ". $dataV[0]['nombre'].' '. $dataV[0]['apellido']),0,'C',false);
       $pdf->MultiCell(0,5,utf8_decode("Documento: ".$dataV[0]['cedula']),0,'C',false);
       $pdf->MultiCell(0,5,utf8_decode("Teléfono: ".$dataV[0]['telefono']),0,'C',false);
       $pdf->MultiCell(0,5,utf8_decode("Dirección: ".$dataV[0]['direccion']),0,'C',false);

       $pdf->Ln(1);
       $pdf->Cell(0,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
       $pdf->Ln(3);

       $tableWidth = 74;
       $pdf->SetLeftMargin(($pdf->GetPageWidth() - $tableWidth) / 2);

       $pdf->Cell(18,4,utf8_decode('Articulo'),0,0,'C');
       $pdf->Cell(19,5,utf8_decode('Cant.'),0,0,'C');
       $pdf->Cell(15,5,utf8_decode('Precio'),0,0,'C');
       $pdf->Cell(28,5,utf8_decode('Total'),0,0,'C');

       $pdf->Ln(3);
       $pdf->Cell($tableWidth,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
       $pdf->Ln(5);

       foreach ($dataP as $col => $value) {
         $pdf->Cell(18,4,utf8_decode($value[0]),0,0,'C');
         $pdf->Cell(19,4,utf8_decode($value[1]),0,0,'C');
         $pdf->Cell(19,4,utf8_decode($value[2]),0,0,'C');
         $pdf->Cell(28,4,utf8_decode($value[1] * $value[2]),0,1,'C');

       }
       $pdf->Ln(4);

       $pdf->Cell($tableWidth,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');

       $pdf->Ln(5);

       $montoTotal = $dataV[0]['monto_fact'];

       $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
       $pdf->Cell(22,5,utf8_decode("TOTAL"),0,0,'C');
       $pdf->Cell(32,5,utf8_decode($montoTotal),0,0,'C');

       $pdf->Ln(5);

       $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
       $pdf->Cell(22,5,utf8_decode("Dolar"),0,0,'C');
       $pdf->Cell(32,5,utf8_decode($dataV[0]['total_divisa'].'$'),0,0,'C');

       $pdf->Ln(10);

       $pdf->MultiCell($tableWidth,5,utf8_decode('*** Precios de productos no incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar este ticket ***'),0,'C',false);

       $pdf->SetFont('Arial','B',9);
       $pdf->Cell($tableWidth,7,utf8_decode('Gracias por su compra'),'',0,'C');

       $pdf->Ln(9);

       $repositorio = 'assets/tickets/'.$nombre;
       $pdf->Output('F',$repositorio);

       $respuesta = ['respuesta' => 'Archivo guardado', 'ruta' => $repositorio];
       
       $this->binnacle("Venta",$_SESSION['cedula'], "Exporto Ticket de Venta");
       parent::desconectarDB();

       return $respuesta;

      }catch(\PDOexection $error){
        die($error);
      }
    }




}

?>