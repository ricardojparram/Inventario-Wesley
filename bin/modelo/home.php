<?php 

namespace modelo;

Use config\connect\DBConnect as DBConnect;

class home extends DBConnect{

    private $fecha;
    private $sql;
    private $fechaActual;
    private $fechaModificada;
    private $fechaSegundos;

    public function __construct(){
        parent::__construct();
    }

    public function mostrarPersonal(){
    	try {
            parent::conectarDB();
    		$new = $this->con->prepare("SELECT 
                (SELECT count(*) FROM usuario WHERE status = 1) AS usuario, 
                (SELECT count(*) FROM proveedor WHERE status = 1) AS proveedor, 
                (SELECT count(*) FROM personal WHERE status = 1) AS personal, 
                (SELECT SUM(cantidad) FROM producto_sede ps INNER JOIN producto p ON ps.cod_producto = p.cod_producto WHERE p.status = 1) as producto");
    		$new->execute();
    		$data = $new->fetchAll();
            echo json_encode($data);
            parent::desconectarDB();
            die();
        }catch (\PDOException $error) {
          return $error;
      }
  }

  public function getVentas($fecha){
    $this->fecha = $fecha;

    $this->ventaFecha();
}

private function ventaFecha(){

    date_default_timezone_set("america/caracas");
    $this->fechaActual = date("Y-m-d G:i:s");

    $this->fechaSegundos = strtotime($this->fechaActual);
    $hora = '00';
    $minuto = '00';
    $segundo = '00';

    switch ($this->fecha) {
        case 'hoy':
        $dia = date("d", $this->fechaSegundos);
        $mes = date("m", $this->fechaSegundos);
        $año =  date("Y", $this->fechaSegundos);
        $this->fechaModificada = $año.'-'.$mes.'-'.$dia.' '.$hora.':'.$minuto.':'.$segundo;        
        break;


        case 'mensual':
        $dia = '01';
        $mes = date("m", $this->fechaSegundos);
        $año =  date("Y", $this->fechaSegundos);
        $this->fechaModificada = $año.'-'.$mes.'-'.$dia.' '.$hora.':'.$minuto.':'.$segundo;
        break;

        case 'anual':
        $dia = '01';
        $mes = '01';
        $año =  date("Y", $this->fechaSegundos);
        $this->fechaModificada = $año.'-'.$mes.'-'.$dia.' '.$hora.':'.$minuto.':'.$segundo;
        break;

        default:
        echo json_encode(['Error' => 'Tipo de reporte inválido.']);
        die();
        break;
    }

    try{
        parent::conectarDB();
        $new = $this->con->prepare("SELECT COUNT(*) as venta FROM venta WHERE fecha BETWEEN ? AND ? AND status = 1");
        $new->bindValue(1, $this->fechaModificada);
        $new->bindValue(2, $this->fechaActual);
        $new->execute();
        $valorV = $new->fetchAll();
        echo json_encode($valorV);
        parent::desconectarDB();
        die();

    }catch (\PDOException $error) {
        return $error;
    }
}

public function getCompras($fecha){
    $this->fecha = $fecha;

    $this->compraFecha();
}

private function compraFecha(){

    date_default_timezone_set("america/caracas");
    $this->fechaActual = date("Y-m-d G:i:s");

    $this->fechaSegundos = strtotime($this->fechaActual);


    switch ($this->fecha) {
        case 'hoy':
        $dia = date("d", $this->fechaSegundos);
        $mes = date("m", $this->fechaSegundos);
        $año =  date("Y", $this->fechaSegundos);
        $this->fechaModificada = $año.'-'.$mes.'-'.$dia;        
        break;


        case 'mensual':
        $dia = '01';
        $mes = date("m", $this->fechaSegundos);
        $año =  date("Y", $this->fechaSegundos);
        $this->fechaModificada = $año.'-'.$mes.'-'.$dia;
        break;

        case 'anual':
        $dia = '01';
        $mes = '01';
        $año =  date("Y", $this->fechaSegundos);
        $this->fechaModificada = $año.'-'.$mes.'-'.$dia;
        break;

        default:
        echo json_encode(['Error' => 'Tipo de reporte inválido.']);
        die();
        break;
    }

    try{
        parent::conectarDB();
        $new = $this->con->prepare("SELECT COUNT(*) as compra FROM compra WHERE fecha BETWEEN ? AND ? AND status = 1");
        $new->bindValue(1, $this->fechaModificada);
        $new->bindValue(2, $this->fechaActual);
        $new->execute();
        $valorC = $new->fetchAll();
        echo json_encode($valorC);
        parent::desconectarDB();
        die();

    }catch (\PDOException $error) {
        return $error;
    }
}

public function getGrafico(){

    try {
        parent::conectarDB();
        $new = $this->con->prepare("SELECT CAST(fecha AS DATE) as x, COUNT(num_fact) as y
            FROM venta
            WHERE CAST(fecha AS DATE) >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND status = 1
            GROUP BY CAST(fecha AS DATE)");
        $new->execute();
        $ventas = $new->fetchAll(\PDO::FETCH_OBJ);

        $new = $this->con->prepare("SELECT CAST(fecha AS DATE) as x, COUNT(orden_compra) as y 
            FROM compra WHERE CAST(fecha AS DATE) >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
            AND status = 1 GROUP BY CAST(fecha AS DATE)");
        $new->execute();
        $compras = $new->fetchAll(\PDO::FETCH_OBJ);
        parent::desconectarDB();

    } catch (\PDOException $error) {
        return $error;
        
    }

    date_default_timezone_set("america/caracas");
    $fecha = date("Y-m-d", strtotime("-7 day", time()));
    $fechas = [];
    $a = 1;
    for ($i=0; $i <7 ; $i++) { 
        $fechas[$i] = date("Y-m-d",strtotime("+".$a." day", strtotime($fecha)));
        $a++;
    }
    $grafico = ['fechas' => $fechas,
                'ventas' => $ventas,
                'compras'=> $compras];
    echo json_encode($grafico);
    die();

}



}



?>