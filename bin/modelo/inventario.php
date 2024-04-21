<?php
namespace modelo;
use config\connect\DBConnect as DBConnect;

class inventario extends DBConnect{

    public function __construct(){
        parent::__construct();
    }

      public function mostrarInventarioAjax($bitacora){
        
     try {
    
          parent::conectarDB();
            $query = "SELECT h.fecha, h.tipo_movimiento, h.entrada, h.salida, s.nombre, h.id_lote, h.id_producto_sede, h.cantidad FROM historial as h, sede as s WHERE h.status = 1";
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
}
?>