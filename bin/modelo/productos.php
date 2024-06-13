<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class productos extends DBConnect
{
    use validar;
    private $cod_producto;
    private $nombre_prod;
    private $presentacion;
    private $laboratorio;
    private $tipo;
    private $clase;
    private $composicion;
    private $posologia;
    private $contraindicaciones;
    private $id;

    public function mostrarProductos()
    {
        try {
            $this->conectarDB();
            $query = "SELECT
                        p.cod_producto,
                        t.nombrepro,
                        concat(cantidad, ' x ', peso, ' ', nombre) as pres
                    FROM
                        producto p,
                        tipo_producto t,
                        presentacion pr,
                        medida m
                    WHERE
                        p.status = 1
                        and t.id_tipoprod = p.id_tipoprod
                        and pr.cod_pres = p.cod_pres
                        and m.id_medida = pr.id_medida;";
            $new = $this->con->prepare($query);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll();
        } catch (\PDOException $error) {
            return  $this->http_error(500, $error->getMessage());
        }
    }

    private function validarRegistrosProductos($id)
    {
        try {
            $sql = "SELECT
                        COUNT(ps.cod_producto) as count
                    FROM
                        producto p
                        LEFT JOIN producto_sede ps ON ps.cod_producto = p.cod_producto
                    WHERE
                        p.cod_producto = :cod_producto";
            $this->conectarDB();
            $new = $this->con->prepare($sql);
            $new->bindValue(':cod_producto', $id);
            $new->execute();
            $res = $new->fetch(\PDO::FETCH_OBJ);
            return intval($res->count) === 0;
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getRegistrarProducto($cod_producto, $nombre_prod, $presentacion, $laboratorio, $tipo, $clase, $composicion, $posologia, $contraindicaciones)
    {

        if (!$this->validarString('cod_producto', $cod_producto)) {
            return $this->http_error(400, "Código del producto inválido.");
        }
        if (!$this->validarString('entero', $nombre_prod)) {
            return $this->http_error(400, "Nombre del producto inválido.");
        }
        if (!$this->validarString('entero', $presentacion)) {
            return $this->http_error(400, "Presentacion del producto inválido.");
        }
        if ($laboratorio !== "") {
            if (!$this->validarString('rif', $laboratorio)) {
                return $this->http_error(400, "Laboratorio del producto inválido.");
            }
        }
        if (!$this->validarString('entero', $tipo)) {
            return $this->http_error(400, "Tipo del producto inválido.");
        }
        if (!$this->validarString('entero', $clase)) {
            return $this->http_error(400, "Clase del producto inválido.");
        }
        if (!$this->validarString('long_string', $composicion, ['min' => 5, 'max' => 60])) {
            return $this->http_error(400, "Composicion del producto inválida.");
        }
        if (!$this->validarString('long_string', $posologia, ['min' => 5, 'max' => 200])) {
            return $this->http_error(400, "Posologia del producto inválida.");
        }
        if (!$this->validarString('long_string', $contraindicaciones, ['min' => 5, 'max' => 250])) {
            return $this->http_error(400, "Contraindicaciones del producto inválida.");
        }

        $this->cod_producto = $cod_producto;
        $this->nombre_prod = $nombre_prod;
        $this->composicion = $composicion;
        $this->contraindicaciones = $contraindicaciones;
        $this->posologia = $posologia;
        $this->laboratorio = $laboratorio;
        $this->tipo = $tipo;
        $this->clase = $clase;
        $this->presentacion = $presentacion;


        $this->registraProd();
    }

    private function registraProd()
    {
        try {
            $this->conectarDB();
            $sql = "INSERT INTO
                        producto(
                        cod_producto,
                        composicion,
                        contraindicaciones,
                        posologia,
                        rif_laboratorio,
                        id_tipo,
                        id_clase,
                        cod_pres,
                        id_tipoprod,
                        status
                        )
                    VALUES
                        (
                        :cod_producto,
                        :composicion,
                        :contraindicaciones,
                        :posologia,
                        :rif_laboratorio,
                        :id_tipo,
                        :id_clase,
                        :cod_pres,
                        :id_tipoprod,
                        1
                        )";
            $new = $this->con->prepare($sql);
            $new->bindValue(':cod_producto', $this->cod_producto);
            $new->bindValue(':composicion', $this->composicion);
            $new->bindValue(':contraindicaciones', $this->contraindicaciones);
            $new->bindValue(':posologia', $this->posologia);
            $new->bindValue(':rif_laboratorio', $this->laboratorio);
            $new->bindValue(':id_tipo', $this->tipo);
            $new->bindValue(':id_clase', $this->clase);
            $new->bindValue(':cod_pres', $this->presentacion);
            $new->bindValue(':id_tipoprod', $this->nombre_prod);
            $new->execute();

            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => "El produto se ha registrado correctamente."];
        } catch (\PDOException $error) {
            return  $this->http_error(500, $error->getMessage());
        }
    }


    public function getMostrarProducto($id)
    {
        if (!$this->validarString('cod_producto', $id)) {
            return $this->http_error(400, "Código del producto inválido.");
        }
        $this->id = $id;
        return $this->mostrarProducto();
    }
    public function mostrarProducto()
    {
        try {
            $this->conectarDB();
            $sql = "SELECT
                        p.cod_producto,
                        p.composicion,
                        p.contraindicaciones,
                        p.posologia,
                        p.rif_laboratorio,
                        p.id_tipo,
                        p.id_clase,
                        p.cod_pres,
                        p.id_tipoprod,
                        p.status
                    FROM
                        producto p
                    WHERE
                        p.status = 1
                        and p.cod_producto = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $error) {
            return  $this->http_error(500, $error->getMessage());
        }
    }



    public function getEditarProducto($cod_producto, $nombre_prod, $presentacion, $laboratorio, $tipo, $clase, $composicion, $posologia, $contraindicaciones, $id)
    {

        if (!$this->validarString('cod_producto', $cod_producto)) {
            return $this->http_error(400, "Código del producto inválido.");
        }
        if (!$this->validarString('entero', $nombre_prod)) {
            return $this->http_error(400, "Nombre del producto inválido.");
        }
        if (!$this->validarString('entero', $presentacion)) {
            return $this->http_error(400, "Presentacion del producto inválido.");
        }
        if ($laboratorio !== "") {
            if (!$this->validarString('rif', $laboratorio)) {
                return $this->http_error(400, "Laboratorio del producto inválido.");
            }
        }
        if (!$this->validarString('entero', $tipo)) {
            return $this->http_error(400, "Tipo del producto inválido.");
        }
        if (!$this->validarString('entero', $clase)) {
            return $this->http_error(400, "Clase del producto inválido.");
        }
        if (!$this->validarString('long_string', $composicion, ['min' => 5, 'max' => 60])) {
            return $this->http_error(400, "Composicion del producto inválida.");
        }
        if (!$this->validarString('long_string', $posologia, ['min' => 5, 'max' => 200])) {
            return $this->http_error(400, "Posologia del producto inválida.");
        }
        if (!$this->validarString('long_string', $contraindicaciones, ['min' => 5, 'max' => 250])) {
            return $this->http_error(400, "Contraindicaciones del producto inválida.");
        }
        if (!$this->validarString('cod_producto', $id)) {
            return $this->http_error(400, "Código del producto a editar inválido.");
        }

        $this->cod_producto = $cod_producto;
        $this->nombre_prod = $nombre_prod;
        $this->composicion = $composicion;
        $this->contraindicaciones = $contraindicaciones;
        $this->posologia = $posologia;
        $this->laboratorio = $laboratorio;
        $this->tipo = $tipo;
        $this->clase = $clase;
        $this->presentacion = $presentacion;
        $this->id = $id;

        return $this->editarProducto();
    }


    private function editarProducto()
    {
        try {
            $this->conectarDB();
            $sql = "UPDATE
                    producto p
                SET
                    cod_producto = :cod_producto,
                    id_tipoprod = :id_tipoprod,
                    composicion = :composicion,
                    contraindicaciones = :contraindicaciones,
                    posologia = :posologia,
                    rif_laboratorio = :rif_laboratorio,
                    id_tipo = :id_tipo,
                    id_clase = :id_clase,
                    cod_pres = :cod_pres
                WHERE
                    p.status = 1
                    AND p.cod_producto = :id";
            $new = $this->con->prepare($sql);
            $new->bindValue(':cod_producto', $this->cod_producto);
            $new->bindValue(':id_tipoprod', $this->nombre_prod);
            $new->bindValue(':composicion', $this->composicion);
            $new->bindValue(':contraindicaciones', $this->contraindicaciones);
            $new->bindValue(':posologia', $this->posologia);
            $new->bindValue(':rif_laboratorio', $this->laboratorio);
            $new->bindValue(':id_tipo', $this->tipo);
            $new->bindValue(':id_clase', $this->clase);
            $new->bindValue(':cod_pres', $this->presentacion);
            $new->bindValue(":id", $this->id);
            $new->execute();

            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => "El produto se ha editado correctamente."];
        } catch (\PDOException $error) {
            return  $this->http_error(500, $error->getMessage());
        }
    }


    public function getEliminarProducto($id)
    {
        if (!$this->validarString('cod_producto', $id)) {
            return $this->http_error(400, "Código del producto a editar inválido.");
        }
        if (!$this->validarRegistrosProductos($id)) {
            return $this->http_error(400, "El producto ya tiene registros.");
        }
        $this->id = $id;
        return $this->eliminarProducto();
    }
    public function eliminarProducto()
    {
        try {
            $this->conectarDB();
            $new = $this->con->prepare("UPDATE producto SET status= 0 WHERE cod_producto = ?");
            $new->bindValue(1, $this->id);
            $new->execute();
            $this->desconectarDB();
            return ['resultado' => 'ok', 'msg' => 'El producto ha sido eliminado correctamente.'];
        } catch (\PDOException $error) {
            return  $this->http_error(500, $error->getMessage());
        }
    }




    public function mostrarLaboratorio()
    {
        try {
            $this->conectarDB();
            $new = $this->con->prepare("SELECT rif_laboratorio, razon_social FROM laboratorio WHERE status = 1");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            $this->desconectarDB();
            return $data;
        } catch (\PDOException $error) {

            return $error;
        }
    }


    public function mostrarTipo()
    {
        try {
            $this->conectarDB();
            $new = $this->con->prepare("SELECT id_tipo, nombre_t FROM tipo WHERE status = 1");
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $error) {
            return  $this->http_error(500, $error->getMessage());
        }
    }

    public function mostrarTipoPro()
    {
        try {
            $this->conectarDB();
            $new = $this->con->prepare("SELECT id_tipoprod, nombrepro FROM tipo_producto WHERE status = 1");
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $error) {
            return  $this->http_error(500, $error->getMessage());
        }
    }

    public function mostrarPresentacion()
    {
        try {
            $this->conectarDB();
            $query = "SELECT
                        p.cod_pres,
                        CONCAT(p.cantidad, ' X ', p.peso, ' ', m.nombre) as presentacion
                    FROM
                        presentacion p,
                        medida m
                    WHERE
                        p.status = 1
                        and p.id_medida = m.id_medida;";
            $new = $this->con->prepare($query);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $error) {
            return  $this->http_error(500, $error->getMessage());
        }
    }

    public function mostrarClase()
    {
        try {
            $this->conectarDB();
            $new = $this->con->prepare("SELECT * FROM clase c WHERE c.status = 1");
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $error) {
            return  $this->http_error(500, $error->getMessage());
        }
    }
}
