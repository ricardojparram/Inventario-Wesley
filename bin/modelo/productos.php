<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;

class productos extends DBConnect {
    private $cod_producto;
    private $tipoprod;
    private $presentacion;
    private $laboratorio;
    private $tipoP;
    private $clase;
    private $composicionP;
    private $posologia;
    private $contraIn;
    private $id;


    public function __construct() {
        parent::__construct();
    }



    public function getRegistraProd($cod_producto, $tipoprod, $presentacion, $laboratorio, $tipoP, $clase, $composicionP, $posologia, $contraIn) {



        // if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,200}$/", $tipoprod) !== 1){
        // return ['resultado' => 'error', 'error' => 'Descripcion inválida'];
        //}
        //if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,50}$/", $composicionP) !== 1){
        //return ['resultado' => 'error','error' => 'Composicion inválida.'];
        //}
        //if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,400}$/", $posologia) !== 1){
        //return ['resultado' => 'error','error' => 'Posologia inválida.'];
        //}
        //if(preg_match_all("/^[a-fA-F0-9]{10}$/", $laboratorio) != 1){
        //return ['resultado' => 'error','error' => 'Laboratorio inválido.'];
        //}
        //if(preg_match_all("/^[0-9]{1,10}$/", $tipoP) !== 1){
        //return ['resultado' => 'error','error' => 'Tipo inválido.'];
        //}
        //if(preg_match_all("/^[0-9]{1,10}$/", $clase) !== 1){
        //return ['resultado' => 'error','error' => 'Clase inválida.'];
        //}
        //if(preg_match_all("/^[0-9]{1,10}$/", $presentación) !== 1){
        //return ['resultado' => 'error','error' => 'Presentación inválida.'];
        //}
        //if(preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,400}$/", $contraIn) !== 1){
        //return ['resultado' => 'error','error' => 'Contraindicaciones inválidas.'];
        //}


        // date_default_timezone_set("america/caracas");
        //$time = date("Y-m-d");

        //if(strftime($time) > strftime($fechaV)){
        //return ['resultado' => 'error', 'error' => 'La fecha es menor'];
        //}

        $this->cod_producto = $cod_producto;
        $this->tipoprod = $tipoprod;
        $this->composicionP = $composicionP;
        $this->contraIn = $contraIn;
        $this->posologia = $posologia;
        $this->laboratorio = $laboratorio;
        $this->tipoP = $tipoP;
        $this->clase = $clase;
        $this->presentacion = $presentacion;


        $this->registraProd();
    }

    private function registraProd() {
        try {
            parent::conectarDB();

            $new = $this->con->prepare("INSERT INTO producto(
                                          cod_producto,
                                          composicion,
                                          contraindicaciones,
                                          posologia,
                                          rif_laboratorio,
                                          id_tipo,
                                          id_clase,
                                          cod_pres,
                                          id_tipoprod,
                                          status) VALUES (?,?,?,?,?,?,?,?,?,1)");

            $new->bindValue(1, $this->cod_producto);
            $new->bindValue(2, $this->composicionP);
            $new->bindValue(3, $this->contraIn);
            $new->bindValue(4, $this->posologia);
            $new->bindValue(5, $this->laboratorio);
            $new->bindValue(6, $this->tipoP);
            $new->bindValue(7, $this->clase);
            $new->bindValue(8, $this->presentacion);
            $new->bindValue(9, $this->tipoprod);
            //$new->bindValue(10, $this->1);
            $new->execute();

            $result = ['resultado' => 'Registrado'];
            parent::desconectarDB();
            return $result;
        } catch (\PDOException $error) {
            die($error);
        }
    }


    public function MostrarEditProductos($id) {
        try {


            parent::conectarDB();
            $this->id = $id;
            $new = $this->con->prepare("SELECT p.cod_producto, p.composicion, p.contraindicaciones, p.posologia, p.rif_laboratorio, p.id_tipo, p.id_clase, p.cod_pres, p.id_tipoprod, p.status FROM producto p WHERE p.status = 1 and p.cod_producto = ?");
            $new->bindValue(1, $this->id);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {

            return $error;
        }
    }



    public function getEditarProd($cod_producto, $tipoprod, $presentacion, $laboratorio, $tipoP, $clase, $composicionP, $posologia, $contraIn, $id) {

        // if (preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,200}$/", $tipoprod) !== 1) {
        //     return ['resultado' => 'error', 'error' => 'Descripcion inválida'];
        // }
        // if (preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,50}$/", $composicionP) !== 1) {
        //     return ['resultado' => 'error', 'error' => 'Composicion inválida.'];
        // }
        // if (preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,400}$/", $posologia) !== 1) {
        //     return ['resultado' => 'error', 'error' => 'Posologia inválida.'];
        // }
        // if (preg_match_all("/^[a-fA-F0-9]{10}$/", $laboratorio) != 1) {
        //     return ['resultado' => 'error', 'error' => 'Laboratorio inválido.'];
        // }
        // if (preg_match_all("/^[0-9]{1,10}$/", $tipoP) !== 1) {
        //     return ['resultado' => 'error', 'error' => 'Tipo inválido.'];
        // }
        // if (preg_match_all("/^[0-9]{1,10}$/", $clase) !== 1) {
        //     return ['resultado' => 'error', 'error' => 'Clase inválida.'];
        // }
        // if (preg_match_all("/^[0-9]{1,10}$/", $presentacion) !== 1) {
        //     return ['resultado' => 'error', 'error' => 'Presentación inválida.'];
        // }
        // if (preg_match_all("/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\/\s()#,.-]){3,400}$/", $contraIn) !== 1) {
        //     return ['resultado' => 'error', 'error' => 'Contraindicaciones inválidas.'];
        // }


        $this->id = $id;
        $this->cod_producto = $cod_producto;
        $this->tipoprod = $tipoprod;
        $this->composicionP = $composicionP;
        $this->contraIn = $contraIn;
        $this->posologia = $posologia;
        $this->laboratorio = $laboratorio;
        $this->tipoP = $tipoP;
        $this->clase = $clase;
        $this->presentacion = $presentacion;

        return $this->editarProd();
    }


    private function editarProd() {
        try {
            parent::conectarDB();
            $sql = "UPDATE
                        producto p
                    SET
                        cod_producto = ?,
                        id_tipoprod = ?,
                        composicion = ?,
                        contraindicaciones = ?,
                        posologia = ?,
                        rif_laboratorio = ?,
                        id_tipo = ?,
                        id_clase = ?,
                        cod_pres = ?
                    WHERE
                        p.status = 1
                        AND p.cod_producto = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->cod_producto);
            $new->bindValue(2, $this->tipoprod);
            $new->bindValue(3, $this->composicionP);
            $new->bindValue(4, $this->contraIn);
            $new->bindValue(5, $this->posologia);
            $new->bindValue(6, $this->laboratorio);
            $new->bindValue(7, $this->tipoP);
            $new->bindValue(8, $this->clase);
            $new->bindValue(9, $this->presentacion);
            $new->bindValue(10, $this->id);
            $new->execute();

            $resultado = ['resultado' => 'Editado'];
            parent::desconectarDB();
            return $resultado;
        } catch (\PDOException $error) {
            return $error;
        }
    }


    public function getEliminarProd($id) {
        try {
            parent::conectarDB();
            $this->id = $id;
            $new = $this->con->prepare("UPDATE `producto` SET `status`= 0 WHERE cod_producto = ?");
            $new->bindValue(1, $this->id);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {
            return $error;
        }
    }

    public function MostrarProductos() {
        try {
            parent::conectarDB();
            $query = "SELECT p.cod_producto, t.nombrepro, concat(cantidad, ' x ', peso, ' ', nombre) as pres FROM producto p, tipo_producto t, presentacion pr, medida m  WHERE p.status = 1 and t.id_tipoprod = p.id_tipoprod and pr.cod_pres = p.cod_pres and m.id_medida = pr.id_medida";

            $new = $this->con->prepare($query);
            $new->execute();
            $data = $new->fetchAll();
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {
            return $error;
        }
    }


    public function mostrarLaboratorio() {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT * FROM laboratorio l WHERE l.status = 1");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {

            return $error;
        }
    }


    public function mostrarTipo() {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT * FROM tipo t WHERE t.status = 1");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {

            return $error;
        }
    }


    public function mostrarTipoPro() {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT * FROM tipo_producto  WHERE status = 1");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {

            return $error;
        }
    }

    public function mostrarPresentacion() {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT * FROM presentacion p, medida m  WHERE p.status = 1 and p.id_medida = m.id_medida");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {

            return $error;
        }
    }

    public function mostrarClase() {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT * FROM clase c WHERE c.status = 1");
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            parent::desconectarDB();
            return $data;
        } catch (\PDOException $error) {

            return $error;
        }
    }

    // public function mostrarImg($id) {
    //     $this->id = $id;

    //     return $this->productImg();
    // }

    // private function productImg() {
    //     try {
    //         parent::conectarDB();
    //         $sql = "SELECT img FROM producto where cod_producto = ?";
    //         $new = $this->con->prepare($sql);
    //         $new->bindValue(1, $this->id);
    //         $new->execute();
    //         $data = $new->fetchAll(\PDO::FETCH_OBJ);
    //         parent::desconectarDB();
    //         return $data;
    //     } catch (\PDOException $e) {
    //         die($e);
    //     }
    // }

    // public function getEditarImg($foto, $id, $borrar = false) {

    //     if (preg_match_all("/^[0-9]{1,10}$/", $id) == false) {
    //         return ['resultado' => 'error', 'error' => 'Producto inválido.'];
    //     }

    //     $this->foto = $foto;
    //     $this->id = $id;

    //     $res;
    //     if ($borrar != false) {
    //         $res = $this->borrarImagen();
    //     }
    //     if (isset($this->foto['name'])) {
    //         $res = $this->editarImagen();
    //     }
    //     return $res;
    // }

    // private function editarImagen() {

    //     if ($this->foto['error'] > 0) {
    //         return ['respuesta' => 'error', 'error' => 'Error de imágen'];
    //     }
    //     if ($this->foto['type'] != 'image/jpeg' && $this->foto['type'] != 'image/jpg' && $this->foto['type'] != 'image/png') {
    //         return ['respuesta' => 'error', 'error' => 'Tipo de imagen inválido.'];
    //     }

    //     $repositorio = "assets/img/productos/";
    //     $extension = pathinfo($this->foto['name'], PATHINFO_EXTENSION);
    //     $date = date('m/d/Yh:i:sa', time());
    //     $rand = rand(1000, 9999);
    //     $imgName = $date . $rand;
    //     $nameEnc = md5($imgName);
    //     $nombre =  $repositorio . $nameEnc . '.' . $extension;

    //     if (move_uploaded_file($this->foto['tmp_name'], $nombre)) {
    //         try {
    //             parent::conectarDB();
    //             $new = $this->con->prepare('SELECT img FROM producto WHERE cod_producto = ?');
    //             $new->bindValue(1, $this->id);
    //             $new->execute();
    //             $data = $new->fetchAll(\PDO::FETCH_OBJ);
    //             $fotoActual = $data[0]->img;

    //             if ($fotoActual != $this->imagenPorDefecto) {
    //                 unlink($fotoActual);
    //             }

    //             $new = $this->con->prepare('UPDATE producto SET img = ? WHERE cod_producto = ?');
    //             $new->bindValue(1, $nombre);
    //             $new->bindValue(2, $this->id);
    //             $new->execute();
    //             parent::desconectarDB();

    //             return ['respuesta' => 'ok', 'msg' => "La imagen del producto se ha actualizado correctamente."];
    //         } catch (\PDOException $error) {
    //             return $error;
    //         }
    //     } else {
    //         return ['respuesta' => 'No se guardó la imagen.'];
    //     }
    // }

    // private function borrarImagen() {

    //     try {

    //         parent::conectarDB();
    //         $new = $this->con->prepare('SELECT img FROM producto WHERE cod_producto = ?');
    //         $new->bindValue(1, $this->id);
    //         $new->execute();
    //         $data = $new->fetchAll(\PDO::FETCH_OBJ);
    //         $fotoActual = $data[0]->img;

    //         $new = $this->con->prepare("UPDATE producto SET img = ? WHERE cod_producto = ?");
    //         $new->bindValue(1, $this->imagenPorDefecto);
    //         $new->bindValue(2, $this->id);
    //         $new->execute();
    //         parent::desconectarDB();

    //         if ($fotoActual != $this->imagenPorDefecto) {
    //             unlink($fotoActual);
    //         }

    //         return ['respuesta' => 'ok', 'msg' => "La imagen ha sido eliminada correctamente."];
    //     } catch (\PDOException $e) {
    //         return $e;
    //     }
    // }
}
