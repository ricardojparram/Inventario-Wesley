<?php 

  namespace modelo;

  use config\connect\DBConnect as DBConnect;


  class inicio extends DBConnect{

    private $id_producto;
    private $cantidad;
    private $user;

    public function __construct(){
    	parent::__construct();
    } 

    public function mostrarCatalogo(){
    	try {

    		$query='SELECT p.cod_producto, p.descripcion as nombre, p.descripcion, p.stock, p.p_venta, p.img, p.contraindicaciones, cl.des_clase, ps.cantidad as cantidad_pres, ps.medida as medida_pres, ps.peso as peso_pres FROM producto p
        INNER JOIN clase cl ON cl.cod_clase = p.cod_clase
        INNER JOIN presentacion ps ON ps.cod_pres = p.cod_pres
        WHERE p.status = 1 and p.stock != 0 LIMIT 4;';
        $this->conectarDB();
        $new = $this->con->prepare($query); 
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);

        $this->desconectarDB();
        die(json_encode($data));

      } catch (\PDOException $e) {
        die($e);
      }

    }

    public function mostrarCategorias(){
      try {

        $query='SELECT c.des_clase, p.img FROM clase c
                INNER JOIN producto p ON p.cod_clase = c.cod_clase
                WHERE c.status = 1 AND c.des_clase != "NO ASIGNADO"
                GROUP BY c.cod_clase;';
        $this->conectarDB();
        $new = $this->con->prepare($query); 
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);
        $this->desconectarDB();
        die(json_encode($data));

      } catch (\PDOException $e) {
        die($e);
      }

    }


  }

?>