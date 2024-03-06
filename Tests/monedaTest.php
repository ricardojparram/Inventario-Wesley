<?php

use PHPUnit\Framework\TestCase;
use modelo\moneda;

class monedaTest extends TestCase 
{
    private $obj;
    private string $nombre;
    public function setUp():void{
        $this->obj = new moneda();
        $_SESSION['cedula'] = '123123123';
        $this->nombre = 'Rublos';
    }

    /** 
     * @test 
     * @group consultar
     * @group crud
    */
    public function mostrarMoneda(): void{
        $res = $this->obj->getMoneda(false);
        if(!isset($res[0]))
            $this->fail('No existen monedas.');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
     * @group validaciones
    */

    public function validacioneRegistrarMoneda(){
        $res = $this->obj->getAgregarMoneda("'; drop table cliente ");     
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }

     /**
     * @test
     * @group registro
     * @group crud
    */

    public function registrarMoneda(){
        
        $res = $this->obj->getAgregarMoneda($this->nombre);
        if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        if($res['resultado'] === "error")
                $this->fail($res['error']);
        else
            $this->assertEquals('Registado con exito', $res['resultado']);
        return $res['idR'];
    }

    /** 
     * @test 
     * @group consultar
     * @group crud
     * @depends registrarMoneda
    */
    public function mostrarMonedaUnica($idR): void{
        $res = $this->obj->mostrarM($idR);
        if(!isset($res[0]))
            $this->fail('No existe la moneda.');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
     * @group validaciones
    */

    public function validacioneEditarMoneda(){
        $res = $this->obj->getEditarM("'; drop table cliente ", "");     
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }

     /**
     * @test
     * @group editar
     * @group crud
     * @depends registrarMoneda
    */

    public function EditarMoneda($idR){
        
        $res = $this->obj->getEditarM($this->nombre, $idR);
        if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        if($res['resultado'] === "error")
                $this->fail($res['error']);
        else
            $this->assertEquals('Actualizado con exito', $res['resultado']);
    }

    
    /** 
     * @test 
     * @group consultar
     * @group crud
     * @depends registrarMoneda
     * 
     */
    public function mostrarCambio($idR): void{
        $res = $this->obj->getMostrarCambio($idR);
        if(!isset($res[0]))
        $this->fail('No existen los cambios');
    else
    $this->assertArrayHasKey('0', $res);
    }

    /**
     * @test
     * @group eliminar
     * @group crud
     * @depends registrarMoneda
    */
    public function eliminarMoneda($idR){
        $res = $this->obj->getEliminarM($idR);
        if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        $this->assertEquals('Eliminado con exito', $res['resultado']);
    }
    /** 
     * @test 
    */
    public function SelectM(): void{
        $res = $this->obj->SelectM();
        if(!isset($res[0]))
            $this->fail('No existen los cambios en el select');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
     * @group validaciones
    */

    public function validacioneRegistrarCambio(){
        $res = $this->obj->getAgregarCambio("'; drop table cliente ", "dasdassa");     
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('Error', $res['resultado']);
    }

     /**
     * @test
     * @group registro
     * @group crud
     * @depends registrarMoneda
    */

    public function registrarCambio($idR){
        
        $res = $this->obj->getAgregarCambio("14.8", $idR);
        if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        if($res['resultado'] === "Error")
                $this->fail($res['error']);
        else
            $this->assertEquals('Registado con exito', $res['resultado']);
        return $res['idC'];
    }

    /**
     * @test
     * @group editar
     * @group crud
     * @depends registrarCambio
     * @depends registrarMoneda
    */

    public function EditarCambio($idC, $idR){
        
        $res = $this->obj->getEditarCambio('15.8', $idR, $idC);
        if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        if($res['resultado'] === "Error")
                $this->fail($res['error']);
        else
            $this->assertEquals('Editado', $res['resultado']);
    }

    /** 
     * @test
     * @group validaciones
    */

    public function validacioneEditarCambio(){
        $res = $this->obj->getAgregarCambio("'; drop table cliente ", "dasdassa", $id = "");     
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('Error', $res['resultado']);
    }

    /**
     * @test
     * @group eliminar
     * @group crud
     * @depends registrarCambio
    */
    public function eliminarCambio($idC){
        $res = $this->obj->getEliminarCambio($idC);
        if(!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        
        $this->assertEquals('Eliminado', $res['resultado']);
    }

    /** 
     * @test 
     * @group consultar
     * @group crud
     * @depends registrarCambio
    */
    public function mostrarCambioUnico($idC): void{
        $res = $this->obj->mostrarUnico($idC);
        if(!isset($res[0]))
            $this->fail('No existe el cambio.');
        else
            $this->assertArrayHasKey('0', $res);
    }


}
