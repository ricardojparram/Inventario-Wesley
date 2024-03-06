<?php 

    namespace modelo;
    use config\connect\DBConnect as DBConnect;

    class pago extends DBConnect{

        private $cedula;
        private $nombre;
        private $apellido;
        private $direccionF;
        private $telefono;
        private $correo;
        private $sede;
        private $direccionE;
        private $detalles;



        public function tipoP($tipo){
            try {
                switch ($tipo) {
                    case '4':
                        $query = "SELECT * from banco c INNER JOIN datos_cobro_farmacia d on c.id_banco = d.id_banco WHERE d.num_cuenta is NULL and c.status =1;";
                        break;

                     case '5':
                        $query = "SELECT * from banco c INNER JOIN datos_cobro_farmacia d on c.id_banco = d.id_banco WHERE d.telefono is NULL and c.status = 1;";
                        break;
                    
                    default:
                        die();
                        break;
                }
                parent::conectarDB();
                $new = $this->con->prepare($query);
                $new->execute();
                $data = $new->fetchAll();
                echo json_encode($data);
                parent::desconectarDB();
                die();
            } catch (\PDOException $error){
                return $error;
              }
        }

        public function mostrarDatosP($cedula){
            try {
                $this->key = parent::KEY();
                $this->iv = parent::IV();
                $this->cipher = parent::CIPHER();

                parent::conectarDB();
                $new = $this->con->prepare("SELECT c.nombre, c.apellido, c.cedula, c.direccion, cc.correo, cc.celular FROM cliente c INNER JOIN contacto_cliente cc ON c.cedula = cc.cedula WHERE status = 1 and c.cedula = ?");
                $new->bindValue(1, $cedula);
                $new->execute();
                $data = $new->fetchAll(\PDO::FETCH_OBJ);
                
                parent::desconectarDB();
                if(isset($data[0]->cedula)){ 
                    foreach ($data as $item) {
                       $item->cedula = openssl_decrypt($item->cedula, $this->cipher, $this->key, 0, $this->iv);
                       $item->direccion = openssl_decrypt($item->direccion, $this->cipher, $this->key, 0, $this->iv);
                       $item->celular = openssl_decrypt($item->celular, $this->cipher, $this->key, 0, $this->iv);
                       $item->correo = openssl_decrypt($item->correo, $this->cipher, $this->key, 0, $this->iv);
                   }
                    echo json_encode($data);
                }elseif (!isset($data[0]->cedula)) {
                    parent::conectarDB();
                    $new = $this->con->prepare("SELECT u.cedula, u.nombre, u.apellido, u.correo FROM usuario u WHERE u.cedula = ?");
                    $new->bindValue(1, $cedula);
                    $new->execute();
                    $data = $new->fetchAll(\PDO::FETCH_OBJ);
                    foreach ($data as $item) {
                        $item->cedula = openssl_decrypt($item->cedula, $this->cipher, $this->key, 0, $this->iv);
                        $item->correo = openssl_decrypt($item->correo, $this->cipher, $this->key, 0, $this->iv);
                    }
                    echo json_encode($data);
                    parent::desconectarDB();
                    
                }

                die();
                
            } catch(\PDOException $error){
                die($error);
            }
        }

        public function mostrarPrecio($cedula){
            try {
                parent::conectarDB();
                $new = $this->con->prepare('SELECT
                    (SELECT SUM(round(car.cantidad*p.p_venta)) FROM carrito car INNER JOIN producto p ON car.cod_producto = p.cod_producto WHERE car.cedula = ?) AS total,
                    (SELECT COUNT(*) FROM carrito car WHERE car.cedula = ?) AS cuenta,
                    (SELECT c.cambio FROM cambio c INNER JOIN moneda m ON c.moneda = m.id_moneda  WHERE c.status = 1 AND m.nombre = "Dolar" or m.nombre = "dolar"ORDER BY c.fecha DESC LIMIT 1) AS cambio,
                    (SELECT c.id_cambio FROM cambio c INNER JOIN moneda m ON c.moneda = m.id_moneda  WHERE c.status = 1 AND m.nombre = "Dolar" or m.nombre = "dolar"ORDER BY c.fecha DESC LIMIT 1) AS id_cambio');
                $new->bindValue(1, $cedula);
                $new->bindValue(2, $cedula);
                $new->execute();
                $data = $new->fetchAll();
                echo json_encode($data);
                parent::desconectarDB();
                die();

            } catch(\PDOException $error){
                return $error;
            }
        }

        public function mostrarEstados(){
            try {
                parent::conectarDB();
                $new = $this->con->prepare('SELECT * FROM `estados_venezuela` ORDER BY nombre ASC');
                $new->execute();
                $data = $new->fetchAll();
                echo json_encode($data);
                parent::desconectarDB();
                die();

            } catch(\PDOException $error){
                return $error;
            }
        }

        public function mostrarSede($estado){
            try {
                parent::conectarDB();
                $new = $this->con->prepare('SELECT * FROM sede_envio WHERE id_estado = ? and status = 1');
                $new->bindValue(1, $estado);
                $new->execute();
                $data = $new->fetchAll();
                echo json_encode($data);
                parent::desconectarDB();
                die();

            } catch(\PDOException $error){
                return $error;
            }
        }

        public function getMostrarMetodo(){
            try{
              parent::conectarDB();
              $new = $this->con->prepare("SELECT * FROM tipo_pago WHERE status = 1 and online = 1");
              $new->execute();
              $data = $new->fetchAll(\PDO::FETCH_OBJ);
              echo json_encode($data);
              parent::desconectarDB();
              die();
      
            }catch(\PDOException $error){
      
             return $error;   
      
           }   
        }

        public function banco(){
            try{
                parent::conectarDB();
                $new = $this->con->prepare("SELECT * FROM banco WHERE status = 1");
                $new->execute();
                $data = $new->fetchAll(\PDO::FETCH_OBJ);
                echo json_encode($data);
                parent::desconectarDB();
                die();
        
              }catch(\PDOException $error){
        
               return $error;   
        
             }   
        }

        public function getRegistar($cedula, $nombre, $apellido, $direccionF, $telefono, $correo, $sede, $direccionE, $detalles){

            if(preg_match_all("/^[a-zA-Z]{0,30}$/", $nombre) == false){
                $resultado = ['resultado' => 'Error de nombre' , 'error' => 'Nombre inválido.'];
                echo json_encode($resultado);
                die();
            }
            if(preg_match_all("/^[a-zA-Z]{0,30}$/", $apellido) == false){
                $resultado = ['resultado' => 'Error de apellido' , 'error' => 'Apellido inválido.'];
                echo json_encode($resultado);
                die();
            }
            if(preg_match_all("/^[0-9]{7,10}$/", $cedula) == false){
                $resultado = ['resultado' => 'Error de cedula' , 'error' => 'Cédula inválida.'];
                echo json_encode($resultado);
                die();
            }
            if(preg_match_all("/[$%&|<>]/", $direccionF) == true){
                $resultado = ['resultado' => 'Error de direccion factura' , 'error' => 'Direccion inválida.'];
                echo json_encode($resultado);
                die();
            }
            if(preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $correo) == false){
                $resultado = ['resultado' => 'Error de email' , 'error' => 'Correo invalida.'];
                echo json_encode($resultado);
                die();
            }
            if(preg_match_all("/[$%&|<>]/", $direccionE) == true){
                $resultado = ['resultado' => 'Error de direccion entrega' , 'error' => 'Direccion inválida.'];
                echo json_encode($resultado);
                die();
            }


            $this->cedula = $cedula;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->direccionF = $direccionF;
            $this->telefono = $telefono;
            $this->correo = $correo;
            $this->sede = $sede;
            $this->direccionE = $direccionE;
            $this->detalles = $detalles;
            $this->registrar();
          }

          private function registrar(){
            try {
                $this->key = parent::KEY();
                $this->iv = parent::IV();
                $this->cipher = parent::CIPHER();
                
                $this->cedula = openssl_encrypt($this->cedula, $this->cipher, $this->key, 0, $this->iv);
                $this->correo = openssl_encrypt($this->correo, $this->cipher, $this->key, 0, $this->iv);
                $this->direccionF = openssl_encrypt($this->direccionF, $this->cipher, $this->key, 0, $this->iv);
                $this->telefono = openssl_encrypt($this->telefono, $this->cipher, $this->key, 0, $this->iv);
                parent::conectarDB();
                $new = $this->con->prepare("SELECT cedula FROM cliente WHERE cedula = ?");
                $new->bindValue(1, $this->cedula);
                $new->execute();
                $data = $new->fetchAll();
               
               
                parent::desconectarDB();

                if(isset($data[0]["cedula"])){
                    
                    parent::conectarDB();
                    $new = $this->con->prepare("UPDATE cliente c INNER JOIN contacto_cliente cc ON c.cedula = cc.cedula SET c.cedula = ?, c.nombre = ?, c.apellido = ?, c.direccion = ?, cc.celular= ?, cc.correo = ?, cc.cedula = ?, c.status = 1 WHERE c.cedula = ?");
                    $new->bindValue(1, $this->cedula);
                    $new->bindValue(2, $this->nombre);
                    $new->bindValue(3, $this->apellido);
                    $new->bindValue(4, $this->direccionF);
                    $new->bindValue(5, $this->telefono);
                    $new->bindValue(6, $this->correo);
                    $new->bindValue(7, $this->cedula);
                    $new->bindValue(8, $this->cedula);
                    $new->execute();
                    parent::desconectarDB();
                   
                }else {
                    parent::conectarDB();
                    $new = $this->con->prepare("INSERT INTO cliente(cedula, nombre, apellido, direccion, status) VALUES (?,?,?,?,1)");
                    $new->bindValue(1, $this->cedula);
                    $new->bindValue(2, $this->nombre);
                    $new->bindValue(3, $this->apellido);
                    $new->bindValue(4, $this->direccionF);
                    $new->execute();

                    $new = $this->con->prepare("INSERT INTO contacto_cliente(id_contacto, celular, correo, cedula) VALUES (DEFAULT,?,?,?)");
                    $new->bindValue(1, $this->telefono);
                    $new->bindValue(2, $this->correo); 
                    $new->bindValue(3, $this->cedula);
                    $new->execute();
                   
                    parent::desconectarDB();
                }

                parent::conectarDB();
                if($this->sede != "" || $this->sede != NULL){

                    $new = $this->con->prepare("INSERT INTO envio(id_envio, id_sede, status) VALUES (DEFAULT, ?, 3)");
                    $new->bindValue(1, $this->sede);
                    $new->execute();

                    $id_envio = $this->con->lastinsertid();

                    $new = $this->con->prepare("INSERT INTO venta(num_fact, fecha, cedula_cliente, direccion, id_envio, online, status) 
                                                VALUES (DEFAULT, DEFAULT, ?, NULL, ?, 1, 1)");
                    $new->bindValue(1, $this->cedula);
                    $new->bindValue(2, $id_envio);
                    $new->execute();
        
                }elseif ($this->direccionE != NULL || $this->direccionE != "") {

                    $new = $this->con->prepare("INSERT INTO venta(num_fact, fecha, cedula_cliente, direccion, id_envio, online, status) 
                                                VALUES (DEFAULT, DEFAULT, ?, ?, NULL, 1, 1)");
                    $new->bindValue(1, $this->cedula);
                    $new->bindValue(2, $this->direccionE);
                    $new->execute();

                }else{

                    $new = $this->con->prepare("INSERT INTO venta(num_fact, fecha, cedula_cliente, direccion, id_envio, online, status) 
                                                VALUES (DEFAULT, DEFAULT, ?, NULL, NULL, 1, 1)");
                    $new->bindValue(1, $this->cedula);
                    $new->execute();
                }
                $numFactura = $this->con->lastInsertId();

                $new = $this->con->prepare("SELECT c.cod_producto, c.cantidad, p.p_venta as precioActual FROM carrito c INNER JOIN producto p ON c.cod_producto = p.cod_producto WHERE cedula = ?;");
                $new->bindValue(1, $this->cedula);
                $new->execute();
                $data = $new->fetchAll();

                foreach ($data as $dato) {
                    $new = $this->con->prepare("INSERT INTO venta_producto(num_fact, cod_producto, cantidad, precio_actual) 
                                                VALUES (?, ?, ?, ?)");
                    $new->bindValue(1, $numFactura);
                    $new->bindValue(2, $dato['cod_producto']);
                    $new->bindValue(3, $dato['cantidad']);
                    $new->bindValue(4, $dato['precioActual']);
                    $new->execute();
                }

                $new = $this->con->prepare("DELETE FROM carrito WHERE cedula = ?");
                $new->bindValue(1, $this->cedula);
                $new->execute();

                $monto = array_column($this->detalles, 'monto');
                $totalMonto = array_sum($monto);
                

                    $new = $this->con->prepare("INSERT INTO pago(id_pago, monto_total, num_fact, status) 
                                            VALUES (DEFAULT, ?, ?, 2)");
                    $new->bindValue(1, $totalMonto);
                    $new->bindValue(2, $numFactura);
                    $new->execute();
                    $idPago = $this->con->lastInsertId();

                
                    

                foreach($this->detalles as $deta){
                    try{
                        $deta['bancoReceptor'] = ($deta['bancoReceptor'] == " ") ? NULL : $deta['bancoReceptor'] ;
                        $deta['bancoEmisor'] = ($deta['bancoEmisor'] == " ") ? NULL : $deta['bancoEmisor'] ;
                        $deta['referencia'] = ($deta['referencia'] == " ") ? NULL : $deta['referencia'] ;

                        $new = $this->con->prepare("INSERT INTO detalle_pago(id_pago, id_tipo_pago, id_datos_cobro, id_banco_cliente, monto_pago, id_cambio, referencia) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $new->bindValue(1, $idPago);
                        $new->bindValue(2, $deta['tipo']);
                        $new->bindValue(3, $deta['bancoReceptor']);
                        $new->bindValue(4, $deta['bancoEmisor']);
                        $new->bindValue(5, $deta['monto']);
                        $new->bindValue(6, $deta['cambio']);
                        $new->bindValue(7, $deta['referencia']);
                        $new->execute();

                    }catch(\PDOException $e){
                        die($e);
                    }

                }
                    $this->eliminar("registrar");
                    
                    $this->cedula = openssl_decrypt($this->cedula, $this->cipher, $this->key, 0, $this->iv);
                    $resultado = ['resultado' => 'Registrado Pedido', 'cedula' => $this->cedula , 'nombre' =>  $this->nombre , 'apellido' => $this->apellido];
                    echo json_encode($resultado);
    
                    die();
                
            } catch (\PDOException $error) {
                die($error);
            }
          }


        private function validarCarrito($cedula){
            try {
                parent::conectarDB();
                $new = $this->con->prepare("SELECT COUNT(*) as cuenta FROM carrito WHERE cedula = ?");
                $new->bindValue(1, $cedula);
                $new->execute();
                $data = $new->fetchAll(\PDO::FETCH_OBJ);
                parent::desconectarDB();
                if(isset($data[0])) return ($data[0]->cuenta >= 1);
                return false;

            } catch (\PDOException $e) {
                die($e);
            }
        }

        public function getComprobarEstadoPago($cedula){


            $this->cedula = $cedula;
            $this->nombre = $_SESSION['nombre'];
            $this->apellido = $_SESSION['apellido'];
        

            if($this->validarCarrito($this->cedula) != true){
                die("<script> window.location = '?url=carrito' </script>");
            }

            $this->comprobarEstadoPago();
        }

        private function comprobarEstadoPago(){
            try{


                parent::conectarDB();
                $new = $this->con->prepare("SELECT cedula FROM cliente WHERE cedula = ?");
                $new->bindValue(1, $this->cedula);
                $new->execute();
                [0 => $data] = $new->fetchAll(\PDO::FETCH_OBJ);
                
                
                parent::desconectarDB();

                if(!isset($data->cedula)){
                    parent::conectarDB();
                    $new = $this->con->prepare("INSERT INTO cliente(cedula, nombre, apellido, direccion, status) VALUES (?,?,?,?,0)");
                    $new->bindValue(1, $this->cedula);
                    $new->bindValue(2, $this->nombre);
                    $new->bindValue(3, $this->apellido);
                    $new->bindValue(4, "");
                    $new->execute();

                    $new = $this->con->prepare("INSERT INTO contacto_cliente(cedula) VALUES (?)");
                    $new->bindValue(1, $this->cedula);
                    $new->execute();
                    parent::desconectarDB();
                }
                parent::conectarDB();
                $new = $this->con->prepare("SELECT status FROM venta WHERE cedula_cliente = ? AND online = 1 AND status = 2");
                $new->bindValue(1, $this->cedula);
                $new->execute();
                $data = $new->fetchAll(\PDO::FETCH_OBJ);
                parent::desconectarDB();

                if(!isset($data[0]->status)){
                    parent::conectarDB();
                    $new = $this->con->prepare("SELECT c.cod_producto, c.cantidad, p.p_venta FROM carrito c
                                                INNER JOIN producto p ON p.cod_producto = c.cod_producto
                                                WHERE cedula = ?");
                    $new->bindValue(1, $this->cedula);
                    $new->execute();
                    $carrito = $new->fetchAll(\PDO::FETCH_OBJ);

                    $new = $this->con->prepare("INSERT INTO venta(num_fact, fecha, cedula_cliente, direccion, id_envio, online, status) 
                        VALUES (DEFAULT, DEFAULT, ?, NULL, NULL, 1, 2)");
                    $new->bindValue(1, $this->cedula);
                    $new->execute();
                    $num_fact = $this->con->lastInsertId();
                    parent::desconectarDB();
                    foreach($carrito as $producto){
                        parent::conectarDB();
                        $new = $this->con->prepare("INSERT INTO venta_producto(num_fact, cod_producto, cantidad, precio_actual) 
                            VALUES (?,?,?,?)");
                        $new->bindValue(1, $num_fact);
                        $new->bindValue(2, $producto->cod_producto);
                        $new->bindValue(3, $producto->cantidad);
                        $new->bindValue(4, $producto->p_venta);
                        $new->execute();
                        parent::desconectarDB();
                       
                        $this->actualizarStockProducto($producto->cod_producto , $producto->cantidad, "restar");
                    }

                }


            }catch(\PDOException $e) {
                die($e);
            }
        }

        public function comprobarTiempoDePago($cedula){
            
            
            try {
    
                if($cedula === NULL){
                    parent::conectarDB();
                    $new = $this->con->prepare("SELECT num_fact, cedula_cliente, fecha as fecha_venta, TIMESTAMP(NOW()) as fecha_actual 
                                            FROM venta WHERE online = 1 AND status = 2;");
                    $new->execute();
                    $ventas = $new->fetchAll(\PDO::FETCH_OBJ);
                    parent::desconectarDB();
                }else {
                    parent::conectarDB();
                    

                    $new = $this->con->prepare("SELECT num_fact, cedula_cliente, fecha as fecha_venta, TIMESTAMP(NOW()) as fecha_actual 
                                            FROM venta WHERE online = 1 AND status = 2 AND cedula_cliente = ?;");
                    $new->bindValue(1, $cedula);
                    $new->execute();
                    $ventas = $new->fetchAll(\PDO::FETCH_OBJ);
                    parent::desconectarDB();
                }

                foreach($ventas as $venta){
                    $hora_limite = strtotime($venta->fecha_venta) + 3600; // 3600 = 1hora en segundos
                    $hora_actual = strtotime($venta->fecha_actual);
                    if($hora_actual > $hora_limite){
                        parent::conectarDB();
                        $new = $this->con->prepare("SELECT cantidad, cod_producto FROM venta_producto WHERE num_fact = ?");
                        $new->bindValue(1, $venta->num_fact);
                        $new->execute();
                        $productos = $new->fetchAll(\PDO::FETCH_OBJ);
                        parent::desconectarDB();
                        foreach($productos as $producto){
                            $this->actualizarStockProducto($producto->cod_producto, $producto->cantidad, "sumar");
                        }
                        parent::conectarDB();
                        $new = $this->con->prepare("DELETE FROM venta WHERE num_fact = ?");
                        $new->bindValue(1, $venta->num_fact);
                        $new->execute();
                        parent::desconectarDB();
                        
                        if($cedula != NULL) {
                            $resultado = ['resultado' => 'Eliminado correctamente.'];
                            echo json_encode($resultado);
                            die();
                        }
                    }
                }
                die();


            } catch (\PDOException $e) {
                die($e);
            }
        }

        private function actualizarStockProducto($cod_producto , $cantidad, $accion){
            try{
                parent::conectarDB();
                $new = $this->con->prepare("SELECT stock FROM producto WHERE cod_producto = ? and status = 1");
                $new->bindValue(1, $cod_producto);
                $new->execute();
                parent::desconectarDB();
                [0 => $data] = $new->fetchAll(\PDO::FETCH_OBJ);

                $stock = ($accion === "sumar") ? $data->stock + $cantidad : $data->stock - $cantidad;
                parent::conectarDB();
                $new = $this->con->prepare("UPDATE producto SET stock = ? WHERE cod_producto = ? and status = 1");
                $new->bindValue(1, $stock);
                $new->bindValue(2, $cod_producto);
                $new->execute();
                parent::desconectarDB();
            }catch(\PDOException $error){
                die($error);
            }
        }

        public function calcularTipo($cedula){
            try{
                parent::conectarDB();
                $new = $this->con->prepare("SELECT p.cod_tipo, SUM(c.cantidad) AS cuenta FROM carrito c INNER JOIN producto p ON c.cod_producto = p.cod_producto WHERE c.cedula = ? GROUP by p.cod_tipo");
                $new->bindValue(1, $cedula);
                $new->execute();
                $data = $new->fetchAll(\PDO::FETCH_OBJ);
                parent::desconectarDB();

                foreach ($data as $item) {
                    if ($item->cuenta > 3) {
                        $resultado = ['resultado' => 'cuenta superior'];
                        echo json_encode($resultado);
                        die();
                    }
                }
                $resultado = ['resultado' => 'cuenta regulada'];
                echo json_encode($resultado);
                die();
                
            }catch(\PDOException $error){
                die($error);
            }
        }

        public function temporizador($cedula,$venta = false){
            try {
                parent::conectarDB();
                $new = $this->con->prepare("SELECT fecha FROM venta WHERE online = 1 AND status = 2 AND cedula_cliente = ?");
                $new->bindValue(1, $cedula);
                $new->execute();
                $data = $new->fetchAll(\PDO::FETCH_OBJ);
                parent::desconectarDB();
                echo json_encode($data[0]->fecha);
                die();
            } catch(\PDOException $error){
                die($error);
            }
        }

        public function getEliminar($cedula, $eliminar){
            $this->cedula = $cedula;
            $this->eliminar($eliminar);
        }

        private function eliminar($carrito = "contar"){
            try {
                    parent::conectarDB();
    
                    $new = $this->con->prepare("SELECT num_fact FROM venta WHERE online = 1 AND status = 2 AND cedula_cliente = ?;");
                    $new->bindValue(1, $this->cedula);
                    $new->execute();
                    $venta = $new->fetchAll();
                    parent::desconectarDB();
                    
                    if ($carrito == "sumar") {
                    parent::conectarDB();
                    $new = $this->con->prepare("SELECT cantidad, cod_producto FROM venta_producto WHERE num_fact = ?");
                    $new->bindValue(1, $venta[0]['num_fact']);
                    $new->execute();
                    $productos = $new->fetchAll(\PDO::FETCH_OBJ);
                    parent::desconectarDB();
                        foreach($productos as $producto){
                            $this->actualizarStockProducto($producto->cod_producto, $producto->cantidad, "sumar");
                        }
                    }
                    
                    parent::conectarDB();
                    $new = $this->con->prepare("DELETE FROM venta WHERE num_fact = ?");
                    $new->bindValue(1, $venta[0]['num_fact']);
                    $new->execute();
                    
                    parent::desconectarDB();

                    if ($carrito != "registrar") {
                        $resultado = ['resultado' => 'Eliminado'];
                        echo json_encode($resultado);
                        die();
                    }

            } catch(\PDOException $error){
                die($error);
            }
        }

    }
?>