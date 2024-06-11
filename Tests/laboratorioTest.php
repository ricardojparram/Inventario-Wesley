<?php

use PHPUnit\Framework\TestCase;
use modelo\laboratorio;

/**
 * @group laboratorio
 * @group relacionadosProductos 
 */
class laboratorioTest extends TestCase
{

    private $obj;
    private string $rif; // numeros 7-10. ej: "123123123"
    public function setUp(): void
    {
        $this->obj = new laboratorio();
        $_SESSION['cedula'] = '123123123';
        $this->rif = "J-321321321";
    }

    /**
     * @test 
     * @group consultar
     * @group crud
     */
    public function mostrarLaboratorio(): void
    {
        $res = $this->obj->mostrarLaboratorios(false);
        if (!isset($res[0]))
            $this->fail('No existen laboratorios en la base de datos.');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
     * @dataProvider datosValidacionLaboratorio
     * @group validaciones
     */
    public function validacionesRegistrarLaboratorio($rif, $direccion, $razon): void
    {
        $res = $this->obj->getRegistrarLaboratorio($rif, $direccion, $razon);
        if (!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }

    public function datosValidacionLaboratorio(): array
    {
        /* Descripcion  de los datasets:
            1: rif, 2: direccion, 3: razon_social(nombre), 4: telefono, 5: contacto, 6: cod_lab(para test de edit)
        */
        return  [
            'Rif inválido' => [
                'holamundo', 'Av.Test calle Test', 'Laboratorio Test'
            ],
            'Dirección inválida' => [
                'J-123123123', '1111111111111', 'Laboratorio Test'
            ],
            'Razón social inválida' => [
                'J-123123123', 'Av.Test calle Test', 'a'
            ],
            'Rif ya registrado' => [
                'J-123123123', 'Av.Test calle Test', 'Laboratorio Test'
            ],
            'Intento inyección SQL' => [
                'J-123123123', 'Av.Test calle Test', "' UNION SELECT @@version --"
            ],
            'Id inválida' => [
                'J-123123123', 'Av.Test calle Test', 'Laboratorio Test', '12312312312'
            ],
        ];
    }

    /**
     * @test
     * @group registro
     * @group crud
     */
    public function registrarLaboratorio()
    {
        $rif = "123123123";
        $res = $this->obj->getRegistrarLaboratorio($this->rif, 'Av. Test Registrar', 'LaboratorioTest');
        if (!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);

        if ($res['resultado'] === "error")
            if ($res['msg'] === "Rif ya registrado")
                $this->fail("Datos del LaboratorioTest ya registrados");
            else
                $this->fail($res['msg']);
        else
            $this->assertEquals('ok', $res['resultado']);
    }

    /**
     * @test 
     * @group consultar
     */
    public function getItemParaEditar(): void
    {
        $res = $this->obj->getItem($this->rif);
        if (!isset($res[0]))
            $this->fail('No existe el laboratorio con el ID indicada.');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test 
     * @dataProvider datosValidacionLaboratorio
     * @group validaciones
     */
    public function validacionesEditarLaboratorio($rif, $direccion, $razon, $id = "")
    {
        $res = $this->obj->getEditar($rif, $direccion, $razon, $id);
        if (!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }


    /**
     * @test
     * @group editar
     * @group crud
     */
    public function editarLaboratorio()
    {
        $res = $this->obj->getEditar('J-999999999', 'Av. TestEditar', 'TestEditar', $this->rif);
        if (!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);
        if ($res['resultado'] === "ok")
            $this->assertEquals('ok', $res['resultado']);
        if ($res['resultado'] === "error")
            $this->fail($res['msg']);
    }

    /**
     * @test
     * @group validaciones 
     */
    public function validacionesEliminarLaboratorio()
    {
        $res = $this->obj->getEliminar("xxxxxxxxx");
        if (!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }

    /**
     * @test
     * @group eliminar
     * @group crud
     */
    public function eliminarLaboratorio()
    {
        $res = $this->obj->getEliminar($this->rif);
        if (!isset($res["resultado"]))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('ok', $res['resultado']);
    }
}
