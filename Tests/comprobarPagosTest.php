<?php  

use PHPUnit\Framework\TestCase;
use modelo\comprobarPago;

/**
 * @group comprobarPago
 * @group configuraciones 
 * @group relacionadoPagos
*/
class comprobarPagosTest extends TestCase{

    private $obj;
    public function setUp():void{
        $this->obj = new comprobarPago();
        $_SESSION['cedula'] = '123123123';
    }   

	/** 
     * @test 
     * @group consultar
     * @group crud
    */
    public function mostrarPagos(): void{
        $res = $this->obj->mostrarPagos(false);
        if(!isset($res[0]))
            $this->fail('No existen pagos para comprobar en la base de datos.');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
     * @dataProvider datosValidacionComprobarPagos
     * @group validaciones
    */
    public function validacionesComprobarPagos($id_pago, $estado): void{
        $res = $this->obj->getComprobacion($id_pago, $estado);        
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }

    public function datosValidacionComprobarPagos(): array {
        /* Descripcion  de los datasets:
            1: id_pago, 2: estado(status del pago)
        */
        return  [
            'Id_pago inválida' => [
                'asdgdsg', '1'
            ],
            'Estado inválido' => [
                '1', '$"!!###'
            ],
        ];
    }

    /**
     * @test
     * @group validaciones
    */
    public function detallePago(){
        $res = $this->obj->getDetallePago('asdgadsgds');
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }
    
}

?>
