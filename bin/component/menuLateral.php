<?php 

  namespace component;

  class menuLateral{

    private $permisos;

    public function __construct($permisos){
      $this->permisos = $permisos;
    }

    public function Menu(){

    $home = ($_GET['url'] == 'home')? "": "collapsed";
    $personal = ($_GET['url'] == 'personal')? "": "collapsed";
    $ventas = ($_GET['url'] == 'ventas')? "" : "collapsed";
    $compras = ($_GET['url'] == 'compras')? ""  : "collapsed" ;
    $sedeEnvio = ($_GET['url'] == 'sedeEnvio')? "" : "collapsed";
    $transferencia = ($_GET['url'] == 'transferencia')? "" : "collapsed";
    $recepcion = ($_GET['url'] == 'recepcion')? "" : "collapsed";
    $recepcionNacional = ($_GET['url'] == 'recepcionNacional')? "" : "collapsed";
    $configuracionesA = ($_GET['url'] == 'metodo' || $_GET['url'] == 'moneda')? "" : "collapsed";
    $configuracionesB = ($_GET['url'] == 'metodo' || $_GET['url'] == 'moneda')? "show" : "collapse" ;
    $moneda = ($_GET['url'] == 'moneda')? "active"  : "" ;  
    $metodo = ($_GET['url'] == 'metodo')? "active"  : "" ;
    $productosA = ($_GET['url'] == 'producto' || $_GET['url'] == 'laboratorio' || $_GET['url'] == 'proveedor' || $_GET['url'] == 'presentacion' || $_GET['url'] == 'clase' || $_GET['url'] == 'tipo')?  ""  : "collapsed" ;
    $productosB = ($_GET['url'] == 'producto' || $_GET['url'] == 'laboratorio' || $_GET['url'] == 'proveedor' || $_GET['url'] == 'presentacion' || $_GET['url'] == 'clase' || $_GET['url'] == 'tipo')? "show" : "collapse" ;
    $categoria = ($_GET['url'] == 'clase' || $_GET['url'] == 'url')? "active" : "" ;
    $producto = ($_GET['url'] == 'producto')? "active" : "" ;
    $laboratorio = ($_GET['url'] == 'laboratorio')? "active" :"" ;
    $proveedor = ($_GET['url'] == 'proveedor')? "active" : "" ;
    $presentacion = ($_GET['url'] == 'presentacion')? "active" : "" ;
    $inventario = ($_GET['url'] == 'inventario')? "active" : "" ;
    $clase = ($_GET['url'] == 'clase')? "active" : "" ;
    $tipo = ($_GET['url'] == 'tipo')? "active" : "" ;
    $medida = ($_GET['url'] == 'medida')? "active" : "" ;
    $donativosA = ($_GET['url'] == 'donativoPaciente' || $_GET['url'] == 'donativoPersonal' || $_GET['url'] == 'donativoInstituciones')?  ""  : "collapsed" ;
    $donativosB = ($_GET['url'] == 'donativoPaciente' || $_GET['url'] == 'donativoPersonal' || $_GET['url'] == 'donativoInstituciones')? "show" : "collapse" ;
    $donativoPacientes = ($_GET['url'] == 'donativoPaciente')? "active" : "";
    $donativoPersonal = ($_GET['url'] == 'donativoPersonal')? "active" : "";
    $donativoInstituciones = ($_GET['url'] == 'donativoInstituciones')? "active" : "";
    $categoria = ($_GET['url'] == 'clase' || $_GET['url'] == 'url')? "active" : "" ;
    $reportes = ($_GET['url'] == 'reportes')? "": "collapsed";
    $usuario = ($_GET['url'] == 'usuario')? "": "collapsed";
    $bitacora = ($_GET['url'] == 'bitacora')? "": "collapsed";
    $roles = ($_GET['url'] == 'roles')? "": "collapsed";
    $tipoEmpleado = ($_GET['url'] == 'tipoEmpleado')? "": "collapsed";
    $productoDañado = ($_GET['url'] == 'productoDañado')? "": "collapsed";
    $cargo = ($_GET['url'] == 'cargo')? "": "collapsed";
    $descargo = ($_GET['url'] == 'descargo')? "": "collapsed";

    if(!isset($_SESSION['nivel'])){
      die('<script> window.location = "?url=login" </script>');
    }

    $personalLi = (isset($this->permisos['Clientes']["Consultar"])) ? 
    '<li class="nav-item">
        <a class="nav-link '.$personal.'" href="?url=personal">
            <i class="bi bi-people"></i>
            <span>Personal</span>
        </a>
      </li>' : '';
    $ventasLi = (isset($this->permisos['Ventas']["Consultar"])) ?
                '<li class="nav-item">
                    <a class="nav-link '.$ventas.'" href="?url=ventas">
                        <i class="bi bi-currency-dollar"></i>
                        <span>Ventas</span>
                    </a>
                </li>' : '';
    $comprasLi = (isset($this->permisos['Compras']["Consultar"])) ?
    '<li class="nav-item">
        <a class="nav-link '.$compras.'" href="?url=compras">
            <i class="bi bi-bag-check-fill"></i>
            <span>Compras</span>
        </a>
    </li>' : '';

    $sedeEnvioLi = (isset($this->permisos['Sedes de Envio']["Consultar"])) ?
    '<li class="nav-item"> 
        <a class="nav-link '.$sedeEnvio.'" href="?url=sedeEnvio">
            <i class="bi bi-bank2"></i>
            <span>Sedes</span>
        </a>
    </li>' : '';

    $transferenciaLi = (isset($this->permisos['Sedes de Envio']["Consultar"])) ?
    '<li class="nav-item"> 
        <a class="nav-link '.$transferencia.'" href="?url=transferencia">
            <i class="bi bi-bag-check-fill"></i>
            <span>Transferencia</span>
        </a>
    </li>' : '';

    $recepcionLi = (isset($this->permisos['Sedes de Envio']["Consultar"])) ?
    '<li class="nav-item"> 
        <a class="nav-link '.$recepcion.'" href="?url=recepcion">
            <i class="bi bi-clipboard2-check-fill"></i>
            <span>Recepcion</span>
        </a>
    </li>' : '';

    $recepcionNacionalLi = (isset($this->permisos['Sedes de Envio']["Consultar"])) ?
    '<li class="nav-item"> 
        <a class="nav-link '.$recepcionNacional.'" href="?url=recepcionNacional">
            <i class="bi bi-box2-fill"></i>
            <span>Recepcion Nacional</span>
        </a>
    </li>' : '';
    
    $transferenciaLi = (isset($this->permisos['Sedes de Envio']["Consultar"])) ?
    '<li class="nav-item"> 
        <a class="nav-link '.$transferencia.'" href="?url=transferencia">
            <i class="bx bx-transfer"></i>
            <span>Transferencia</span>
        </a>
    </li>' : '';


    $metodoLi = (isset($this->permisos['Metodo pago']["Consultar"])) ?
    '<li>
        <a href="?url=metodo" class="'.$metodo.'" >
          <i class="bi bi-circle-fill "></i><span>Metodo de Pago</span>
        </a>
    </li>' : '';

    $monedaLi = (isset($this->permisos['Moneda']["Consultar"])) ?
    '<li>
        <a href="?url=moneda" class="'.$moneda.'">
          <i class="bi bi-circle-fill "></i><span>Moneda</span>
        </a>
    </li> ' : '';



    $configuracionesLi = (isset($this->permisos['Metodo pago']["Consultar"]) || isset($this->permisos['Moneda']["Consultar"]) ) ?
    '<li class="nav-item">
        <a class="nav-link '.$configuracionesA.'" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
          <i class="bi bi-gear-fill"></i><span>Configuraciones</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content '.$configuracionesB.'" data-bs-parent="#sidebar-nav" style="">

            '.$metodoLi.'

            '.$monedaLi.'


        </ul>
    </li>' : '';
    $productosLi = (isset($this->permisos['Producto']["Consultar"])) ?
    '<li>
        <a href="?url=producto" class="'.$producto.'">
          <i class="bi bi-circle-fill"></i><span>Producto</span>
        </a>
    </li>' : '';
    $laboratorioLi = (isset($this->permisos['Laboratorio']["Consultar"])) ?
    '<li>
        <a href="?url=laboratorio" class="'.$laboratorio.'">
          <i class="bi bi-circle-fill"></i><span>Laboratorio</span>
        </a>
    </li>' : '';
    $proveedorLi = (isset($this->permisos['Proveedor']["Consultar"])) ?
    '<li>
        <a href="?url=proveedor" class="'.$proveedor.'">
          <i class="bi bi-circle-fill"></i><span>Proveedor</span>
        </a>
    </li>' : '';

    $claseLi = (isset($this->permisos['Clase']["Consultar"])) ?
    '<li>
      <a href="?url=clase" class="'.$clase.'">
        <i class="bi bi-circle-fill"></i><span>Clase</span>
      </a>
    </li>' : '';
    $tipoLi = (isset($this->permisos['Tipo']["Consultar"])) ?
    '<li>
      <a href="?url=tipo" class="'.$tipo.'">
        <i class="bi bi-circle-fill"></i><span>Tipo</span>
      </a>
    </li>' : '';
    $medidaLi = (isset($this->permisos['Tipo']["Consultar"])) ?
    '<li>
      <a href="?url=medida" class="'.$medida.'">
        <i class="bi bi-circle-fill"></i><span>Medida</span>
      </a>
    </li>' : '';
    $categoriaLi = (isset($this->permisos['Clase']["Consultar"]) || isset($this->permisos['Tipo'])) ?
    '<li>
        <a href="#" class="'.$categoria.'">
          <i class="bi bi-circle-fill"></i><span>Categoría</span>
        </a>
        <ul>
          '.$claseLi.'

          '.$tipoLi.'

          '.$medidaLi.'

        </ul>
    </li>' : '';

    $presentacionLi = (isset($this->permisos['Presentacion']["Consultar"])) ?
    '<li>
        <a href="?url=presentacion" class="'.$presentacion.'">
          <i class="bi bi-circle-fill"></i><span>Presentación</span>
        </a>
    </li>' : '';

    $inventarioLi = (isset($this->permisos['Presentacion']["Consultar"])) ?
    '<li>
        <a href="?url=inventario" class="'.$inventario.'">
          <i class="bi bi-circle-fill"></i><span>Inventario</span>
        </a>
    </li>' : '';

    $productosNavLi = (isset($this->permisos['Producto']["Consultar"]) || isset($this->permisos['Laboratorio']["Consultar"]) || isset($this->permisos['Proveedor']["Consultar"]) || isset($this->permisos['Clase']["Consultar"]) || isset($this->permisos['Tipo']["Consultar"]) || isset($this->permisos['Presentacion']["Consultar"])) ?
    '<li class="nav-item">
          <a class="nav-link '.$productosA.'" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
              <i class="bi bi-boxes"></i><span>Productos</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content '.$productosB.'" data-bs-parent="#sidebar-nav">
              
              '.$productosLi.'

              '.$laboratorioLi.'
              
              '.$proveedorLi.'

              '.$categoriaLi.'

              '.$presentacionLi.'

              '.$inventarioLi.'

          </ul>
    </li>' : '';

     $donativosPacienteLi = (isset($this->permisos['Ventas']["Consultar"])) ?
       '<li>
        <a href="?url=donativoPaciente" class="'.$donativoPacientes.'">
          <i class="ri-heart-add-fill fs-5"></i><span>Donativos Pacientes</span>
        </a>
     </li>' : '';
     $donativosPersonalLi = (isset($this->permisos['Ventas']["Consultar"])) ?
      '<li>
        <a href="?url=donativoPersonal" class="'.$donativoPersonal.'">
          <i class="ri-heart-add-fill fs-5"></i><span>Donativos Personal</span>
        </a>
    </li>' : '';

      $donativosInstitucionesLi = (isset($this->permisos['Ventas']["Consultar"])) ?
      '<li>
        <a href="?url=donativoInstituciones" class="'.$donativoInstituciones.'">
          <i class="ri-heart-add-fill fs-5"></i><span>Donativo Instituciones</span>
        </a>
    </li>' : '';

          $DonacionesNavLi = (isset($this->permisos['Ventas']["Consultar"])) ?
    '<li class="nav-item">
          <a class="nav-link '.$donativosA.'" data-bs-target="#consul-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
              <i class="ri-hand-heart-line"></i><span>Donaciones</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="consul-nav" class="nav-content '.$donativosB.'" data-bs-parent="#sidebar-nav">
              
              '.$donativosPacienteLi.'

              '.$donativosPersonalLi.'
              
              '.$donativosInstitucionesLi.'

          </ul>
    </li>' : '';

    $reportesLi = (isset($this->permisos['Reportes']["Consultar"])) ?
    '<li class="nav-item ">
        <a class="nav-link '.$reportes.'" href="?url=reportes">
          <i class="bi bi-clipboard2-data-fill"></i><span>Reportes</span>
        </a>
    </li>' : '';

    $usuarioLi = (isset($this->permisos['Usuarios']["Consultar"])) ?
    '<li class="nav-item">
        <a class="nav-link '.$usuario.'" href="?url=usuario">
          <i class="bi bi-people-fill"></i><span>Usuarios</span>
        </a>
    </li>' : '';

    $bitacoraLi = (isset($this->permisos['Bitacora']["Consultar"])) ?
    '<li class="nav-item ">
        <a class="nav-link '.$bitacora.'" href="?url=bitacora">
          <i class="bi bi-journals"></i><span>Bitacora</span>
        </a>
    </li>' : '';

    $rolesLi = (isset($this->permisos['Roles']["Consultar"])) ?
    '<li class="nav-item ">
        <a class="nav-link '.$roles.'" href="?url=roles">
          <i class="bi bi-person-lines-fill"></i><span>Roles</span>
        </a>
    </li>' : '';

    $tipoEmpleadoLi = (isset($this->permisos['Usuarios']["Consultar"])) ?
    '<li class="nav-item">
        <a class="nav-link '.$tipoEmpleado.'" href="?url=tipoEmpleado">
          <i class="ri-user-2-fill"></i><span>Tipo Empleado</span>
        </a>
    </li>' : '';

    $productoDañadoLi = (isset($this->permisos['Usuarios']["Consultar"])) ?
    '<li class="nav-item">
        <a class="nav-link '.$productoDañado.'" href="?url=productoDañado">
          <i class="bi bi-capsule-pill"></i><span>Producto Dañado</span>
        </a>
    </li>' : '';

    $cargoLi = (isset($this->permisos['Usuarios']["Consultar"])) ?
    '<li class="nav-item">
        <a class="nav-link '.$cargo.'" href="?url=cargo">
          <i class="bi bi-cart-fill"></i><span>Cargo</span>
        </a>
    </li>' : '';

    $descargoLi = (isset($this->permisos['Usuarios']["Consultar"])) ?
    '<li class="nav-item">
        <a class="nav-link '.$descargo.'" href="?url=descargo">
          <i class="bi bi-cart"></i><span>Descargo</span>
        </a>
    </li>' : '';

    $menu = '
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">

          <li class="nav-item">
              <a class="nav-link '.$home.'" href="?url=home">
                <i class="bi bi-house-door-fill"></i>
                <span>Inicio</span>
              </a>
          </li>
              
          '.$personalLi.'

          '.$ventasLi.'
          
          '.$comprasLi.'

          <!-- Configuraciones desplegable -->

          '.$configuracionesLi.'

          <!-- Final de Configuraciones desplegable -->

          <!-- Donaciones desplegable -->

          '.$DonacionesNavLi.'

           <!-- Final de Donaciones desplegable -->

           '.$sedeEnvioLi.'

           '.$transferenciaLi.'

           '.$recepcionLi.'

           '.$recepcionNacionalLi.'

           <!-- Productos desplegable -->

          '.$productosNavLi.'

          <!-- Final de Productos desplegable -->

          '.$productoDañadoLi.'

          '.$reportesLi.'

          '.$usuarioLi.'

          '.$bitacoraLi.'

          '.$rolesLi.'

          '.$tipoEmpleadoLi.'

          '.$cargoLi.'

          '.$descargoLi.'

        </ul>
    </aside>

    ';

    echo $menu;


    }

  }

  
?>
