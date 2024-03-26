<?php 

  namespace config\connect;
  use config\componentes\configSistema as configSistema;
  use \PDO;

  class DBConnect extends configSistema{

    protected $con;
    private $puerto;
    private $usuario;
    private $contra;
    private $local;
    private $nameBD;

    private $modulo;
    private $rol;

    
    public function __construct(){

      $this->usuario = parent::_USER_();
      $this->contra = parent::_PASS_();
      $this->local = parent::_LOCAL_();
      $this->nameBD = parent::_BD_();
      // $this->connectarDB();
    }

    protected function conectarDB(){
      try {
        $this->con = new \PDO("mysql:host={$this->local};dbname={$this->nameBD}", $this->usuario, $this->contra);  
      } catch (\PDOException $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
        
        die();
      }
    }

    // protected function conectarDB(){
      
    // }
    protected function desconectarDB(){
      $this->con = NULL;  
    }

    protected function binnacle($modulo = "", $usuario, $descripcion){
      try {
        $new = $this->con->prepare("INSERT INTO `bitacora`(`id_Bitacora`, `cedula`, `descripcion`, `fecha`, `status`) VALUES (DEFAULT,?,?,DEFAULT,1)");
        $new->bindValue(1, $usuario);
        $new->bindValue(2, $descripcion);
        $new->execute();
      } catch (\PDOException $e) {
        return $e;
      }
    }

    protected function uniqueID(){
      return bin2hex(random_bytes(5));
    }

    protected function uniqueNumericID(){
    $randomNumber = '';
    $length = 8; // Longitud del ID numérico deseado

    for ($i = 0; $i < $length; $i++) {
        $randomNumber .= mt_rand(0, 9); // Genera un dígito aleatorio entre 0 y 9
      }

      return $randomNumber;
    }

    public function getPermisosRol($rol){
      $this->rol = $rol;

      return $this->consultarPermisos();
    }

    private function consultarPermisos(){

      try {
        $this->conectarDB();
        $new = $this->con->prepare('SELECT id_modulo, nombre FROM modulos');
        $new->execute();
        $modulos = $new->fetchAll(\PDO::FETCH_OBJ);
        $permisos = [];
        foreach ($modulos as $modulo) { $permisos[$modulo->nombre] = ''; }

        $query = 'SELECT m.nombre, p.nombre_accion, p.status FROM permisos p
                  INNER JOIN modulos m ON m.id_modulo = p.id_modulo
                  WHERE p.id_rol = ? AND m.nombre = ? AND p.status = 1';

        foreach ($permisos as $nombre_modulo => $valor) {

          $new = $this->con->prepare($query);
          $new->bindValue(1, $this->rol);
          $new->bindValue(2, $nombre_modulo);
          $new->execute();
          $data = $new->fetchAll(\PDO::FETCH_OBJ);
          $acciones = []; 

          foreach($data as $modulo){
            $acciones += [$modulo->nombre_accion => $modulo->status];
          }
          $permisos[$nombre_modulo] = $acciones;
        }
        $this->desconectarDB();

        return $permisos;

      } catch (\PDOException $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
        die();
      }

    }

  }


?>