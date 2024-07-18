<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class notificaciones extends DBConnect
{

    use validar;
    private $id;
    private $status;

    public function mostrarNotificaciones()
    {
        try {
            $this->conectarDB();
            $query = "SELECT `id`, `titulo`, `mensaje`, `fecha`, `status` FROM `notificaciones`";

            $new = $this->con->prepare($query);
            $new->execute();
            $res = $new->fetchAll();

            $this->desconectarDB();

            return $res;

        } catch (\PDOException $error) {
            die($error);
        }
    }

    public function getActualizarStatus($id, $status)
    {
        if (!$this->validarString('entero', $id))
            return $this->http_error(400, 'id notificacion inválido.');

        if (!$this->validarString('decimal', $status))
            return $this->http_error(400, 'status inválido.');

        $this->id = $id;
        $this->status = $status;

        return $this->actualizarStatus();
    }

    private function actualizarStatus(){
        try {
            $this->conectarDB();
            $new = $this->con->prepare('UPDATE notificaciones n SET n.status = ? WHERE n.id = ?');
            $new->bindValue(1, $this->status);
            $new->bindValue(2, $this->id);
            $new->execute();
            $data = ['resultado' => 'ok'];

            $this->desconectarDB();
            return $data;
        } catch (\PDOException $error) {
            die($error);
        }
    }
}
