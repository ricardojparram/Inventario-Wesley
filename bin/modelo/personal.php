<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class personal extends DBConnect
{
    use validar;
    private $cedula;
    private $nombre;
    private $apellido;
    private $correo;
    private $edad;
    private $direccion;
    private $telefono;
    private $sede;
    private $tipo;
    private $id;

    public function getAgregarPersonal($cedula, $nombre, $apellido, $correo, $edad, $direccion, $telefono, $sede, $tipo)
    {

        if (preg_match_all("/^[VE]-[A-Z0-9]{7,12}$/", $cedula) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Documento invalido.'];
            return $resultado;
        }
        if (preg_match_all("/^[a-zA-ZÀ-ÿ]{0,30}$/", $nombre) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Nombre invalido.'];
            return $resultado;
        }
        if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $apellido) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Apellido invalido.'];
            return $resultado;
        }
        if (preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $correo) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Correo invalido.'];
            return $resultado;
        }
        if (preg_match_all("/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/", $edad) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Fecha invalida.'];
            return $resultado;
        }
        if (preg_match_all("/[$%&|<>]/", $direccion) == true) {
            $resultado = ['resultado' => 'Error', 'error' => 'Direccion inválida.'];
            return $resultado;
        }
        if (preg_match_all("/^[0-9]{10,30}$/", $telefono) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Telefono Invalido'];
            return $resultado;
        }
        if (preg_match_all("/^[0-9]{1,2}$/", $sede) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Sede invalida.'];
            return $resultado;
        }
        if (preg_match_all("/^[0-9]{1,2}$/", $tipo) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Tipo de Empleado invalido.'];
            return $resultado;
        }

        $this->cedula = $cedula;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->edad = $edad;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->sede = $sede;
        $this->tipo = $tipo;

        return $this->agregarPersonal();
    }

    private function agregarPersonal()
    {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT `cedula`, `status` FROM `personal` WHERE `cedula` = ?");
            $new->bindValue(1, $this->cedula);
            $new->execute();
            $data = $new->fetchAll();
            parent::desconectarDB();

            if (!isset($data[0]['status'])) {

                parent::conectarDB();
                $new = $this->con->prepare("INSERT INTO `personal`(`cedula`, `nombres`, `apellidos`, `direccion`, `id_sede`, `edad`, `telefono`, `correo`, `tipo_em`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
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
                $resultado = ['resultado' => 'Registrado'];
                $this->binnacle("", $_SESSION['cedula'], "Registró un personal");
                parent::desconectarDB();
            } elseif ($data[0]['status'] == 0) {

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
                $resultado = ['resultado' => 'Registrado'];
                $this->binnacle("", $_SESSION['cedula'], "Registró un personal");
                parent::desconectarDB();
            } else {
                $resultado = ['resultado' => 'Error', 'error' => 'error desconocido.'];
            }
            return $resultado;
        } catch (\PDOException $error) {
            return $error;
        }
    }

    public function getMostrarPersonal($bitacora = false)
    {
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

    public function getUnico($cedula)
    {
        $this->cedula = $cedula;
        return $this->seleccionarUnico();
    }

    private function seleccionarUnico()
    {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT p.cedula, p.nombres, p.apellidos, p.direccion, p.telefono, p.edad as fecha, p.correo, s.id_sede as sede, e.tipo_em as tipo, s.nombre as nomSede, e.nombre_e as nomTipo FROM personal p INNER JOIN sede s ON p.id_sede = s.id_sede INNER JOIN tipo_empleado e ON p.tipo_em = e.tipo_em WHERE p.cedula = ?");
            $new->bindValue(1, $this->cedula);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            $fecha = time() - strtotime($data[0]->fecha);
            $data[0]->edad = floor($fecha / 31556926);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {
            return $error;
        }
    }

    public function getEditarPersonal($cedula, $nombre, $apellido, $correo, $edad, $direccion, $telefono, $sede, $tipo, $id)
    {

        if (preg_match_all("/^[VE]-[A-Z0-9]{7,12}$/", $cedula) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Documento invalido.'];
            return $resultado;
        }
        if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $nombre) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Nombre invalido.'];
            return $resultado;
        }
        if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $apellido) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Apellido invalido.'];
            return $resultado;
        }
        if (preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $correo) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Correo invalido.'];
            return $resultado;
        }
        if (preg_match_all("/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/", $edad) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Fecha invalida.'];
            return $resultado;
        }
        if (preg_match_all("/[$%&|<>]/", $direccion) == true) {
            $resultado = ['resultado' => 'Error', 'error' => 'Direccion inválida.'];
            return $resultado;
        }
        if (preg_match_all("/^[0-9]{10,30}$/", $telefono) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Telefono Invalido'];
            return $resultado;
        }
        if (preg_match_all("/^[0-9]{1,2}$/", $sede) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Sede invalida.'];
            return $resultado;
        }
        if (preg_match_all("/^[0-9]{1,2}$/", $tipo) == false) {
            $resultado = ['resultado' => 'Error', 'error' => 'Tipo de Empleado invalido.'];
            return $resultado;
        }

        $this->cedula = $cedula;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->edad = $edad;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->sede = $sede;
        $this->tipo = $tipo;
        $this->id = $id;

        return $this->editarPersonal();
    }

    private function editarPersonal()
    {
        try {
            $user = $this->validarPersonalUser($this->cedula);
            if (!$user) {
                parent::conectarDB();
                $new = $this->con->prepare("UPDATE `usuario` SET `cedula`= ?,`nombre`= ?,`apellido`= ?, `correo`= ? WHERE `cedula` = ?");
                $new->bindValue(1, $this->cedula);
                $new->bindValue(2, $this->nombre);
                $new->bindValue(3, $this->apellido);
                $new->bindValue(4, $this->correo);
                $new->bindValue(5, $this->id);
                $new->execute();
            }
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
            $resultado = ['resultado' => 'Editado'];
            $this->binnacle("a", $_SESSION['cedula'], "Editó un personal");
            parent::desconectarDB();
            return $resultado;
        } catch (\PDOException $e) {
            return $e;
        }
    }

    public function getEliminarPersonal($cedula)
    {
        $this->cedula = $cedula;
        return $this->eliminarPersonal();
    }

    private function eliminarPersonal()
    {
        try {
            $user = $this->validarPersonalUser($this->cedula);
            if (!$user) return ['resultado' => 'Error', 'msj' => 'Elimine primero en Usuario'];

            parent::conectarDB();
            $new = $this->con->prepare("UPDATE `personal` SET `status` = '0' WHERE `personal`.`cedula` = ?"); //"DELETE FROM `personal` WHERE `personal`.`cedula` = ?"
            $new->bindValue(1, $this->cedula);
            $new->execute();
            $resultado = ['resultado' => 'Eliminado'];

            $this->binnacle("a", $_SESSION['cedula'], "Eliminó un personal");
            parent::desconectarDB();
            return $resultado;
        } catch (\PDOException $error) {
            return $error;
        }
    }

    public function getValidarC($cedula, $id)
    {
        $this->cedula = $cedula;
        $this->id = $id;
        return $this->validarC();
    }

    private function validarC()
    {
        try {

            if ($this->cedula == " ") {
                parent::conectarDB();
                $new = $this->con->prepare("SELECT `cedula` FROM `personal` WHERE `cedula` = ?");
                $new->bindValue(1, $this->id);
                $new->execute();
                $data = $new->fetchAll();
                parent::desconectarDB();
                if (isset($data[0]['cedula'])) {
                    $resultado = ['resultado' => 'Correcto', 'msj' => 'el documento está registrado.'];
                } else {
                    $resultado = ['resultado' => 'Error', 'msj' => 'documento no Registrado'];
                }
            } elseif ($this->id == " ") {

                parent::conectarDB();
                $new = $this->con->prepare("SELECT `cedula` FROM `personal` WHERE `status`= 1 and `cedula` = ?");
                $new->bindValue(1, $this->cedula);
                $new->execute();
                $data = $new->fetchAll();
                parent::desconectarDB();
                if (isset($data[0]['cedula'])) {
                    $resultado = ['resultado' => 'Error', 'msj' => 'El documento ya está registrado.'];
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
                    $resultado = ['resultado' => 'Error', 'msj' => 'No Puede Ser Registrado'];
                } elseif (isset($data[0]['cedula']) && $data[0]['cedula'] == $this->cedula && $data[0]['status'] == 1) {
                    $resultado = ['resultado' => 'Error', 'msj' => 'El documento ya esta Registrado'];
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

    public function mostrarSede()
    {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT id_sede, nombre FROM sede WHERE status = 1");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {

            return $error;
        }
    }

    public function mostrarTipo()
    {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT tipo_em, nombre_e FROM tipo_empleado WHERE status = 1");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {

            return $error;
        }
    }

    private function validarPersonalUser($cedula)
    {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT nombre FROM usuario WHERE cedula = ?");
            $new->bindValue(1, $cedula);
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            $new->execute();
            if (isset($data)) return false;
            return true;

            parent::desconectarDB();
        } catch (\PDOException $e) {
            return $e;
        }
    }
}
