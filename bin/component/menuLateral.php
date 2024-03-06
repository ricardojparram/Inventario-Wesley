<?php 

  namespace component;

  class menuLateral{

    private $permisos;

    public function __construct($permisos){
      $this->permisos = $permisos;
    }

    public function Menu(){

    $home = ($_GET['url'] == 'home')? "": "collapsed";
    $clientes = ($_GET['url'] == 'clientes')? "": "collapsed";
    $ventas = ($_GET['url'] == 'ventas')? "" : "collapsed";
    $compras = ($_GET['url'] == 'compras')? ""  : "collapsed" ;
    $configuracionesA = ($_GET['url'] == 'metodo' || $_GET['url'] == 'moneda' || $_GET['url'] == 'banco' || $_GET['url'] == 'empresaEnvio'|| $_GET['url'] == "sedeEnvio" || $_GET['url'] == "envios" || $_GET['url'] == "comprobarPago")? "" : "collapsed";
    $configuracionesB = ($_GET['url'] == 'metodo' || $_GET['url'] == 'moneda' || $_GET['url'] == 'banco' || $_GET['url'] == 'empresaEnvio'|| $_GET['url'] == "sedeEnvio" || $_GET['url'] == "envios" || $_GET['url'] == "comprobarPago")? "show" : "collapse" ;
    $moneda = ($_GET['url'] == 'moneda')? "active"  : "" ;
    $banco = ($_GET['url'] == 'banco')? "active" : "" ;
    $empresaEnvio = ($_GET['url'] == 'empresaEnvio')? "active"  : "" ;
    $sedeEnvio = ($_GET['url'] == 'sedeEnvio')? "active"  : "" ;
    $comprobarPago = ($_GET['url'] == 'comprobarPago')? "active"  : "" ;
    $envios = ($_GET['url'] == 'envios')? "active"  : "" ;
    $metodo = ($_GET['url'] == 'metodo')? "active"  : "" ;
    $productosA = ($_GET['url'] == 'producto' || $_GET['url'] == 'laboratorio' || $_GET['url'] == 'proveedor' || $_GET['url'] == 'presentacion' || $_GET['url'] == 'clase' || $_GET['url'] == 'tipo')?  ""  : "collapsed" ;
    $productosB = ($_GET['url'] == 'producto' || $_GET['url'] == 'laboratorio' || $_GET['url'] == 'proveedor' || $_GET['url'] == 'presentacion' || $_GET['url'] == 'clase' || $_GET['url'] == 'tipo')? "show" : "collapse" ;
    $categoria = ($_GET['url'] == 'clase' || $_GET['url'] == 'url')? "active" : "" ;
    $producto = ($_GET['url'] == 'producto')? "active" : "" ;
    $laboratorio = ($_GET['url'] == 'laboratorio')? "active" :"" ;
    $proveedor = ($_GET['url'] == 'proveedor')? "active" : "" ;
    $presentacion = ($_GET['url'] == 'presentacion')? "active" : "" ;
    $clase = ($_GET['url'] == 'clase')? "active" : "" ;
    $tipo = ($_GET['url'] == 'tipo')? "active" : "" ;
    $reportes = ($_GET['url'] == 'reportes')? "": "collapsed";
    $usuario = ($_GET['url'] == 'usuario')? "": "collapsed";
    $bitacora = ($_GET['url'] == 'bitacora')? "": "collapsed";
    $roles = ($_GET['url'] == 'roles')? "": "collapsed";

    if(!isset($_SESSION['nivel'])){
      die('<script> window.location = "?url=login" </script>');
    }

    $clientesLi = (isset($this->permisos['Clientes']["Consultar"])) ? 
    '<li class="nav-item">
        <a class="nav-link '.$clientes.'" href="?url=clientes">
            <i class="bi bi-people"></i>
            <span>Clientes</span>
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

    $metodoLi = (isset($this->permisos['Metodo pago']["Consultar"])) ?
    '<li>
        <a href="?url=metodo" class="'.$metodo.'" >
          <i class="bi bi-circle-fill "></i><span>Metodo de pago</span>
        </a>
    </li>' : '';
    $monedaLi = (isset($this->permisos['Moneda']["Consultar"])) ?
    '<li>
        <a href="?url=moneda" class="'.$moneda.'">
          <i class="bi bi-circle-fill "></i><span>Moneda</span>
        </a>
    </li> ' : '';

    $bancoLi = (isset($this->permisos['Bancos']["Consultar"])) ?
    '<li>
        <a href="?url=banco" class="'.$banco.'">
          <i class="bi bi-circle-fill "></i><span>Bancos</span>
        </a>
    </li>' : '';

    $empresaEnvioLi = (isset($this->permisos['Empresa de Envio']["Consultar"])) ?
    '<li>
        <a href="?url=empresaEnvio" class="'.$empresaEnvio.'">
          <i class="bi bi-circle-fill "></i><span>Empresa envio</span>
        </a>
    </li>' : '';
    $sedeEnvioLi = (isset($this->permisos['Sedes de Envio']["Consultar"])) ?
    '<li>
        <a href="?url=sedeEnvio" class="'.$sedeEnvio.'">
          <i class="bi bi-circle-fill "></i><span>Sedes de envío</span>
        </a>
    </li>' : '';
    $comprobarPagoLi = (isset($this->permisos['Comprobar pago']["Consultar"])) ?
    '<li>
        <a href="?url=comprobarPago" class="'.$comprobarPago.'">
          <i class="bi bi-circle-fill "></i><span>Comprobar pago</span>
        </a>
    </li>' : '';
    $enviosLi = (isset($this->permisos['Envios']["Consultar"])) ?
    '<li>
        <a href="?url=envios" class="'.$envios.'">
          <i class="bi bi-circle-fill "></i><span>Envios</span>
        </a>
    </li>' : '';
    


    $configuracionesLi = (isset($this->permisos['Metodo pago']["Consultar"]) || isset($this->permisos['Moneda']["Consultar"]) || isset($this->permisos['Bancos']["Consultar"]) || isset($this->permisos['Empresa de Envio']['Consultar']) || isset($this->permisos['Sedes de Envio']['Consultar']) || isset($this->permisos['Comprobar pago']['Consultar']) || isset($this->permisos['Envios']['Consultar'])) ?
    '<li class="nav-item">
        <a class="nav-link '.$configuracionesA.'" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
          <i class="bi bi-gear-fill"></i><span>Configuraciones</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content '.$configuracionesB.'" data-bs-parent="#sidebar-nav" style="">

            '.$metodoLi.'

            '.$monedaLi.'

            '.$bancoLi.'

            '.$empresaEnvioLi.'

            '.$sedeEnvioLi.'

            '.$comprobarPagoLi.'

            '.$enviosLi.'

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
    $categoriaLi = (isset($this->permisos['Clase']["Consultar"]) || isset($this->permisos['Tipo'])) ?
    '<li>
        <a href="#" class="'.$categoria.'">
          <i class="bi bi-circle-fill"></i><span>Categoría</span>
        </a>
        <ul>
          '.$claseLi.'

          '.$tipoLi.'
        </ul>
    </li>' : '';

    $presentacionLi = (isset($this->permisos['Presentacion']["Consultar"])) ?
    '<li>
        <a href="?url=presentacion" class="'.$presentacion.'">
          <i class="bi bi-circle-fill"></i><span>Presentación</span>
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

    $menu = '
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">

          <li class="nav-item">
              <a class="nav-link '.$home.'" href="?url=home">
                <i class="bi bi-house-door-fill"></i>
                <span>Inicio</span>
              </a>
          </li>
              
          '.$clientesLi.'

          '.$ventasLi.'
          
          '.$comprasLi.'

          <!-- Configuraciones desplegable -->

          '.$configuracionesLi.'

          <!-- Final de Configuraciones desplegable -->

          <!-- Productos desplegable -->

          '.$productosNavLi.'

          <!-- Final de Productos desplegable -->

          '.$reportesLi.'

          '.$usuarioLi.'

          '.$bitacoraLi.'

          '.$rolesLi.'

        </ul>
    </aside>

    ';

    echo $menu;


    }

  }

  
?>
