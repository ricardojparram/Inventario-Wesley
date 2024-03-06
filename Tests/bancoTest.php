<?php 
use PHPUnit\Framework\TestCase;
use modelo\banco as banco;

/*
* @group banco
* @group configuraciones
*/
class bancoTest extends TestCase{

    private $obj;

    public function setUp(): void {
       $this->obj = new banco();
       $_SESSION['cedula'] = '123123123';
    }

      /** 
     * @test 
     * @group consultar
     * @group crud
    */

 	public function testMostrarBank(){
 	   $res	= $this->obj->mostrarBank(false);
		 if (!isset($res[0])) {
			 $this->fail('No existen datos cobro banco en la base de datos.');
		 }else{
			 $this->assertArrayHasKey('0', $res);
		 }
 	}

    public function testDatosBanco(){
      $res	= $this->obj->datosBanco();
      if (!isset($res[0])) {
         $this->fail('No existen banco en la base de datos.');
      }else{
         $this->assertArrayHasKey('0', $res);
      }
   }

   public function testSelecTipoPago(){
      $res	= $this->obj->SelecTipoPago();
      if (!isset($res[0])) {
         $this->fail('No existen tipos de pago en la base de datos.');
      }else{
         $this->assertArrayHasKey('0', $res);
      }
   }

   public function testvalidarTipoP(){
		$res = $this->obj->validarTipoP('4');
		if(isset($res["error"])){
			$this->fail($res['error']);
		}elseif($res['resultado'] == 'Error de id'){
			$this->fail($res['resultado']);
		}else{
			$this->assertEquals('Codigo tipo válido.', $res['resultado']);
		}
	}

   public function testValidarDatos(){
		$res = $this->obj->ValidarDatos('Transferencia','2323223-23232','12121212',2 , null);
		if(isset($res["error"])){
			$this->fail($res['error']);
		}elseif($res['resultado'] == 'Error de empresa'){
			$this->fail($res['resultado']);
		}else{
			$this->assertEquals('Datos validos', $res['resultado']);
		}
	}

 

   public function testGetRegistrarBanco(){
      $datoT = ['Transferencia', '2', '30233547', '2323223-2345454'];
      $datoP = ['Pago movil', '2', '21727935', '04245699810'];
		$res = $this->obj->getRegistrarBanco($datoP);
		if (isset($res["error"])) {
			$this->fail($res['error']);
		}else{
			$this->assertEquals('banco registrado.', $res['resultado']);
		}
	}

   public function testValidarSelect(){
		$res = $this->obj->validarSelect('4');
		if(isset($res["error"])){
			$this->fail($res['error']);
		}elseif($res['resultado'] == 'Error de banco'){
			$this->fail($res['resultado']);
		}else{
			$this->assertEquals('Si existe esa banco.', $res['resultado']);
		}
	}

   public function testGetEditarBanco(){
      $datoT = ['Transferencia', '2', '30233547', '2323223-2345454'];
      $datoP = ['Pago movil', '2', '21727935', '04245699810'];
		$res = $this->obj->getEditarBanco($datoP, 35);
		if (isset($res["error"])) {
			$this->fail($res['error']);
		}else{
			$this->assertEquals('banco editado.', $res['resultado']);
		}
	}

   public function testGetEliminarBanco(){
      $res = $this->obj->getEliminarBanco("wewe");
       if(!isset($res["resultado"]))
          $this->assertArrayHasKey('resultado',  $res);
      
      $this->assertEquals('Error de banco', $res['resultado']);
  }

}


?>