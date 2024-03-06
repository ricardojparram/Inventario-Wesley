<?php 

use PHPUnit\Framework\TestCase;
use modelo\empresaEnvio as empresaEnvio;

/*
* @group empresas envio
* @group configuraciones
*/
class empresasEnvioTest extends TestCase{

 private $obj;

  public function setUp(): void {
    $this->obj = new empresaEnvio();
	$_SESSION['cedula'] = '123123123';
  }

  /** 
     * @test 
     * @group consultar
     * @group crud
    */

 	public function testmostrarEmpresas(){
 	   $res	= $this->obj->mostrarEmpresas(false);
		 if (!isset($res[0])) {
			 $this->fail('No existen empresas envio en la base de datos.');
		 }else{
			 $this->assertArrayHasKey('0', $res);
		 }
 	}

	public function testRegistrarEmpresa(){
		$res = $this->obj->getRegistrarEmpresa('1234567','ZOON', null);
		if (isset($res["error"])) {
			$this->fail($res['error']);
		}else{
			$this->assertEquals('Empresa registrado.', $res['resultado']);
		}
	}

	public function testValidarSelect(){
		$res = $this->obj->validarSelect('4');
		if(isset($res["error"])){
			$this->fail($res['error']);
		}elseif($res['resultado'] == 'Error de empresa'){
			$this->fail($res['resultado']);
		}else{
			$this->assertEquals('Si existe esa empresa.', $res['resultado']);
		}
	}

	public function testRellenarEdit(){
		$res = $this->obj->rellenarEdit('4');
		if (isset($res["error"])) {
			$this->fail($res["error"]);
		}elseif(!isset($res[0])){
			$this->fail('No existen empresas envio en la base de datos.');
		}else{
			$this->assertArrayHasKey('0', $res);
		}
	}

	public function testEditarEmpresa(){
		$res = $this->obj->getEditarEmpresa('12345678','ZOON', null,'3');
		if (isset($res["error"])) {
			$this->fail($res['error']);
		}else{
			$this->assertEquals('Empresa editado.', $res['resultado']);
		}
	}

	public function testGetEliminarEmpresa(){
        $res = $this->obj->getEliminarEmpresa("100");
         if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        $this->assertEquals('Error de empresa', $res['resultado']);
    }


}


?>