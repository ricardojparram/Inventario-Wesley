<?php

namespace modelo;

use utils\validar;
use utils\JWTService;
use config\connect\DBConnect as DBConnect;
use utils\CryptoService;

class login extends DBConnect
{
    use validar;
    private $cedula;
    private $password;
    private $sede;
    private $login;

    public function getSedes()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT id_sede, nombre FROM sede";
            $new = $this->con->prepare($sql);
            $new->execute();
            return $new->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOStatement $e) {
            return $this->http_error(500, $e);
        }
    }
    public function getPublicKey()
    {
        try {
            $crypto = new CryptoService;
            $public_key = $crypto->getPublicKey();
            return ['resultado' => 'ok', 'key' => base64_encode($public_key)];
        } catch (\Exception $e) {
            $this->http_error(500, $e->getMessage());
        }
    }
    public function getLoginSistema($cedula, $password, $sede, $login)
    {
        if (!$this->validarString('documento', $cedula)) {
            return $this->http_error(400, 'Cédula inválida.');
        }
        if (!$this->validarString('contraseña', $password)) {
            return $this->http_error(400, 'Contraseña inválida.');
        }
        if (!$this->validarString('entero', $sede)) {
            return $this->http_error(400, 'Sede inválida.');
        }

        $this->cedula = $cedula;
        $this->password = $password;
        $this->sede = $sede;
        $this->login = $login;

        $validCedula = $this->validarCedula();
        if (!isset($validCedula['res'])) {
            return $validCedula;
        }

        return $this->loginSistema();
    }

    private function loginSistema()
    {

        try {
            $this->conectarDB();
            $new = $this->con->prepare("
              SELECT u.cedula, u.nombre, u.apellido, u.correo, u.password, u.img, u.rol as nivel, r.nombre as puesto FROM usuario u 
							INNER JOIN rol r
							ON r.id_rol = u.rol
							WHERE u.cedula = ?");
            $new->bindValue(1, $this->cedula);
            $new->execute();
            $data = $new->fetch(\PDO::FETCH_OBJ);

            if (!isset($data->password)) {
                $this->desconectarDB();
                return $this->http_error(400, 'La cédula no está registrada.');
            }
            if (!password_verify($this->password, $data->password)) {
                $this->desconectarDB();
                return $this->http_error(400, 'Contraseña incorrecta.');
            }

            $new = $this->con->prepare('SELECT id_sede, nombre as sede FROM sede WHERE id_sede = :id_sede');
            $new->bindParam(':id_sede', $this->sede);
            $new->execute();
            $sede = $new->fetch(\PDO::FETCH_OBJ);

            $userData = (object) [
                'id_sede' => (isset($sede->id_sede)) ? $sede->id_sede : '',
                'sede' => (isset($sede->sede)) ? $sede->sede : '',
                'cedula' => $data->cedula,
                'nombre' => $data->nombre,
                'apellido' => $data->apellido,
                'correo' => $data->correo,
                'nivel' => $data->nivel,
                'puesto' => $data->puesto,
                'fotoPerfil' => (isset($data->img)) ? $data->img : 'assets/img/profile_photo.jpg'
            ];

            if ($this->login === 'app') {
                return ['resultado' => "Logueado", "token" => JWTService::generateToken($userData)];
            }

            if (isset($sede->id_sede)) {
                $_SESSION['id_sede'] = $userData->id_sede;
                $_SESSION['sede'] = $userData->sede;
            }
            $_SESSION['cedula'] = $userData->cedula;
            $_SESSION['nombre'] = $userData->nombre;
            $_SESSION['apellido'] = $userData->apellido;
            $_SESSION['correo'] = $userData->correo;
            $_SESSION['nivel'] = $userData->nivel;
            $_SESSION['puesto'] = $userData->puesto;
            $_SESSION['fotoPerfil'] = (isset($userData->img)) ? $userData->img : 'assets/img/profile_photo.jpg';

            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => 'Se ha iniciado sesion'];
        } catch (\PDOException $error) {
            return $this->http_error(500, $error);
        }
    }

    public function getValidarCedula($cedula)
    {
        if (!$this->validarString('documento', $cedula)) {
            return $this->http_error(400, 'Cédula inválida.');
        }

        $this->cedula = $cedula;

        return $this->validarCedula();
    }

    private function validarCedula(): array
    {
        try {

            $this->conectarDB();

            $new = $this->con->prepare("SELECT cedula FROM usuario WHERE status = 1 and cedula = ?");
            $new->bindValue(1, $this->cedula);
            $new->execute();
            $data = $new->fetchAll();
            parent::desconectarDB();
            return (!isset($data[0]['cedula']))
                ? $this->http_error(400, 'La cédula no está registrada.')
                : ['resultado' => 'ok', 'msg' => 'La cédula es válida.', 'res' => true];
        } catch (\PDOException $error) {
            return $this->http_error(500, $error);
        }
    }
}
