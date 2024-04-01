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




}

?>