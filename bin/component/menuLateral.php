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
    $sede = ($_GET['url'] == 'sede')? "" : "collapsed";
    $transferencia = ($_GET['url'] == 'transferencia')? "" : "collapsed";
    $recepcion = ($_GET['url'] == 'recepcion')? "" : "collapsed";
    $recepcionNacional = ($_GET['url'] == 'recepcionNacional')? "" : "collapsed";
    $configuracionesA = ($_GET['url'] == 'metodo' || $_GET['url'] == 'moneda')? "" : "collapsed";
    $configuracionesB = ($_GET['url'] == 'metodo' || $_GET['url'] == 'moneda')? "show" : "collapse" ;
    $sistemaA = ($_GET['url'] == 'roles' || $_GET['url'] == 'bitacora' || $_GET['url'] == 'usuarios' || $_GET['url'] == 'mantenimiento')? "" : "collapsed";
    $sistemaB = ($_GET['url'] == 'roles' || $_GET['url'] == 'bitacora' || $_GET['url'] == 'usuarios' || $_GET['url'] == 'mantenimiento')? "show" : "collapse" ;
    $moneda = ($_GET['url'] == 'moneda')? "active"  : "" ;  
    $metodo = ($_GET['url'] == 'metodo')? "active"  : "" ;
    $productosA = ($_GET['url'] == 'productoDañado' ||$_GET['url'] == 'producto' || $_GET['url'] == 'laboratorio' || $_GET['url'] == 'proveedor' || $_GET['url'] == 'presentacion' || $_GET['url'] == 'clase' || $_GET['url'] == 'tipo')?  ""  : "collapsed" ;
    $productosB = ($_GET['url'] == 'productoDañado' || $_GET['url'] == 'producto' || $_GET['url'] == 'laboratorio' || $_GET['url'] == 'proveedor' || $_GET['url'] == 'presentacion' || $_GET['url'] == 'clase' || $_GET['url'] == 'tipo')? "show" : "collapse" ;
    $categoria = ($_GET['url'] == 'clase' || $_GET['url'] == 'url')? "active" : "" ;
    $producto = ($_GET['url'] == 'producto')? "active" : "" ;
    $laboratorio = ($_GET['url'] == 'laboratorio')? "active" :"" ;
    $proveedor = ($_GET['url'] == 'proveedor')? "active" : "" ;
    $presentacion = ($_GET['url'] == 'presentacion')? "active" : "" ;
    $inventario = ($_GET['url'] == 'inventario')? "active" : "" ;
    $productoDañado = ($_GET['url'] == 'productoDañado')? "active" : "" ;
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
    $usuario = ($_GET['url'] == 'usuario')? "active": "";
    $bitacora = ($_GET['url'] == 'bitacora')? "active": "";
    $roles = ($_GET['url'] == 'roles')? "active": "";
    $tipoEmpleado = ($_GET['url'] == 'tipoEmpleado')? "": "collapsed";
    $cargo = ($_GET['url'] == 'cargo')? "": "collapsed";
    $descargo = ($_GET['url'] == 'descargo')? "": "collapsed";

    if(!isset($_SESSION['nivel'])){
      die('<script> window.location = "?url=login" </script>');
    }

    $personalLi = (isset($this->permisos['Personal']["Consultar"])) ? 
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

    $sedeLi = (isset($this->permisos['Sedes']["Consultar"])) ?
    '<li class="nav-item"> 
        <a class="nav-link '.$sede.'" href="?url=sede">
            <i class="bi bi-hospital"></i>
            <span>Sedes</span>
        </a>
    </li>' : '';

    $transferenciaLi = (isset($this->permisos['Transferencia']["Consultar"])) ?
    '<li class="nav-item"> 
        <a class="nav-link '.$transferencia.'" href="?url=transferencia">
            <i class="bi bi-truck"></i>
            <span>Transferencia</span>
        </a>
    </li>' : '';

    $recepcionLi = (isset($this->permisos['Recepcion']["Consultar"])) ?
    '<li class="nav-item"> 
        <a class="nav-link '.$recepcion.'" href="?url=recepcion">
            <i class="bi bi-clipboard2-check-fill"></i>
            <span>Recepcion</span>
        </a>
    </li>' : '';

    $recepcionNacionalLi = (isset($this->permisos['Recepcion nacional']["Consultar"])) ?
    '<li class="nav-item"> 
        <a class="nav-link '.$recepcionNacional.'" href="?url=recepcionNacional">
            <i class="bi bi-box2-fill"></i>
            <span>Recepcion Nacional</span>
        </a>
    </li>' : '';


    
    $usuarioLi = (isset($this->permisos['Usuarios']["Consultar"])) ?
    '<li >
        <a class="'.$usuario.'" href="?url=usuario">
          <i class="bi bi-people-fill"></i><span>Usuarios</span>
        </a>
    </li>' : '';


    $bitacoraLi = (isset($this->permisos['Bitacora']["Consultar"])) ?
    '<li>
        <a class="'.$bitacora.'" href="?url=bitacora">
          <i class="bi bi-journals"></i><span>Bitacora</span>
        </a>
    </li>' : '';

    $rolesLi = (isset($this->permisos['Roles']["Consultar"])) ?
    '<li>
        <a class="'.$roles.'" href="?url=roles">
          <i class="bi bi-person-lines-fill"></i><span>Roles</span>
        </a>
    </li>' : '';

    $sistemaLi = (isset($this->permisos['Roles']["Consultar"]) || isset($this->permisos['Usuarios']["consultar"]) || isset($this->permisos['Bitacora']["consultar"]) ) ?
    '<li class="nav-item">
        <a class="nav-link '.$sistemaA.'" data-bs-target="#sistema-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
          <i class="bi bi-shield-fill"></i><span>Sistema</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="sistema-nav" class="nav-content '.$sistemaB.'" data-bs-parent="#sidebar-nav" style="">

            '.$rolesLi.'

            '.$bitacoraLi.'

            '.$usuarioLi.'

        </ul>
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

    $configuracionesLi = (isset($this->permisos['Metodo pago']["Consultar"]) || isset($this->permisos['Moneda']["Consultar"]) ) ?
    '<li class="nav-item">
        <a class="nav-link '.$configuracionesA.'" data-bs-target="#configuraciones-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
          <i class="bi bi-gear-fill"></i><span>Configuraciones</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="configuraciones-nav" class="nav-content '.$configuracionesB.'" data-bs-parent="#sidebar-nav" style="">

            '.$metodoLi.'

            '.$monedaLi.'


        </ul>
    </li>' : '';
    $productosLi = (isset($this->permisos['Producto']["Consultar"])) ?
    '<li>
        <a href="?url=producto" class="'.$producto.'">
          <i class="bi bi-capsule"></i><span>Producto</span>
        </a>
    </li>' : '';
    $laboratorioLi = (isset($this->permisos['Laboratorio']["Consultar"])) ?
    '<li>
        <a href="?url=laboratorio" class="'.$laboratorio.'">
          <i class="bi bi-prescription2"></i><span>Laboratorio</span>
        </a>
    </li>' : '';
    $proveedorLi = (isset($this->permisos['Proveedor']["Consultar"])) ?
    '<li>
        <a href="?url=proveedor" class="'.$proveedor.'">
          <i class="bi bi-box-seam-fill"></i><span>Proveedor</span>
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
    $medidaLi = (isset($this->permisos['Medida']["Consultar"])) ?
    '<li>
      <a href="?url=medida" class="'.$medida.'">
        <i class="bi bi-circle-fill"></i><span>Medida</span>
      </a>
    </li>' : '';
    $presentacionLi = (isset($this->permisos['Presentacion']["Consultar"])) ?
    '<li>
      <a href="?url=medida" class="'.$presentacion.'">
        <i class="bi bi-circle-fill"></i><span>Presentación</span>
      </a>
    </li>' : '';
    $categoriaLi = (isset($this->permisos['Clase']["Consultar"]) || isset($this->permisos['Medida']["Consultar"]) || isset($this->permisos['Tipo']["Consultar"])) ?
    '<li>
        <a href="#" class="'.$categoria.'">
          <i class="bi bi-tags"></i><span>Categoría</span>
        </a>
        <ul>
          '.$claseLi.'

          '.$tipoLi.'

          '.$medidaLi.'

          '.$presentacionLi.'

        </ul>
    </li>' : '';

    $inventarioLi = (isset($this->permisos['Inventario']["Consultar"])) ?
    '<li>
        <a href="?url=inventario" class="'.$inventario.'">
          <i class="bi bi-box-fill"></i><span>Inventario</span>
        </a>
    </li>' : '';

    $productoDañadoLi = (isset($this->permisos['Producto dañado']["Consultar"])) ?
    '<li>
        <a href="?url=inventario" class="'.$productoDañado.'">
          <i class="bi bi-capsule-pill"></i><span>Producto dañado</span>
        </a>
    </li>' : '';

   $productosNavLi = (isset($this->permisos['Producto']["Consultar"]) || isset($this->permisos['Laboratorio']["Consultar"]) || isset($this->permisos['Proveedor']["Consultar"]) || isset($this->permisos['Clase']["Consultar"]) || isset($this->permisos['Tipo']["Consultar"]) || isset($this->permisos['Presentacion']["Consultar"]) || isset($this->permisos['Producto dañado']["Consultar"])) ?
    '<li class="nav-item">
          <a class="nav-link '.$productosA.'" data-bs-target="#productos-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
              <i class="bi bi-boxes"></i><span>Productos</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="productos-nav" class="nav-content '.$productosB.'" data-bs-parent="#sidebar-nav">
              
              '.$productosLi.'

              '.$laboratorioLi.'
              
              '.$proveedorLi.'

              '.$categoriaLi.'

              '.$inventarioLi.'

              '.$productoDañadoLi.'

          </ul>
    </li>' : '';

     $donativosPacienteLi = (isset($this->permisos['Donativos pacientes']["Consultar"])) ?
       '<li>
        <a href="?url=donativoPaciente" class="'.$donativoPacientes.'">
          <i class="ri-heart-add-fill"></i><span>Donativos Pacientes</span>
        </a>
     </li>' : '';
     $donativosPersonalLi = (isset($this->permisos['Donativos personal']["Consultar"])) ?
      '<li>
        <a href="?url=donativoPersonal" class="'.$donativoPersonal.'">
          <i class="ri-heart-add-fill"></i><span>Donativos Personal</span>
        </a>
    </li>' : '';

      $donativosInstitucionesLi = (isset($this->permisos['Donativos instituciones']["Consultar"])) ?
      '<li>
        <a href="?url=donativoInstituciones" class="'.$donativoInstituciones.'">
          <i class="ri-heart-add-fill"></i><span>Donativo Instituciones</span>
        </a>
    </li>' : '';

    $donacionesNavLi = (isset($this->permisos['Ventas']["Consultar"])) ?
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

    

    $tipoEmpleadoLi = (isset($this->permisos['Tipo empleado']["Consultar"])) ?
    '<li class="nav-item">
        <a class="nav-link '.$tipoEmpleado.'" href="?url=tipoEmpleado">
          <i class="ri-user-2-fill"></i><span>Tipo Empleado</span>
        </a>
    </li>' : '';

    $cargoLi = (isset($this->permisos['Cargo']["Consultar"])) ?
    '<li class="nav-item">
        <a class="nav-link '.$cargo.'" href="?url=cargo">
          <i class="bi bi-cart-fill"></i><span>Cargo</span>
        </a>
    </li>' : '';

    $descargoLi = (isset($this->permisos['Descargo']["Consultar"])) ?
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

          '.$donacionesNavLi.'

           <!-- Final de Donaciones desplegable -->

           '.$sedeLi.'

           '.$transferenciaLi.'

           '.$recepcionLi.'

           '.$recepcionNacionalLi.'

           <!-- Productos desplegable -->

          '.$productosNavLi.'

          <!-- Final de Productos desplegable -->

          '.$sistemaLi.'

          '.$reportesLi.'

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