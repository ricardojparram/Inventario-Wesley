<?php

use PHPUnit\Framework\TestCase;
use modelo\pago;

class pagoTest extends TestCase 
{
    private $obj;
    private string $cedula;
    public function setUp():void{
        $this->obj = new pago();
        $_SESSION['cedula'] = '123123123';
        $this->cedula = 'O9OnH0ox4pHUYNZMrowa2Q==';
    }

     /**
     * @test
    */

    public function tiempoPago(){
        
        $res = $this->obj->comprobarTiempoDePago($this->cedula);
        if ($res['resultado'] == 'Eliminado correctamente.') 
            $this->assertEquals('Eliminado correctamente.', $res['resultado']);
        elseif ($res['resultado'] == 'Nada') 
            $this->assertEquals('Nada', $res['resultado']);
        else 
            $this->fail('No se encontro');    
    }

    /** 
     * @test
    */
    public function mostrarDatos(): void{
        $res = $this->obj->mostrarDatosP($this->cedula);
        if(!isset($res[0]))
            $this->fail('No existen los datos personales.');
        else
            $this->assertArrayHasKey('0', $res);
    }
    
    /** 
     * @test
    */
    public function mostrarMetodos(): void{
        $res = $this->obj->getMostrarMetodo();
        if(!isset($res[0]))
            $this->fail('No existen los metodos de pago');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
    */
    public function mostrarTipoP(): void{
        $res = $this->obj->tipoP('4');
        if(!isset($res[0]))
            $this->fail('No existen los tipos de pago');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
    */
    public function mostrarPrecio(): void{
        $res = $this->obj->mostrarPrecio($this->cedula);
        if(!isset($res[0]))
            $this->fail('No existen los precios');
        else
            $this->assertArrayHasKey('0', $res);
    }
    
    /** 
     * @test
    */
    public function mostrarEstados(): void{
        $res = $this->obj->mostrarEstados();
        if(!isset($res[0]))
            $this->fail('No existen los estados en la base de datos');
        else
            $this->assertArrayHasKey('0', $res);
    }
    
    /** 
     * @test
    */
    public function mostrarSede(): void{
        $res = $this->obj->mostrarSede('1');
        if(!isset($res[0]))
            $this->fail('No existen la sedes en la base de datos');
        else
            $this->assertArrayHasKey('0', $res);
    }
    
    /** 
     * @test
    */
    public function mostrarBanco(): void{
        $res = $this->obj->banco();
        if(!isset($res[0]))
            $this->fail('No existen la sedes en la base de datos');
        else
            $this->assertArrayHasKey('0', $res);
    }
    
    /** 
     * @test
    */
    public function calcularTipoProducto(): void{
        $res = $this->obj->calcularTipo($this->cedula);
        if(isset($res['resultado']))
            if($res['resultado'] == 'cuenta superior')
                $this->assertEquals('cuenta superior', $res['resultado']);
            else
                $this->assertEquals('cuenta regulada', $res['resultado']);
        else
            $this->fail('error');
    }

    /** 
     * @test
    */
    public function calcularTiempo(): void{
        $res = $this->obj->temporizador($this->cedula);
        if(!isset($res[0]))
            $this->fail('No existen en la base de datos');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
    */
    public function eliminar(): void{
        $res = $this->obj->getEliminar($this->cedula, 'contar');
        if(!isset($res['resultado']))
            $this->fail('No existen la venta');
        else
            $this->assertEquals('Eliminado', $res['resultado']);
    }
    /** 
     * @test
    */
    public function Registrar(): void{
        $res = $this->obj->getEliminar($this->cedula, 'contar');
        if(!isset($res['resultado']))
            $this->fail('No existen la venta');
        else
            $this->assertEquals('Eliminado', $res['resultado']);
    }

    public function datosValidacionesRegistrar() {
        return [
                'cedula inválido' => [
                    'xxxxxxxx', 'Test', 'test', 'cale 234', '04145556598', 'hola@gmail.com','1', 'cale 234fs', ""
                ],
                'nombre inválido' => [
                    '30349515', '2342', 'test', 'cale 234', '04145556598', 'hola@gmail.com','1', 'cale 234fs', ""
                ],
                'apellido inválido' => [
                    '30349515', 'Test', '3123', 'cale 234', '04145556598', 'hola@gmail.com','1', 'cale 234fs', ""
                ],
                'direccion invalida' => [
                    '30349515', 'test', 'test', '%$#$%', '04145556598', 'hola@gmail.com','1', 'cale 234fs', ""
                ],
                'correo invalido' => [
                    '30349515', 'test', 'test', 'casdad asda s', '04145556598', 'holagmailcom','1', 'cale 234fs', ""
                ],
                'direccion invalida' => [
                    '30349515', 'test', 'test', 'casdad asda s', '04145556598', 'hola@gmail.com','1', '423%$', ""
                ],
                'Intento inyección SQL' => [
                    '30349515', 'test', 'test', 'casdad asda s', '04145556598', 'hola@gmail.com',"'; drop table cliente ", '423%$'
                ],
            
            ];
    }

    /** 
     * @test
     * @dataProvider datosValidacionesRegistrar
     * @group validaciones
    */

    public function validacionesRegistrarCliente($cedula, $nombre, $apellido, $direccionF, $telefono, $correo, $sede, $direccionE, $detalles): void{
        $res = $this->obj->getRegistrarClientes($cedula, $nombre, $apellido, $direccionF, $telefono, $correo, $sede, $direccionE, $detalles);        
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('Error', $res['resultado']);
    }






}
