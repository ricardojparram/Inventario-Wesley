<?php  

use PHPUnit\Framework\TestCase;
use modelo\roles;

/**
 * @group roles
*/
class rolesTest extends TestCase{

    private $obj;
    public function setUp():void{
        $this->obj = new roles();
        $_SESSION['cedula'] = 'testing';
    }   

	/** 
     * @test 
     * @group consultar
     * @group crud
    */
    public function mostrarRoles(): void{
        $res = $this->obj->mostrarRoles(false);
        if(!isset($res[0]))
            $this->fail('No existen roles en la base de datos.');
        else
            $this->assertArrayHasKey('0', $res);
    }

    /** 
     * @test
     * @dataProvider datosValidacionPermisos
     * @group validaciones
    */
    public function mostrarPermisos($id_rol): void{
        $res = $this->obj->getPermisos($id_rol);        
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }

    public function  datosValidacionPermisos(): array {
        /* Descripcion  de los datasets:
            1: id_rol, 2: permisos
        */
        return  [
            'Id rol inválido (string)' => [
                'sdgdg'
            ],
            'Id rol inválida (hexadecimal)' => [
                'af12afafaf'
            ],
        ];
    }

    /** 
     * @test
     * @dataProvider datosValidacionActualizarPermisos
     * @group validaciones
    */
    public function validacionActualizarPermisos($id, $datos): void{
        $res = $this->obj->getDatosPermisos($datos, $id);        
        if(!isset($res['resultado']))
            $this->assertArrayHasKey('resultado',  $res);

        $this->assertEquals('error', $res['resultado']);
    }


    public function datosValidacionActualizarPermisos(): array {
        /* Descripcion  de los datasets:
            1: id_rol, 2: permisos
        */
        return  [
            'Id rol inválido (string)' => [
                'sdgdg', [0 => ['id_permiso' => "1", "status" => "1"]]
            ],
            'Permisos inválidos' => [
                '1', 'asdgadsg'
            ],
        ];
    }

    /** 
     * @test
     * @group editar
     * @group crud
    */
    public function actualizarPermisos(): void{
        $res = $this->obj->getDatosPermisos([0 => ['id_permiso' => "1", "status" => "1"]], "1");  
        if(!isset($res['respuesta']))
            $this->assertArrayHasKey('respuesta',  $res);

        $this->assertEquals('ok', $res['respuesta']);
    }
    
}

?>
