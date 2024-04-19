<?php

namespace modelo;

use config\connect\DBConnect;

class proveedor extends DBConnect
{
    private $rif;
    private $direccion;
    private $razon;
    private $telefono;
    private $contacto;

    private $id;
    private $idedit;

    public function __construct()
    {
        parent::__construct();

    }

    public function mostrarProveedorAjax($bitacora)
    {

        try {
            parent::conectarDB();
            $query = "SELECT p.rif_proveedor, p.razon_social, p.direccion, c.telefono FROM proveedor as p
                      INNER JOIN contacto_prove as c ON c.rif_proveedor = p.rif_proveedor 
                      WHERE p.status = 1";
            $new = $this->con->prepare($query);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            echo json_encode($data);
            parent::desconectarDB();
            die();
        } catch (\PDOException $error) {
            return $error;

        }
    }

    public function getDatosPro($rif, $direccion, $razon, $telefono, $contacto)
    {

        if(preg_match_all("/^J-[0-9]{9,10}$/", $rif) != 1) {
            die(json_encode(['resultado' => 'Error de rif','msg' => 'Rif inválido.']));
        }
        if(preg_match_all("/^[a-zA-ZÀ-ÿ\s]{0,30}$/", $razon) != 1) {
            die(json_encode(['resultado' => 'Error de nombre','msg' => 'Nombre inválido.']));
        }
        if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){7,160}$/', $direccion) != 1) {
            die(json_encode(['resultado' => 'Error de direccion','msg' => 'Direccion inválida.']));
        }
        if(preg_match_all("/^[0-9]{10,30}$/", $telefono) != 1) {
            die(json_encode(['resultado' => 'Error de telefono','msg' => 'Telefono inválido.']));
        }

        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->telefono = $telefono;
        $this->contacto = $contacto;

        // var_dump($this->rif);
        // var_dump($this->direccion);
        // var_dump($this->razon);
        // var_dump($this->contacto);
        // die();
        //
        $this->registrarPro();

    }

    private function registrarPro()
    {

        try {
            parent::conectarDB();

            $sql = "INSERT INTO proveedor(rif_proveedor,direccion,razon_social,contacto,status) VALUES(?,?,?,?,1); ";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->rif);
            $new->bindValue(2, $this->direccion);
            $new->bindValue(3, $this->razon);
            $new->bindValue(4, $this->contacto);
            $new->execute();

            $new = $this->con->prepare("INSERT INTO `contacto_prove`(`telefono`, `rif_proveedor`) VALUES (?,?)");
            $new->bindValue(1, $this->telefono);
            $new->bindValue(2, $this->rif);
            $new->execute();

            $resultado = ['resultado' => 'ok'];
            echo json_encode($resultado);
            parent::desconectarDB();
            die();

        } catch (\PDOException $error) {
            return $error;
        }

    }

    public function getRif($rif)
    {

        if(preg_match_all("/^J-[0-9]{9,10}$/", $rif) != 1) {
            die(json_encode(['resultado' => 'Error de rif','msg' => 'Rif inválido.']));
        }

        $this->rif = $rif;


        return $this->validarRif();
    }

    private function validarRif()
    {


        try {
            $this->conectarDB();
            $new = $this->con->prepare("SELECT rif_proveedor FROM proveedor WHERE status = 1 and rif_proveedor = ?");
            $new->bindValue(1, $this->rif);
            $new->execute();
            $data = $new->fetchAll();



            $resultado;
            if(isset($data[0]['rif_proveedor'])) {

                $resultado = ['resultado' => 'Error de rif', 'msg' => 'El rif ya está registrado.', 'res' => false];
            } else {
                $resultado = ['resultado' => 'Rif válido.', 'res' => true];
            }
            $this->desconectarDB();
            return $resultado;

        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }

    }


    public function getItem($rif)
    {
        if(preg_match_all("/^J-[0-9]{9,10}$/", $rif) != 1) {
            die(json_encode(['resultado' => 'Error de rif','msg' => 'Rif inválido.']));
        }

        $this->id = $rif;
        $this->selectItem();
    }

    private function selectItem()
    {

        try {
            $this->conectarDB();
            $sql = "SELECT * FROM proveedor p 
                    INNER JOIN contacto_prove cp ON p.rif_proveedor = cp.rif_proveedor 
                    WHERE p.status = 1 and p.rif_proveedor = ? ;";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);
            $this->desconectarDB();
            die(json_encode($data));

        } catch(\PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }

    }

    public function getEditar($rif, $direccion, $razon, $telefono, $contacto)
    {

        if(preg_match_all("/^J-[0-9]{9,10}$/", $rif) != 1) {
            die(json_encode(['resultado' => 'Error de rif','msg' => 'Rif inválido.']));
        }
        if(preg_match_all("/^[a-zA-ZÀ-ÿ\s]{0,30}$/", $razon) != 1) {
            die($razon);
            die(json_encode(['resultado' => 'Error de nombre','msg' => 'Nombre inválido.']));
        }
        if(preg_match_all('/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\"\/,.-]){7,160}$/', $direccion) != 1) {
            die(json_encode(['resultado' => 'Error de direccion','msg' => 'Direccion inválida.']));
        }
        if(preg_match_all("/^[0-9]{10,30}$/", $telefono) != 1) {
            die(json_encode(['resultado' => 'Error de telefono','msg' => 'Telefono inválido.']));
        }


        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->razon = $razon;
        $this->telefono = $telefono;
        $this->contacto = $contacto;
        //$this->idedit = $id;


        $validarRif = $this->validarRif();



        if($validarRif['res'] === true) {
            die(json_encode(["resultado" => "error", "El proveedor no existe"]));
        }

        $this->editarProveedor();
    }

    private function editarProveedor()
    {



        try {
            $this->conectarDB();
            $sql = "UPDATE proveedor p
          SET p.rif_proveedor= ?, p.razon_social = ?, p.direccion= ? WHERE p.rif_proveedor = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->rif);
            $new->bindValue(2, $this->razon);
            $new->bindValue(3, $this->direccion);
            $new->bindValue(4, $this->rif);

            $new->execute();

            $sql = "UPDATE contacto_prove 
                    SET telefono = ? WHERE rif_proveedor = ?";
            $new = $this->con->prepare($sql);

            $new->bindValue(1, $this->telefono);
            $new->bindValue(2, $this->rif);

            $new->execute();
            $resultado = ['resultado' => 'ok',"msg" => "Proveedor ha sido editado correctamente."];
            //  $this->binnacle("Proveedor",$_SESSION['cedula'],"Editó proveedor.");

            $this->desconectarDB();
            die(json_encode($resultado));

        } catch(\PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }

    }


    public function getEliminar($rif)
    {
        if(preg_match_all("/^J-[0-9]{9,10}$/", $rif) != 1) {
            die(json_encode(['resultado' => 'Error de rif','msg' => 'Rif inválido.']));
        }

        $this->id = $rif;

        $this->eliminarProveedor();
    }

    private function eliminarProveedor()
    {
        try {
            $this->conectarDB();
            $new = $this->con->prepare("UPDATE proveedor SET status = 0 WHERE rif_proveedor = ?");
            $new->bindValue(1, $this->id);
            $new->execute();
            $resultado = ['resultado' => 'ok', 'msg' => "Proveedor ha sido eliminado correctamente."];
            // $this->binnacle("Proveedor",$_SESSION['cedula'],"Eliminó proveedor.");
            $this->desconectarDB();
            die(json_encode($resultado));

        } catch(\PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }

    }
}
