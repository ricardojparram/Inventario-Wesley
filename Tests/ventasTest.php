<?php 
use PHPUnit\Framework\TestCase;
use modelo\ventas as ventas;
 
class ventasTest extends TestCase{
    private $obj;

    public function setUp(): void {
       $this->obj = new ventas();
       $_SESSION['cedula'] = '123123123';
    }

    public function test_getMostrarVentas(){
 	   $res	= $this->obj->getMostrarVentas(false);
		 if (!isset($res[0])) {
			 $this->fail('No existen datos de ventas en la base de datos.');
		 }else{
			 $this->assertArrayHasKey('0', $res);
		 }
 	}

     public function testGetAgregarVenta(){
       $res = $this->obj->getAgregarVenta('TBaiIL0EALWh6IxCa82CfA==');

         if (isset($res["error"])) {
             $this->fail($res['error']);
         }else{
            $this->assertEquals('Venta general registrada', $res['resultado']);
        }

        $res2 = $this->obj->agregarVentaXProd(8,10.00,10, $res['id']);
        if (isset($res2["error"])) {
            $this->fail($res2['error']);
        }else{
           $this->assertEquals('Venta por producto registrada y stock actualizado', $res2['resultado']);
       }

       $res3 =  $this->obj->getPago(100.00, $res['id']);

        if (isset($res3["error"])) {
            $this->fail($res3['error']);
        }else{
           $this->assertEquals('Pago general registrada', $res3['resultado']);
       }

       $res4 = $this->obj->agregarDetallePago(1,100.00, $res3['id'],1);        
       if (isset($res4["error"])) {
        $this->fail($res4['error']);
       }else{
       $this->assertEquals('Detalles de pago registrada', $res4['resultado']);
       }
     }

     public function testValidarSelect(){
		$res = $this->obj->validarSelect('1');
		if(isset($res["error"])){
			$this->fail($res['error']);
		}elseif($res['resultado'] == 'Error de venta'){
			$this->fail($res['resultado']);
		}else{
			$this->assertEquals('Si existe esa venta.', $res['resultado']);
		}
	}

    public function testGetEliminar(){
        $res = $this->obj->getEliminar("xxxxxxxxx");
         if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        $this->assertEquals('Error de id', $res['resultado']);
    }

    public function testExportarFactura(){
		$res = $this->obj->ExportarFactura('2');
		if (isset($res["error"])) {
			$this->fail($res['error']);
		}else{
			$this->assertEquals('Archivo guardado', $res['respuesta']);
		}
	}

    public function testValidarCliente(){
		$res = $this->obj->validarCliente('TBaiIL0EALWh6IxCa82CfA==');
		if(isset($res["error"])){
			$this->fail($res['error']);
		}elseif($res['resultado'] == 'Error de id'){
			$this->fail($res['resultado']);
		}else{
			$this->assertEquals('cedula valida.', $res['resultado']);
		}
	}

    public function testgetDetalleV(){
        $res = $this->obj->getDetalleV('8');
        if (!isset($res[0])) {
           $this->fail('No existe detalles de producto en la base de datos.');
        }else{
           $this->assertArrayHasKey('0', $res);
        }
     }

     public function testGetDetalleTipoPago(){
        $res = $this->obj->getDetalleTipoPago('4');
        if (!isset($res[0])) {
           $this->fail('No existe detalles de tipo pago en la base de datos.');
        }else{
           $this->assertArrayHasKey('0', $res);
        }
     }

     public function testGetMostrarProducto(){
        $res = $this->obj->getMostrarProducto();
        if (!isset($res[0])) {
           $this->fail('No exist productos en la base de datos.');
        }else{
           $this->assertArrayHasKey('0', $res);
        }
     }

     public function testProductoDetalle(){
        $res = $this->obj->productoDetalle('10');
        if (!isset($res[0])) {
           $this->fail('No existe producto detalle en la base de datos.');
        }else{
           $this->assertArrayHasKey('0', $res);
        }
     }

     public function testGetMostrarMoneda(){
        $res = $this->obj->getMostrarMoneda();
        if (!isset($res[0])) {
           $this->fail('No existe monedas en la base de datos.');
        }else{
           $this->assertArrayHasKey('0', $res);
        }
     }

     public function testGetMostrarMetodo(){
        $res = $this->obj->getMostrarMetodo();
        if (!isset($res[0])) {
           $this->fail('No existe metodos en la base de datos.');
        }else{
           $this->assertArrayHasKey('0', $res);
        }
     }

     public function testGetMostrarCliente(){
        $res = $this->obj->getMostrarCliente();
        if (!isset($res[0])) {
           $this->fail('No existe clientes en la base de datos.');
        }else{
           $this->assertArrayHasKey('0', $res);
        }
     }
}



?>