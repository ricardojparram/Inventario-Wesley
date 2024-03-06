<?php

use PHPUnit\Framework\TestCase;
use modelo\usuarios;

class usuarioTest extends TestCase
{
    private $obj;
    private string $cedula;
    private string $cedulaE;
    public function setUp():void{
        $this->obj = new usuarios();
        $_SESSION['cedula'] = '123123123';
        $this->cedula = "8511698";
        $this->cedulaE = "k4rGvaO+qcckiueC+9OmjQ==";
    }

    /** 
     * @test 
     * @group consultar
     * @group crud
    */
    public function mostrarUsuario(): void{
        $res = $this->obj->getMostrarUsuario(false);
        if(!isset($res[0]))
            $this->fail('No existen usuarios.');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
     * @dataProvider datosValidacionUsuario
     * @group validaciones
    */

    public function validacionesRegistrarUsuario($cedula, $name, $apellido, $email, $password, $tipoUsuario): void{
        $res = $this->obj->getAgregarUsuario($cedula, $name, $apellido, $email, $password, $tipoUsuario);        
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }


    
    public function datosValidacionUsuario(): array {
        /* Descripcion  de los datasets:
            1: cedula, 2: nombre, 3: apellido, 4: correo, 5: password, 6: tipo_usuario
        */
        
        return  [
            'cedula inválido' => [
                'xxxxxxxx', 'Test', 'test', 'hola@gmail.com', '1234qwer', '1'
            ],
            'nombre inválido' => [
                '30349137', '452551', 'test', 'hola@gmail.com', '1234qwer', '1'
            ],
            'apellido inválido' => [
                '30349137', 'test', '545555', 'hola@gmail.com', '1234qwer', '1'
            ],
            'correo invalido' => [
                '30349137', 'test', 'test', 'holagmailcom', '1234qwer', '1'
            ],
            'Intento inyección SQL' => [
                '30349137', 'test', 'test', 'holagmailcom', '1234qwer', "'; drop table cliente "
            ],
            'Tipo invalido' => [
                '30349137', 'test', 'test', 'holagmailcom', '1234qwer', 'wq'
            ]
        ];
    }

    /**
     * @test
     * @group registro
     * @group crud
    */


    public function registrarUsuario(){
        
        $res = $this->obj->getAgregarUsuario($this->cedula, 'victor', 'apalicio', 'aparicio@gmail.com', '1234qwer', '1');
        if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        if($res['resultado'] === "error")
            if($res['error'] === "error desconocido.")
                $this->fail("errorVAl");
            else
                $this->fail($res['error']);
        else
            $this->assertEquals('Registrado correctamente.', $res['resultado']);
    }

    /** 
     * @test
     * @dataProvider datosValidacionUsuario
     * @group validaciones
    */

    public function validacionesEditarUsuario($cedula, $name, $apellido, $email, $password, $tipoUsuario, $id = ""): void{
        $res = $this->obj->getEditar($cedula, $name, $apellido, $email, $password, $tipoUsuario, $id);        
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }



    /**
     * @test
     * @group editar
     * @group crud
    */
    public function editarUsuario(){
        $res = $this->obj->getEditar($this->cedula, 'david', 'rivero', 'apariciovictor@gmail.com', '1234qwer', '1',$this->cedulaE);
        if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        if($res['resultado'] === "Editado")    
            $this->assertEquals('Editado', $res['resultado']);
        if($res['resultado'] === "error")    
            $this->fail($res['error']);
            
    }

    /**
     * @test
     * @group validaciones
    */

    public function validarCedula() {
        $res = $this->obj->getValidarC($this->cedula, $this->cedulaE);
        if($res['resultado'] === "Error")
                $this->fail($res['msj']);
        else
            $this->assertEquals('Correcto', $res['resultado']);
    }
    /**
     * @test
     * @group validaciones
    */

    public function validarCorreo() {
        $res = $this->obj->getValidarE("apariciovictor@gmail.com", $this->cedulaE);
        if($res['resultado'] === "Error")
                $this->fail($res['msj']);
        else
            $this->assertEquals('Correcto', $res['resultado']);
    }
    
    /**
     * @test
     * @group eliminar
     * @group crud
    */
    public function eliminarUsuario(){
        $res = $this->obj->getEliminar($this->cedulaE);
         if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        $this->assertEquals('Eliminado', $res['resultado']);
    }

    /** 
     * @test 
     * @group consultarunico
     * @group crud
    */
    public function usuarioUnico(): void{
        $res = $this->obj->getUnico($this->cedulaE);
        if(!isset($res[0]))
            $this->fail('No existe el usuario');
        else
            $this->assertArrayHasKey('0', $res);
    }



}
