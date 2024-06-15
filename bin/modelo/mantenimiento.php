<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class mantenimiento extends DBConnect
{
    use validar;
    private $usuario;
    private $contra;
    private $local;
    private $nameBD;

    public function getHistorial()
    {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT concat(u.nombre,' ',u.apellido) as nombre, b.descripcion, b.fecha FROM bitacora b INNER JOIN usuario u ON b.cedula = u.cedula WHERE b.status = 1 AND b.descripcion = ?");
            $new->bindValue(1, "Creo un Copia de Seguridad");
            $new->execute();
            $data = $new->fetchAll();
            return $data;
            $this->binnacle("", $_SESSION['cedula'], "Consultó listado de Mantenimiento");

            parent::desconectarDB();
        } catch (\PDOException $error) {
            return $this->http_error(500, $error);
        }
    }

    public function getRespaldo() {
        $this->usuario = parent::_USER_();
        $this->contra = parent::_PASS_();
        $this->local = parent::_LOCAL_();
        $this->nameBD = parent::_BD_();
       

        return $this->respaldo();
    }

    private function respaldo(){
        try {
            $fecha = date("Ymd-his");
            $nombreArchivo = $this->nameBD.'_'.$fecha.'.sql';
            parent::conectarDB();
            
            $this->binnacle("", $_SESSION['cedula'], "Creo un Copia de Seguridad");
            parent::desconectarDB();
            return "listo";

        } catch (\PDOException $error) {
            return $this->http_error(500, $error);
        }
    }
}

?>
