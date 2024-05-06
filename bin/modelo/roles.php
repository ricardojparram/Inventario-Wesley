<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class roles extends DBConnect
{
    use validar;
    private $id_rol;
    private $rol;
    private $permisos;
    private array $roles;

    public function __construct()
    {
        parent::__construct();
        $this->getRoles();
    }


    private function getRoles()
    {
        try {
            $this->conectarDB();
            $new = $this->con->prepare("SELECT id_rol as id, nombre FROM rol");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($data as $rol) {
                $this->roles[$rol['id']] = $rol['nombre'];
            }
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    private function validarNombreRol()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT nombre FROM rol WHERE nombre LIKE ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->rol);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            if (isset($data[0])) {
                return ['resultado' => 'error', 'msg' => 'El rol ya esta registrado.'];
            }
            return ['resultado' => 'ok', 'msg' => 'Rol valido'];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getAgregarRol($rol)
    {
        if (!$this->validarString('nombre', $rol)) {
            return $this->http_error(400, 'Nombre inválido.');
        }

        $this->rol = $rol;
        $valid = $this->validarNombreRol();
        if ($valid['resultado'] !== 'ok') {
            return $this->http_error(400, $valid['msg']);
        }

        return $this->agregarRol();
    }

    private function generarPermisosPorModulo($id_rol)
    {
        try {
            $this->conectarDB();
            $sql = "INSERT INTO permisos (id_rol, id_modulo, nombre_accion, status)
						SELECT ?, id_modulo, nombre_accion, 0
						FROM permisos
						WHERE id_rol = 1 AND status = 1;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $id_rol);
            return $new->execute();
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    private function agregarRol()
    {
        try {
            $this->conectarDB();
            $sql = "INSERT INTO rol(nombre, status) VALUES (?,1)";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->rol);
            $new->execute();
            $id_rol = $this->con->lastInsertId();
            if (!$this->generarPermisosPorModulo($id_rol)) {
                return ['resultado' => 'error', 'msg' => 'Ha ocurrido un error al generar permisos.'];
            }

            return ['resultado' => 'ok', 'msg' => 'Se ha agregado el rol'];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getEditarRol($id_rol, $rol)
    {
        if (!$this->validarString('entero', $id_rol)) {
            return $this->http_error(400, 'Id inválida.');
        }

        if (!$this->validarString('nombre', $rol)) {
            return $this->http_error(400, 'Nombre inválido.');
        }

        $this->id_rol = $id_rol;
        $this->rol = $rol;
        $valid = $this->validarNombreRol();
        if ($valid['resultado'] !== 'ok') {
            return $this->http_error(400, $valid['msg']);
        }

        return $this->editarRol();
    }

    private function editarRol()
    {
        try {
            $this->conectarDB();
            $sql = "UPDATE rol SET nombre = ? WHERE id_rol = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->rol);
            $new->bindValue(2, $this->id_rol);
            if (!$new->execute()) {
                return ['resultado' => 'error', 'msg' => 'Ha ocurrido un error en la base de datos.'];
            }

            return [
                'resultado' => 'ok',
                'msg' => "Se ha editado el rol {$this->roles[$this->id_rol]}."
            ];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    private function validarRolConUsuarios()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT * FROM usuario WHERE rol = ? AND status = 1";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_rol);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_ASSOC);
            return isset($data[0]);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getEliminarRol($id_rol)
    {
        if ($_SESSION['nivel'] === $id_rol) {
            return $this->http_error(400, 'No puede eliminar su propio rol.');
        }

        if (!$this->validarString('entero', $id_rol)) {
            return $this->http_error(400, 'Id inválida.');
        }

        $this->id_rol = $id_rol;
        if ($this->validarRolConUsuarios()) {
            return $this->http_error(400, 'Este rol tiene usuarios activos.');
        }

        return $this->eliminarRol();
    }

    public function eliminarRol()
    {
        try {

            $this->conectarDB();
            $sql = "UPDATE rol SET status = 0 WHERE id_rol = ?;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_rol);

            if (!$new->execute()) {
                return $this->http_error(500, 'Ha ocurrido un error en la base de datos.');
            }

            return [
                'resultado' => 'ok',
                'msg' => "Se ha eliminado el rol {$this->roles[$this->id_rol]}."
            ];
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getMostrarRol($id_rol)
    {
        if (!$this->validarString('entero', $id_rol)) {
            return $this->http_error(400, 'Id inválida.');
        }

        $this->id_rol = $id_rol;
        return $this->mostrarRol();
    }

    private function mostrarRol()
    {

        try {
            $this->conectarDB();
            $sql = 'SELECT nombre FROM rol WHERE status =1 AND id_rol=?;';
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_rol);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);

            $this->desconectarDB();
            return $data;
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function mostrarRoles($bitacora)
    {

        try {
            $this->conectarDB();
            $sql = 'SELECT id, MAX(nombre) as nombre, SUM(totales) as totales, MAX(status) as status
                    FROM (
                        SELECT r.id_rol as id, r.nombre as nombre, COUNT(*) as totales, r.status as status 
                        FROM rol r 
                        INNER JOIN usuario u ON u.rol = r.id_rol 
                        GROUP BY r.id_rol 
                        UNION 
                        SELECT r.id_rol as id, r.nombre as nombre, 0 as totales, r.status as status 
                        FROM rol r 
                    ) as tabla 
                    WHERE tabla.id != 1 AND tabla.status != 0 
                    GROUP BY id;';
            $new = $this->con->prepare($sql);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            if ($bitacora == "true") {
                $this->binnacle("Roles", $_SESSION['cedula'], "Consultó listado.");
            }
            $this->desconectarDB();
            return $data;
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getPermisos($id)
    {
        if (!$this->validarString('entero', $id)) {
            return $this->http_error(400, 'Id inválida.');
        }

        $this->id_rol = $id;

        return $this->mostrarPermisos();
    }

    private function mostrarPermisos()
    {

        try {

            $this->conectarDB();
            $new = $this->con->prepare('SELECT id_modulo, nombre FROM modulos WHERE status = 1');
            $new->execute();
            $modulos = $new->fetchAll(\PDO::FETCH_ASSOC);
            $permisos = [];
            foreach ($modulos as $modulo) {
                $nombre = mb_convert_encoding($modulo["nombre"], 'UTF-8');
                $permisos[$nombre] = "";
            }

            $query = 'SELECT p.id_permiso, p.nombre_accion, p.status FROM permisos p
						INNER JOIN modulos m ON m.id_modulo = p.id_modulo
						WHERE p.id_rol = ? AND m.nombre = ? AND m.status = 1';

            foreach ($permisos as $nombre_modulo => $valor) {

                $new = $this->con->prepare($query);
                $new->bindValue(1, $this->id_rol);
                $new->bindValue(2, $nombre_modulo);
                $new->execute();
                $data = $new->fetchAll(\PDO::FETCH_ASSOC);
                $acciones = [];

                foreach ($data as $permiso) {
                    $acciones += [
                        $permiso["nombre_accion"] => [
                            "id_permiso" => $permiso["id_permiso"],
                            "status" => $permiso["status"]
                        ]
                    ];
                }
                $permisos[$nombre_modulo] = $acciones;
            }
            $this->binnacle("Roles", $_SESSION['cedula'], "Consultó el los permisos del rol {$this->roles[$this->id_rol]}.");
            $this->desconectarDB();
            return $permisos;
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    public function getDatosPermisos($datos, $id)
    {
        if (!$this->validarString('entero', $id)) {
            return $this->http_error(400, 'Id inválida.');
        }

        $estructura = [
            'id_permiso' => 'string',
            'status' => 'string'
        ];
        if (!$this->validarEstructuraArray($datos, $estructura, true)) {
            return $this->http_error(400, 'Permisos inválidos.');
        }

        $this->permisos = $datos;
        $this->id_rol = $id;

        return $this->actualizarPermisos();
    }

    private function actualizarPermisos()
    {

        try {

            $this->conectarDB();
            $sql = "UPDATE permisos SET status = ? WHERE id_permiso = ?";

            foreach ($this->permisos as $modulo) {
                try {

                    $status = ($modulo['status'] === "true") ? 1 : 0;
                    $new = $this->con->prepare($sql);
                    $new->bindValue(1, $status);
                    $new->bindValue(2, $modulo['id_permiso']);
                    $new->execute();
                } catch (\PDOException $e) {
                    return $this->http_error(500, $e->getMessage());
                }
            }
            $respuesta = ['respuesta' => 'ok', 'msg' => 'Se han actualizado los permisos correctamente.'];
            $this->binnacle("Roles", $_SESSION['cedula'], "Actualizó los permisos del rol {$this->roles[$this->id_rol]}.");
            $this->desconectarDB();
            return $respuesta;
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
}
