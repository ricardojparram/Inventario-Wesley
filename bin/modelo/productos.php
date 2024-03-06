<?php

  namespace modelo;
  use config\connect\DBConnect as DBConnect;


  class productos extends DBConnect{

   private $id;
   private $fechaV;
   private $composicionP;
   private $posologia;
   private $laboratorio;
   private $tipoP;
   private $clase;
   private $presentación;
   private $ubicación;
   private $contraIn;
   private $cantidad;
   private $precioV;
   private $descripcion;
   private $nombre;
   private $img;
   private $codigo;
   private $imagenPorDefecto = 'assets/img/productos/producto_imagen.png';



   function __construct(){
    parent::__construct();
  }


  public function getRegistraProd($codigo, $descripcion, $fechaV ,$composicionP, $posologia , $ubicación , $laboratorio , $presentación  , $tipoP , $clase , $contraIn, $cantidad, $precioV){

    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,200}$/", $descripcion) !== 1){
      return ['resultado' => 'error', 'error' => 'Descripcion inválida'];
    }
    if(preg_match_all("/^([0-9]{4}\-[0-9]{2}\-[0-9]{2})$/", $fechaV) !== 1){
      return ['resultado' => 'error', 'error' => 'Fecha vencimiento inválida'];
    }
    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,50}$/", $composicionP) !== 1){
      return ['resultado' => 'error','error' => 'Composicion inválida.'];
    }
    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,400}$/", $posologia) !== 1){
      return ['resultado' => 'error','error' => 'Posologia inválida.'];
    }
    if(preg_match_all("/^[a-fA-F0-9]{10}$/", $laboratorio) != 1){
      return ['resultado' => 'error','error' => 'Laboratorio inválido.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $tipoP) !== 1){
      return ['resultado' => 'error','error' => 'Tipo inválido.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $clase) !== 1){
      return ['resultado' => 'error','error' => 'Clase inválida.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $presentación) !== 1){
      return ['resultado' => 'error','error' => 'Presentación inválida.'];
    }
    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,100}$/", $ubicación) !== 1){
      return ['resultado' => 'error','error' => 'Ubicación inválida.'];
    }
    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,400}$/", $contraIn) !== 1){
      return ['resultado' => 'error','error' => 'Contraindicaciones inválidas.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $cantidad) !== 1){
      return ['resultado' => 'error','error' => 'Cantidad inválida.'];
    }
    if(preg_match_all("/^([0-9]+\.+[0-9]|[0-9])+$/", $precioV) !== 1){
      return ['resultado' => 'error','error' => 'Precio inválido.'];
    }

    date_default_timezone_set("america/caracas");
    $time = date("Y-m-d");

    if(strftime($time) > strftime($fechaV)){
      return ['resultado' => 'error', 'error' => 'La fecha es menor'];
    }

    $this->codigo = $codigo;
    $this->descripcion = $descripcion;
    $this->composicionP = $composicionP;
    $this->fechaV = $fechaV;
    $this->contraIn = $contraIn;
    $this->ubicación = $ubicación;
    $this->posologia = $posologia;
    $this->cantidad = $cantidad;
    $this->precioV = $precioV;

    $this->laboratorio = $laboratorio;
    $this->tipoP = $tipoP;
    $this->clase = $clase;
    $this->presentación = $presentación;  


    $this->registraProd();

  }

  private function registraProd(){
   try{
    parent::conectarDB();

    $new = $this->con->prepare("INSERT INTO `producto`(`codigo`, `descripcion`, `ubicacion`, `composicion`, `contraindicaciones`, `posologia`, `vencimiento`, `p_venta`, `stock`, `cod_lab`, `cod_tipo`, `cod_clase`, `cod_pres`,`img`,`status`) VALUES ( ? , ? , ? , ? , ? , ? ,? , ? , ? , ? , ? , ? , ?, ?, 1)");

    $new->bindValue(1, $this->codigo);
    $new->bindValue(2, $this->descripcion);
    $new->bindValue(3, $this->ubicación);
    $new->bindValue(4, $this->composicionP);
    $new->bindValue(5, $this->contraIn);
    $new->bindValue(6, $this->posologia);
    $new->bindValue(7, $this->fechaV);
    $new->bindValue(8, $this->precioV);
    $new->bindValue(9, $this->cantidad);
    $new->bindValue(10, $this->laboratorio);
    $new->bindValue(11, $this->tipoP);
    $new->bindValue(12, $this->clase);
    $new->bindValue(13, $this->presentación); 
    $new->bindValue(14, $this->imagenPorDefecto); 

    $new->execute();

    $result = ['resultado' => 'Registrado'];
    parent::desconectarDB();
    return $result;

  }catch(\PDOexection $error) {
   die($error);

  }
  }




  public function MostrarEditProductos($id){
    try{
      parent::conectarDB();
      $this->id = $id;
      $new = $this->con->prepare("SELECT `cod_producto`, `codigo` , `descripcion`, `ubicacion`, `composicion`, `contraindicaciones`, `posologia`, `vencimiento`, `p_venta`, `stock`, `cod_lab`, `cod_tipo`, `cod_clase`, `cod_pres`,  `status` FROM producto p WHERE p.status = 1 and p.cod_producto = ?");
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;

    }catch(\PDOexection $error){

     return $error;   
   } 
  }



  public function getEditarProd($codigoEd, $descripcionEd, $fechaEd ,$composicionEd,$posologiaEd,$ubicacionEd,$laboratorioEd,$presentacionEd,$tipoEd,$claseEd,$contraInEd,$cantidadEd,$VentaEd,$id){

    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,200}$/", $descripcionEd) !== 1){
      return ['resultado' => 'error', 'error' => 'Descripcion inválida'];
    }
    if(preg_match_all("/^([0-9]{4}\-[0-9]{2}\-[0-9]{2})$/", $fechaEd) !== 1){
      return ['resultado' => 'error', 'error' => 'Fecha vencimiento inválida'];
    }
    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,50}$/", $composicionEd) !== 1){
      return ['resultado' => 'error','error' => 'Composicion inválida.'];
    }
    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,400}$/", $posologiaEd) !== 1){
      return ['resultado' => 'error','error' => 'Posologia inválida.'];
    }
    if(preg_match_all("/^[a-fA-F0-9]{10}$/", $laboratorioEd) != 1){
      return ['resultado' => 'error','error' => 'Laboratorio inválido.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $tipoEd) !== 1){
      return ['resultado' => 'error','error' => 'Tipo inválido.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $claseEd) !== 1){
      return ['resultado' => 'error','error' => 'Clase inválida.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $presentacionEd) !== 1){
      return ['resultado' => 'error','error' => 'Presentación inválida.'];
    }
    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,100}$/", $ubicacionEd) !== 1){
      return ['resultado' => 'error','error' => 'Ubicación inválida.'];
    }
    if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,400}$/", $contraInEd) !== 1){
      return ['resultado' => 'error','error' => 'Contraindicaciones inválidas.'];
    }
    if(preg_match_all("/^[0-9]{1,10}$/", $cantidadEd) !== 1){
      return ['resultado' => 'error','error' => 'Cantidad inválida.'];
    }
    if(preg_match_all("/^([0-9]+\.+[0-9]|[0-9])+$/", $VentaEd) !== 1){
      return ['resultado' => 'error','error' => 'Precio inválido.'];
    }

    $this->id = $id;
    $this->codigo = $codigoEd;
    $this->descripcion = $descripcionEd;
    $this->composicionP = $composicionEd;
    $this->fechaV = $fechaEd;
    $this->contraIn = $contraInEd;
    $this->ubicación = $ubicacionEd;
    $this->posologia = $posologiaEd;
    $this->cantidad = $cantidadEd;
    $this->precioV = $VentaEd;  
    $this->laboratorio = $laboratorioEd;
    $this->tipoP = $tipoEd;
    $this->clase = $claseEd;
    $this->presentación = $presentacionEd; 

    return $this->editarProd();

  }


  private function editarProd(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("UPDATE producto p SET `codigo`= ? ,`descripcion`= ? ,`ubicacion`= ? ,`composicion`= ? ,`contraindicaciones`= ? ,`posologia`= ? ,`vencimiento`= ? ,`p_venta`= ?,`stock`= ? ,`cod_lab`= ? ,`cod_tipo`= ? ,`cod_clase`= ? ,`cod_pres`= ? WHERE p.status = 1 AND p.cod_producto = ?");

      $new->bindValue(1, $this->codigo);
      $new->bindValue(2, $this->descripcion);
      $new->bindValue(3, $this->ubicación);
      $new->bindValue(4, $this->composicionP);   
      $new->bindValue(5, $this->contraIn);
      $new->bindValue(6, $this->posologia);
      $new->bindValue(7, $this->fechaV);
      $new->bindValue(8, $this->precioV);
      $new->bindValue(9, $this->cantidad);
      $new->bindValue(10, $this->laboratorio);
      $new->bindValue(11, $this->tipoP);
      $new->bindValue(12, $this->clase); 
      $new->bindValue(13, $this->presentación);
      $new->bindValue(14, $this->id);
      $new->execute();

      $resultado = ['resultado' => 'Editado'];
      parent::desconectarDB();
      return $resultado;

    }catch(\PDOexection $error) {
     $error;

   }
  }


  public function getEliminarProd($id){
   try{
    parent::conectarDB();
    $this->id = $id;
    $new = $this->con->prepare("UPDATE `producto` SET `status`= 0 WHERE cod_producto = ?");
    $new->bindValue(1, $this->id);
    $new->execute();
    $data = $new->fetchAll(\PDO::FETCH_OBJ);
    parent::desconectarDB();
    return $data;
  }
  catch(\PDOexection $error){
    return $error;
  }
  }

  public function MostrarProductos(){
    try{
      parent::conectarDB();
      $query = "SELECT `cod_producto`, `codigo`, `descripcion`, `vencimiento`, format(p_venta,2,'de_DE') as venta, `stock`, t.des_tipo FROM producto p INNER JOIN tipo t ON t.cod_tipo = p.cod_tipo WHERE p.status = 1";

      $new = $this->con->prepare($query);
      $new->execute();
      $data = $new->fetchAll();
      parent::desconectarDB();
      return $data;
    }catch(\PDOexection $error){

     return $error;   
   } 
  } 


  public function mostrarLaboratorio(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("SELECT * FROM laboratorio l WHERE l.status = 1");
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;
    }catch(\PDOexection $error){

     return $error;   
   } 
  }


  public function mostrarTipo(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("SELECT * FROM tipo t WHERE t.status = 1");
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;

    }catch(\PDOexection $error){

     return $error;   
   } 
  }

  public function mostrarPresentacion(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("SELECT * FROM presentacion p WHERE p.status = 1");
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;

    }catch(\PDOexection $error){

     return $error;   
   } 
  }

  public function mostrarClase(){
    try{
      parent::conectarDB();
      $new = $this->con->prepare("SELECT * FROM clase c WHERE c.status = 1");
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;

    }catch(\PDOexection $error){

     return $error;   
   } 
  }

  public function mostrarImg($id){
    $this->id = $id;

    return $this->productImg();
  }

  private function productImg(){
    try {
      parent::conectarDB();
      $sql = "SELECT img FROM producto where cod_producto = ?";
      $new = $this->con->prepare($sql);
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      parent::desconectarDB();
      return $data;

    } catch (\PDOException $e) {
      die($e);
    }
  }

  public function getEditarImg($foto, $id, $borrar = false){
 
    if(preg_match_all("/^[0-9]{1,10}$/", $id) == false){
      return ['resultado' => 'error' , 'error' => 'Producto inválido.'];
    }

    $this->foto = $foto;
    $this->id = $id;

    $res;
    if($borrar != false) $res = $this->borrarImagen();
    if(isset($this->foto['name'])) $res = $this->editarImagen();
    return $res;

  }

  private function editarImagen(){

    if($this->foto['error'] > 0){
      return ['respuesta' => 'error', 'error' => 'Error de imágen'];
    }
    if($this->foto['type'] != 'image/jpeg' && $this->foto['type'] != 'image/jpg' && $this->foto['type'] != 'image/png'){
      return ['respuesta' => 'error', 'error' => 'Tipo de imagen inválido.'];
    }

    $repositorio = "assets/img/productos/";
    $extension = pathinfo($this->foto['name'], PATHINFO_EXTENSION);
    $date = date('m/d/Yh:i:sa', time());
    $rand = rand(1000,9999);
    $imgName = $date.$rand;
    $nameEnc = md5($imgName);
    $nombre =  $repositorio.$nameEnc.'.'.$extension;

    if(move_uploaded_file($this->foto['tmp_name'], $nombre)){
      try {
        parent::conectarDB();
        $new = $this->con->prepare('SELECT img FROM producto WHERE cod_producto = ?');
        $new->bindValue(1, $this->id);
        $new->execute();
        $data = $new->fetchAll(\PDO::FETCH_OBJ);
        $fotoActual = $data[0]->img;

        if($fotoActual != $this->imagenPorDefecto){
          unlink($fotoActual);        
        }

        $new = $this->con->prepare('UPDATE producto SET img = ? WHERE cod_producto = ?');
        $new->bindValue(1, $nombre);
        $new->bindValue(2, $this->id);
        $new->execute();
        parent::desconectarDB();

        return ['respuesta' => 'ok', 'msg' => "La imagen del producto se ha actualizado correctamente."];

      } catch (\PDOException $error) {
        return $error;
      }
    }else{
      return ['respuesta' => 'No se guardó la imagen.'];
    }

  }

  private function borrarImagen(){

    try {

      parent::conectarDB();
      $new = $this->con->prepare('SELECT img FROM producto WHERE cod_producto = ?');
      $new->bindValue(1, $this->id);
      $new->execute();
      $data = $new->fetchAll(\PDO::FETCH_OBJ);
      $fotoActual = $data[0]->img;

      $new = $this->con->prepare("UPDATE producto SET img = ? WHERE cod_producto = ?");
      $new->bindValue(1, $this->imagenPorDefecto);
      $new->bindValue(2, $this->id);
      $new->execute();
      parent::desconectarDB();

      if($fotoActual != $this->imagenPorDefecto){
        unlink($fotoActual);        
      }

      return ['respuesta' => 'ok', 'msg' => "La imagen ha sido eliminada correctamente."];

    } catch (\PDOException $e) {
      return $e;
    }

  }

  }

?>