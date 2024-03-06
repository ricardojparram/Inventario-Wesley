<?php  

use PHPUnit\Framework\TestCase;
use modelo\envios;

/**
 * @group envios
 * @group configuraciones 
 * @group relacionadoPagos
*/
class enviosTest extends TestCase{

    private $obj;
    public function setUp():void{
        $this->obj = new envios();
        $_SESSION['cedula'] = '123123123';
    }   

	/** 
     * @test 
     * @group consultar
     * @group crud
    */
    public function mostrarEnvios(): void{
        $res = $this->obj->mostrarEnvios(false);
        if(!isset($res[0]))
            $this->fail('No existen envíos en la base de datos.');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
     * @dataProvider datosValidacionComprobarEnvios
     * @group validaciones
    */
    public function validacionesComprobarEnvios($id_envio, $estado): void{
        $res = $this->obj->getComprobacion($id_envio, $estado);        
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }

    public function datosValidacionComprobarEnvios(): array {
        /* Descripcion  de los datasets:
            1: id_envio, 2: estado(status del pago)
        */
        return  [
            'Id_envio inválida' => [
                'asdgdsg', '1'
            ],
            'Status inválido' => [
                '1', '$"!!###'
            ],
        ];
    }

    /**
     * @test
     * @group consultar
    */
    public function calcularPrecioEnvio(){
        $res = $this->obj->calcularPrecioEnvio();
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado', $res);

        if($res['resultado'] === 'error')
            $this->fail($res['msg']);

        $this->assertEquals('ok', $res['resultado']);
    }
    
}

?>
