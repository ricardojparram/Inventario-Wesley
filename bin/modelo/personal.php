<?php

    namespace modelo;

    use config\connect\DBConnect as DBConnect;

    class personal extends DBConnect
    {
        private $cedula;
        private $nombre;
        private $apellido;
        private $email;
        private $edad;
        private $direccion;
        private $telefono;
        private $sede;
        private $tipo;
        private $id;

        public function getAgregarPersonal($cedula, $nombre, $apellido, $email, $edad, $direccion, $telefono, $sede, $tipo){

            if (preg_match_all("/^[0-9]{7,10}$/", $cedula) == false) {
              $resultado = ['resultado' => 'error', 'error' => 'Cédula invalida.'];
              return $resultado;
            }
            if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $nombre) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Nombre invalido.'];
                return $resultado;
            }
            if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $apellido) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Apellido invalido.'];
                return $resultado;
            }
            if (preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Correo invalido.'];
                return $resultado;
            }
            if (preg_match_all("/^[0-9]{1,3}$/", $edad) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Sede invalida.'];
                return $resultado;
            }
            if (preg_match_all("/[$%&|<>]/", $direccion) == true) {
                $resultado = ['resultado' => 'error', 'error' => 'Direccion inválida.'];
                return $resultado;
            }
            if (preg_match_all("/^[0-9]{10,30}$/", $telefono) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Telefono Invalido'];
                return $resultado;
            }
            if (preg_match_all("/^[0-9]{1,2}$/", $sede) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Sede invalida.'];
                return $resultado;
            }
            if (preg_match_all("/^[0-9]{1,2}$/", $tipo) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Tipo de Empleado invalido.'];
                return $resultado;
            }

            $this->cedula = $cedula;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->email = $email;
            $this->edad = $edad;
            $this->direccion = $direccion;
            $this->telefono = $telefono;
            $this->sede = $sede;
            $this->tipo = $tipo;

            return $this->agregarPersonal();

        }

        private function agregarPersonal() {
            try {
                parent::conectarDB();
                $new = $this->con->prepare("SELECT `cedula`, `status` FROM `personal` WHERE `cedula` = ?");
                $new->bindValue(1, $this->cedula);
                $new->execute();
                $data = $new->fetchAll();
                parent::desconectarDB();

                if (!isset($data[0]['status'])) {

                    parent::conectarDB();
                    $new = $this->con->prepare("INSERT INTO `personal`(`cedula`, `nombres`, `apellidos`, `direccion`, `id_sede`, `edad`, `telefono`, `correo`, `tipo_em`, `status`) VALUES (?, ?; ?, ?, ?, ?, ?, ?, ?, 1)");
                    $new->bindValue(1, $this->cedula);
                    $new->bindValue(2, $this->nombre);
                    $new->bindValue(3, $this->apellido);
                    $new->bindValue(4, $this->direccion);
                    $new->bindValue(5, $this->sede);
                    $new->bindValue(6, $this->edad);
                    $new->bindValue(7, $this->telefono);
                    $new->bindValue(8, $this->correo);
                    $new->bindValue(9, $this->tipo);
                    $new->execute();
                    $resultado = ['resultado' => 'Registrado correctamente.'];
                    $this->binnacle($_SESSION['cedula'], "Registró un personal");
                    parent::desconectarDB();

                }   elseif ($data[0]['status'] == 0){

                    parent::conectarDB();
                    $new = $this->con->prepare("UPDATE `personal` SET `cedula`= ?,`nombres`= ?,`apellidos`= ?,`direccion`= ?,`id_sede`= ?,`edad`= ?,`telefono`= ?,`correo`= ?,`tipo_em`= ?,`status`= 1 WHERE `cedula` = ?");
                    $new->bindValue(1, $this->cedula);
                    $new->bindValue(2, $this->nombre);
                    $new->bindValue(3, $this->apellido);
                    $new->bindValue(4, $this->direccion);
                    $new->bindValue(5, $this->sede);
                    $new->bindValue(6, $this->edad);
                    $new->bindValue(7, $this->telefono);
                    $new->bindValue(8, $this->correo);
                    $new->bindValue(9, $this->tipo);
                    $new->bindValue(10, $this->cedula);
                    $new->execute();
                    $resultado = ['resultado' => 'Registrado correctamente.'];
                    $this->binnacle($_SESSION['cedula'], "Registró un personal");
                    parent::desconectarDB();
                }else {
                    $resultado = ['resultado' => 'error', 'error' => 'error desconocido.'];
                }
                    return $resultado;
            } catch (\PDOException $error) {
                return $error;
            }
        }

        public function getMostrarPersonal($bitacora = false){
            try {
                parent::conectarDB();
                $new = $this->con->prepare("SELECT p.cedula, p.nombres, p.apellidos, p.direccion, s.nombre as sede, e.nombre_e as tipo FROM personal p INNER JOIN sede s ON p.id_sede = s.id_sede INNER JOIN tipo_empleado e ON p.tipo_em = e.tipo_em WHERE p.status = 1");
                $new->execute();
                $data = $new->fetchAll(\PDO::FETCH_OBJ);
                if ($bitacora) $this->binnacle("", $_SESSION['cedula'], "Consultó listado Personal.");
                parent::desconectarDB();
                return $data;
            } catch (\PDOException $e) {
                return $e;
            }
      
        }

        public function getUnico($cedula){
            $this->cedula = $cedula;
            return $this->seleccionarUnico();
        }

        private function seleccionarUnico(){
            try {
                parent::conectarDB();
                $new = $this->con->prepare("SELECT p.cedula, p.nombres, p.apellidos, p.direccion, p.telefono, p.edad, p.correo, s.nombre as sede, e.nombre_e as tipo FROM personal p INNER JOIN sede s ON p.id_sede = s.id_sede INNER JOIN tipo_empleado e ON p.tipo_em = e.tipo_em WHERE p.cedula = ?");
                $new->bindValue(1, $this->cedula);
                $new->execute();
                $data = $new->fetchAll(\PDO::FETCH_OBJ);
                parent::desconectarDB();

                return $data;
            } catch (\PDOexection $error) {
                return $error;
            }
        }

        public function getEditarPersonal($cedula, $nombre, $apellido, $email, $edad, $direccion, $telefono, $sede, $tipo, $id){

            if (preg_match_all("/^[0-9]{7,10}$/", $cedula) == false) {
              $resultado = ['resultado' => 'error', 'error' => 'Cédula invalida.'];
              return $resultado;
            }
            if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $nombre) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Nombre invalido.'];
                return $resultado;
            }
            if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $apellido) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Apellido invalido.'];
                return $resultado;
            }
            if (preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Correo invalido.'];
                return $resultado;
            }
            if (preg_match_all("/^[0-9]{1,3}$/", $edad) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Sede invalida.'];
                return $resultado;
            }
            if (preg_match_all("/[$%&|<>]/", $direccion) == true) {
                $resultado = ['resultado' => 'error', 'error' => 'Direccion inválida.'];
                return $resultado;
            }
            if (preg_match_all("/^[0-9]{10,30}$/", $telefono) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Telefono Invalido'];
                return $resultado;
            }
            if (preg_match_all("/^[0-9]{1,2}$/", $sede) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Sede invalida.'];
                return $resultado;
            }
            if (preg_match_all("/^[0-9]{1,2}$/", $tipo) == false) {
                $resultado = ['resultado' => 'error', 'error' => 'Tipo de Empleado invalido.'];
                return $resultado;
            }

            $this->cedula = $cedula;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->email = $email;
            $this->edad = $edad;
            $this->direccion = $direccion;
            $this->telefono = $telefono;
            $this->sede = $sede;
            $this->tipo = $tipo;
            $this->id = $id;

            return $this->editarPersonal();

        }

        private function editarPersonal(){
            try {
                parent::conectarDB();
                $new = $this->con->prepare("UPDATE `personal` SET `cedula`= ?,`nombres`= ?,`apellidos`= ?,`direccion`= ?,`id_sede`= ?,`edad`= ?,`telefono`= ?,`correo`= ?,`tipo_em`= ?,`status`= 1 WHERE `cedula` = ?");
                $new->bindValue(1, $this->cedula);
                    $new->bindValue(2, $this->nombre);
                    $new->bindValue(3, $this->apellido);
                    $new->bindValue(4, $this->direccion);
                    $new->bindValue(5, $this->sede);
                    $new->bindValue(6, $this->edad);
                    $new->bindValue(7, $this->telefono);
                    $new->bindValue(8, $this->correo);
                    $new->bindValue(9, $this->tipo);
                    $new->bindValue(10, $this->id);
                    
                    $new->execute();
                $new->execute();
                $resultado = ['resultado' => 'Editado'];
                $this->binnacle("a", $_SESSION['cedula'], "Editó un personal");
                parent::desconectarDB();
            } catch (\PDOException $e) {
                return $e;
            }
        }

        public function getEliminarPersonal($cedula){
            $this->cedula = $cedula;
            return $this->eliminarPersonal();
        }

        private function eliminarPersonal(){
            try {
            parent::conectarDB();
            $new = $this->con->prepare("UPDATE `personal` SET `status` = '0' WHERE `personal`.`cedula` = ?"); //"DELETE FROM `personal` WHERE `personal`.`cedula` = ?"
            $new->bindValue(1, $this->cedula);
            $new->execute();
            $resultado = ['resultado' => 'Eliminado'];
            
            $this->binnacle("a", $_SESSION['cedula'], "Eliminó un personal");
            parent::desconectarDB();
            return $resultado;

            } catch (\PDOexection $error) {
            return $error;
            }
        }

        public function getValidarC($cedula, $id){
            $this->cedula = $cedula;
            $this->id = $id;
            return $this->validarC();
        }

        private function validarC(){
            try {
            
            if ($this->cedula == " ") {
                parent::conectarDB();
                $new = $this->con->prepare("SELECT `cedula` FROM `personal` WHERE `cedula` = ?");
                $new->bindValue(1, $this->id);
                $new->execute();
                $data = $new->fetchAll();
                parent::desconectarDB();
                if (isset($data[0]['cedula'])) {
                $resultado = ['resultado' => 'Correcto', 'msj' => 'La cédula está registrada.'];

                
                } else {
                $resultado = ['resultado' => 'Error', 'msj' => 'Cedula no Registrada'];
                
                }
            } elseif ($this->id == " ") {
                
                parent::conectarDB();
                $new = $this->con->prepare("SELECT `cedula` FROM `personal` WHERE `status`= 1 and `cedula` = ?");
                $new->bindValue(1, $this->cedula);
                $new->execute();
                $data = $new->fetchAll();
                parent::desconectarDB();
                if (isset($data[0]['cedula'])) {
                $resultado = ['resultado' => 'Error', 'msj' => 'La cédula ya está registrada.'];
                
                } else {
                $resultado = ['resultado' => 'Correcto'];
                
                }
            } elseif ($this->id != " " && $this->cedula != " " && $this->cedula != $this->id) {
                
                parent::conectarDB();
                $new = $this->con->prepare("SELECT `cedula`, `status` FROM personal WHERE cedula = ?");
                $new->bindValue(1, $this->cedula);
                $new->execute();
                $data = $new->fetchAll();
                parent::desconectarDB();
                if (isset($data[0]['status']) && $data[0]['status'] == 0) {
                $resultado = ['resultado' => 'Error', 'msj' => 'No Puede Ser Registrada'];
                
                } elseif (isset($data[0]['cedula']) && $data[0]['cedula'] == $this->cedula && $data[0]['status'] == 1) {
                $resultado = ['resultado' => 'Error', 'msj' => 'La Cedula ya esta Registrada'];
                
                } else {
                $resultado = ['resultado' => 'Correcto'];
                
                }
            } elseif ($this->cedula == $this->id) {
                $resultado = ['resultado' => 'Correcto'];
                
            } 
            return $resultado;


            } catch (\PDOException $error) {
            return $error;
            }
        }

        public function getValidarE($correo, $id)
        {
          $this->correo = $correo;
          $this->id = $id;
      
          return $this->validarE();
        }
      
        private function validarE()
        {
          try {
      
            parent::conectarDB();
            $new = $this->con->prepare("SELECT `correo`, `status` FROM personal WHERE cedula <> ? and correo = ?");
            $new->bindValue(1, $this->id);
            $new->bindValue(2, $this->correo);
            $new->execute();
            $data = $new->fetchAll();
            parent::desconectarDB();
            if (isset($data[0]['correo']) && $data[0]['status'] === 1) {
              $resultado = ['resultado' => 'Error', 'msj' => 'El Correo ya esta Registrado'];
              return $resultado;
            }
            // elseif (isset($data[0]['correo']) && $data[0]['status'] === 0 ) {
            //     $resultado = ['resultado' => 'Error', 'msj' => 'El Correo no Puede Ser Registrado'];
            //     return $resultado;
            // } -------> Preguntar si dejo esta validacion <-------
            $resultado = ['resultado' => 'Correcto'];
            return $resultado;
      
          } catch (\PDOException $e) {
            return $e;
          }
        }

        public function mostrarSede(){
            try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT id_sede, nombre FROM sede WHERE status = 1");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
            } catch (\PDOexection $error) {

            return $error;

            }
        }
        
        public function mostrarTipo(){
            try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT tipo_em, nombre_e FROM tipo WHERE status = 1");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
            } catch (\PDOexection $error) {

            return $error;

            }
        }



    }
    

?>