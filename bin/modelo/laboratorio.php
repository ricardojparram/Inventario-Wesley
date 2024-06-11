<?php

namespace modelo;

use config\connect\DBConnect;
use utils\validar;

class laboratorio extends DBConnect
{
    use validar;
    private $cod_lab;
    private $rif;
    private $direccion;
    private $razon;
    private $idedit;
    private $update;

    public function mostrarLaboratorios($bitacora)
    {
        try {
            $this->conectarDB();
            $sql = 'SELECT rif_laboratorio, razon_social, direccion FROM laboratorio 
                  WHERE status = 1;';
            $new = $this->con->prepare($sql);
            $new->execute();
            if ('true' == $bitacora) {
                $this->binnacle('Laboratorio', $_SESSION['cedula'], 'Consultó el listado de laboratorios.');
            }
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getRegistrarLaboratorio($rif, $direccion, $razon)
    {
        if (!$this->validarString('rif', $rif)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        if (!$this->validarString('razon_social', $razon)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        if (!$this->validarString('direccion', $direccion)) {
            return $this->http_error(400, 'Dirección inválida.');
        }

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->update = false;
        $validarRif = $this->validarRif();
        if (!isset($validarRif['res'])) {
            return $validarRif;
        }
        return $this->registrarLaboratorio();
    }

    private function registrarLaboratorio()
    {
        try {
            $this->conectarDB();
            $sql = (!$this->update)
                ? 'INSERT INTO laboratorio(rif_laboratorio,direccion,razon_social,status) VALUES(:rif, :direccion, :razon_social, 1)'
                : 'UPDATE laboratorio SET direccion = :direccion, razon_social = :razon_social, status = 1
                WHERE rif_laboratorio = :rif;';

            $new = $this->con->prepare($sql);
            $new->bindValue(':rif', $this->rif);
            $new->bindValue(':direccion', $this->direccion);
            $new->bindValue(':razon_social', $this->razon);
            if (!$new->execute()) {
                $this->http_error(500, 'Ha ocurrido un error en la base de datos.');
            }
            $this->binnacle('Laboratorio', $_SESSION['cedula'], 'Registró un laboratorio.');
            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => "Se ha registrado el laboratorio {$this->razon}."];
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

    public function getItem($rif)
    {
        if (!$this->validarString('rif', $rif)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        $this->rif = $rif;
        return $this->selectItem();
    }

    private function validarRif()
    {
        try {
            $this->conectarDB();
            $sql = 'SELECT rif_laboratorio, status FROM laboratorio
                    WHERE rif_laboratorio = ?';
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->rif);
            $new->execute();
            $data = $new->fetchAll();
            $this->desconectarDB();
            if (isset($data[0]['rif_laboratorio']) && $data[0]['status'] == 1) {
                return $this->http_error(400, 'El rif ya está registrado.');
            } elseif (isset($data[0]['status']) && $data[0]['status'] == 0) {
                $this->update = true;
            }
            return ['resultado' => 'ok', 'msg' => 'Rif válido.', 'res' => true];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    private function selectItem()
    {
        try {
            $this->conectarDB();
            $sql = 'SELECT rif_laboratorio, direccion, razon_social FROM laboratorio 
                  WHERE status = 1 and rif_laboratorio = ? ;';
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->rif);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getEditar($rif, $direccion, $razon, $id): array
    {
        if (!$this->validarString('rif', $rif)) {
            return $this->http_error(400, 'Rif inválido.');
        }
        if (!$this->validarString('razon_social', $razon)) {
            return $this->http_error(400, 'Razon social inválida.');
        }
        if (!$this->validarString('direccion', $direccion)) {
            return $this->http_error(400, 'Dirección inválida.');
        }
        if (!$this->validarString('rif', $id)) {
            return $this->http_error(400, 'Rif inválido.');
        }

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->idedit = $id;

        if ($this->idedit !== $this->rif) {
            $validarRif = $this->validarRif();
            if (!isset($validarRif['res'])) {
                return $this->http_error(400, $validarRif['msg']);
            }
        }

        return $this->editarLaboratorio();
    }

    private function editarLaboratorio()
    {
        try {
            $this->conectarDB();
            $sql = 'UPDATE laboratorio SET  direccion = :direccion , razon_social = :razon_social , status = 1
                    WHERE rif_laboratorio = :rif';
            $new = $this->con->prepare($sql);
            $new->bindValue(':rif', $this->rif);
            $new->bindValue(':direccion', $this->direccion);
            $new->bindValue(':razon_social', $this->razon);
            $new->execute();
            if ($this->idedit !== $this->rif) {
                $sql = 'UPDATE laboratorio SET status = 0 WHERE rif_laboratorio = :rif_edit';
                $new = $this->con->prepare($sql);
                $new->bindValue(':rif_edit', $this->idedit);
                $new->execute();
            }
            $this->binnacle('Laboratorio', $_SESSION['cedula'], 'Editó laboratorio.');
            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => "Se ha editado correctamente el laboratorio {$this->rif}."];
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

        return $this->eliminarLaboratorio();
    }

    private function eliminarLaboratorio()
    {
        try {
            $this->conectarDB();
            $new = $this->con->prepare('UPDATE laboratorio SET status = 0 WHERE rif_laboratorio = ?; ');
            $new->bindValue(1, $this->rif);
            $new->execute();
            $this->binnacle('Laboratorio', $_SESSION['cedula'], 'Eliminó laboratorio.');
            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => "Se ha eliminado correctamente el laboratorio {$this->rif}."];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
}
