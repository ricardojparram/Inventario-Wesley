<?php

namespace modelo;

use config\connect\DBConnect;
use utils\validar;

class instituciones extends DBConnect
{
    use validar;
    private $rif;
    private $direccion;
    private $razon;
    private $contacto;
    private $update;
    private $id;


    public function getMostrarInstituciones($bitacora)
    {
        try {
            $this->conectarDB();
            $sql = 'SELECT rif_int, razon_social, direccion,contacto FROM instituciones 
                  WHERE status = 1;';
            $new = $this->con->prepare($sql);
            $new->execute();
            if ('true' == $bitacora) {
                $this->binnacle('Instituciones', $_SESSION['cedula'], 'Consultó el listado de Instituciones.');
            }
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getRegistrarInstitucion($rif, $razon, $direccion, $contacto)
    {
        if (!$this->validarString('rif', $rif)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        if (!$this->validarString('nombre', $razon)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        if (!$this->validarString('direccion', $direccion)) {
            return $this->http_error(400, 'Dirección inválida.');
        }
        if (!$this->validarString('entero', $contacto)) {
            return $this->http_error(400, 'Dirección inválida.');
        }
        
        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->contacto = $contacto;
        $this->update = false;
        $validarRif = $this->validarRif();
        if (!isset($validarRif['res'])) {
            return $validarRif;
        }
        return $this->registrarInstitucion();
    }

    private function registrarInstitucion()
    {
        try {
            $this->conectarDB();
            $sql = (!$this->update)
              ? 'INSERT INTO instituciones(rif_int ,direccion, razon_social, contacto, status) VALUES(:rif, :direccion, :razon_social, :contacto, 1)'
              : 'UPDATE instituciones SET direccion = :direccion, razon_social = :razon_social, contacto = :contacto, status = 1
                WHERE rif_int = :rif;';

            $new = $this->con->prepare($sql);
            $new->bindValue(':rif', $this->rif);
            $new->bindValue(':direccion', $this->direccion);
            $new->bindValue(':razon_social', $this->razon);
            $new->bindValue(':contacto', $this->contacto);
            if (!$new->execute()) {
                $this->http_error(500, 'Ha ocurrido un error en la base de datos.');
            }
            $this->binnacle('Institucion', $_SESSION['cedula'], 'Registró una Institucion.');
            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => "Se ha registrado la institucion {$this->razon}."];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }





    public function getRif($rif)
    {
        if (!$this->validarString('rif', $rif)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        $this->rif = $rif;
        return $this->validarRif();
    }

    private function validarRif()
    {
        try {
            $this->conectarDB();
            $sql = 'SELECT rif_int, status FROM instituciones WHERE rif_int = ?';
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->rif);
            $new->execute();
            $data = $new->fetchAll();
            $this->desconectarDB();
            if (isset($data[0]['rif_int']) && $data[0]['status'] == 1) {
                return $this->http_error(400, 'El rif ya está registrado.');
            } elseif(isset($data[0]['status']) && $data[0]['status'] == 0) {
                $this->update = true;
            }
            return ['resultado' => 'ok', 'msg' => 'Rif válido.', 'res' => true];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getItem($rif)
    {
        if (!$this->validarString('rif', $rif)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        $this->rif = $rif;
        return $this->selectItem();
    }

    private function selectItem()
    {
        try {
            $this->conectarDB();
            $sql = 'SELECT rif_int, direccion, razon_social, contacto FROM instituciones
                  WHERE status = 1 and rif_int = ? ;';
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->rif);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getEditar($rif, $razon, $direccion, $contacto ,$id): array
    {
        if (!$this->validarString('rif', $rif)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        if (!$this->validarString('nombre', $razon)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        if (!$this->validarString('direccion', $direccion)) {
            return $this->http_error(400, 'Dirección inválida.');
        }
        if (!$this->validarString('rif', $id)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        if (!$this->validarString('entero', $contacto)) {
            return $this->http_error(400, 'Dirección inválida.');
        }

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->id = $id;
        $this->contacto = $contacto;

        if ($this->id !== $this->rif) {
            $validarRif = $this->validarRif();
            if (!$validarRif['res']) {
                return $this->http_error(400, $validarRif['msg']);
            }
        }
        return $this->editarInstituciones();
    }

    private function editarInstituciones()
    {
        try {
            $this->conectarDB();
            $sql = 'UPDATE instituciones SET  direccion = :direccion , razon_social = :razon_social , contacto = :contacto, status = 1
                    WHERE rif_int = :rif';
            $new = $this->con->prepare($sql);
            $new->bindValue(':rif', $this->rif);
            $new->bindValue(':direccion', $this->direccion);
            $new->bindValue(':razon_social', $this->razon);
            $new->bindValue(':contacto', $this->contacto);
            $new->execute();
            if ($this->id !== $this->rif) {
                $sql = 'UPDATE instituciones SET status = 0 WHERE rif_int = :rif_edit';
                $new = $this->con->prepare($sql);
                $new->bindValue(':rif_edit', $this->id);
                $new->execute();
            }
            $this->binnacle('int', $_SESSION['cedula'], 'Editó una Institucion.');
            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => "Se ha editado correctamente la institucion {$this->razon}."];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getEliminar($rif)
    {
        if (!$this->validarString('rif', $rif)) {
            return $this->http_error(400, 'Rif inválido.');
        }

        $this->rif = $rif;

        return $this->eliminarInstituciones();
    }

    private function eliminarInstituciones()
    {
        try {
            $donacion = $this->validarInstitucionRegis($this->rif);
            if(!$donacion) return $this->http_error(400, 'La Institucion no se puede eliminar');

            $this->conectarDB();
            $new = $this->con->prepare('UPDATE instituciones SET status = 0 WHERE rif_int = ?; ');
            $new->bindValue(1, $this->rif);
            $new->execute();
            $this->binnacle('int', $_SESSION['cedula'], 'Eliminó una Institucion.');
            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => "Se ha eliminado correctamente la Institucion {$this->rif}."];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    private function validarInstitucionRegis($rif){
        try {
            parent::conectarDB();
            $new = $this->con->prepare('SELECT count(id_donativo_int) as count FROM donativo_int WHERE rif_int = ?;');
            $new->bindValue(1, $rif);
            $new->execute();
            $data = $new->fetch(\PDO::FETCH_OBJ);
            return $data->count === 0;
            parent::desconectarDB();
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }




}
