<?php

use PHPUnit\Framework\TestCase;
use modelo\clientes;

class clienteTest extends TestCase 
{
    private $obj;
    private string $cedula;
    private string $cedulaE;
    public function setUp():void{
        $this->obj = new clientes();
        $_SESSION['cedula'] = '123123123';
        $this->cedula = "8511698";
        $this->cedulaE = "k4rGvaO+qcckiueC+9OmjQ==";
    }

    /** 
     * @test 
     * @group consultar
     * @group crud
    */
    public function mostrarCliente(): void{
        $res = $this->obj->mostrarClientes(false);
        if(!isset($res[0]))
            $this->fail('No existen Clientes.');
        else
            $this->assertArrayHasKey('0', $res);
    }
    /** 
     * @test
     * @dataProvider datosValidacionCliente
     * @group validaciones
    */

    public function validacionesRegistrarCliente($nombre, $apellido, $cedula, $direccion, $telefono, $correo): void{
        $res = $this->obj->getRegistrarClientes($nombre, $apellido, $cedula, $direccion, $telefono, $correo);        
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('Error', $res['resultado']);
    }


    
    public function datosValidacioncliente(): array {
        
        
        return  [
            'cedula inválido' => [
                'Test', 'test', 'xxxxxxxx', 'cale 234', '04145556598', 'hola@gmail.com'
            ],
            'nombre inválido' => [
                '452551', 'test', '30349137', 'cale 234', '04145556598', 'hola@gmail.com'
            ],
            'apellido inválido' => [
                'test', '545555', '30349137', 'cale 234', '04145556598', 'hola@gmail.com'
            ],
            'correo invalido' => [
                'test', 'test', '30349137', 'cale 234', '04145556598', 'holagmailcom'
            ],
            'direccion invalida' => [
                'test', 'test', '30349137', 'cale&234%', '04145556598', 'hola@gmail.com'
            ],
            'Intento inyección SQL' => [
                'test', 'test', '30349137', 'cale 234', "'; drop table cliente ", 'hola@gmail.com'
            ],
        ];
    }

    /**
     * @test
     * @group registro
     * @group crud
    */


    public function registrarCliente(){
        
        $res = $this->obj->getRegistrarClientes('Test', 'test', $this->cedula, 'cale 234', '04145556598', 'hola@gmail.com');
        if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        if($res['resultado'] === "Error")
            if($res['error'] === "La cédula ya está registrada.")
                $this->fail($res['error']);
            else
                $this->fail($res['error']);
        else
            $this->assertEquals('Registrado correctamente.', $res['resultado']);
    }

    /** 
     * @test
     * @dataProvider datosValidacionCliente
     * @group validaciones
    */

    public function validacionesEditarCliente($nombre, $apellido, $cedula, $direccion, $telefono, $correo, $id = ""): void{
        $res = $this->obj->getEditar($nombre, $apellido, $cedula, $direccion, $telefono, $correo, $id);        
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('Error', $res['resultado']);
    }
    
    /**
     * @test
     * @group editar
     * @group crud
    */
    public function editarCliente(){
        $res = $this->obj->getEditar('Test', 'test', $this->cedula, 'cale 234', '04145556598', 'hola@gmail.com', $this->cedulaE);
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
    public function eliminarCliente(){
        $res = $this->obj->eliminarClientes($this->cedulaE);
         if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        $this->assertEquals('Eliminado', $res['resultado']);
    }

    /** 
     * @test 
     * @group consultarunico
     * @group crud
    */
    public function clienteUnico(): void{
        $res = $this->obj->unicoCliente($this->cedulaE);
        if(!isset($res[0]))
            $this->fail('No existe el cliente');
        else
            $this->assertArrayHasKey('0', $res);
    }
}

