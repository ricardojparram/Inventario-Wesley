<?php 

namespace modelo;
Use config\connect\DBConnect as DBConnect;

class perfil extends DBConnect{

	private $cedulaVieja;
	private $cedulaNueva;
	private $nombre;
	private $apellido;
	private $correo;
	private $foto;
	private $borrar;
	private $imagenPorDefecto = 'assets/img/profile_photo.jpg';


	private $passwordAct;
	private $passwordNew;

	public function __construct(){
        parent::__construct();
    }

    public function mostrarDatos($cedula){
    	
    	$this->cedula = $cedula;

    	$this->datos();
    }

    private function datos(){
    	try {
			parent::conectarDB();
			$this->key = parent::KEY();
			$this->iv = parent::IV();
			$this->cipher = parent::CIPHER();
    		$new = $this->con->prepare("SELECT u.cedula, u.nombre, u.apellido, u.correo, n.nombre as nivel FROM usuario u INNER JOIN rol n ON n.id_rol = u.rol WHERE u.cedula = ?");
            $new->bindValue(1, $this->cedula);
            $new->execute();
            $data = $new->fetchAll(\PDO::FETCH_OBJ);

			$data[0]->cedula = openssl_decrypt($data[0]->cedula, $this->cipher, $this->key, 0, $this->iv);
			$data[0]->correo = openssl_decrypt($data[0]->correo, $this->cipher, $this->key, 0, $this->iv);
            echo json_encode($data);
			parent::desconectarDB();
            die();
    	} catch(\PDOexection $error){
            return $error;
        }
    }

    public function mostrarUsuarios(){
    	try {
    		parent::conectarDB();
    		$new = $this->con->prepare("SELECT img, CONCAT(nombre, ' ', apellido) AS nombre FROM usuario
    			WHERE status = 1
    			ORDER BY RAND() LIMIT 4");
    		$new->execute();
    		$data = $new->fetchAll(\PDO::FETCH_OBJ);
			parent::desconectarDB();
    		echo json_encode($data);
    		die();

    	} catch (\PDOException $e) {
    		return $e;
    	}
    }

    public function getValidarContraseña($pass, $cedula){
    	if(preg_match_all("/^[A-Za-z0-9 *?=&_!¡()@#]{8,30}$/", $pass) == false) {
    		$resultado = ['resultado' => 'Error de contraseña' , 'error' => 'Correo inválida.'];
    		echo json_encode($resultado);
    		die();
    	}
    	if(preg_match_all("/^[0-9]{7,10}$/", $cedula) == false){
	      $resultado = ['resultado' => 'Error de cedula' , 'error' => 'Cédula invalida.'];
	      echo json_encode($resultado);
	      die();
	    }

	    $this->cedulaVieja = $cedula;
    	$this->passwordAct = $pass;

    	$this->validarContraseña();

    }

    private function validarContraseña(){
    	try {
			parent::conectarDB();
    		$new = $this->con->prepare("SELECT password FROM usuario WHERE cedula = ? AND status = 1");
    		$new->bindValue(1, $this->cedulaVieja);
    		$new->execute();
    		$data = $new->fetchAll(\PDO::FETCH_OBJ);
			parent::desconectarDB();
    		if(password_verify($this->passwordAct, $data[0]->password)){
    			die(json_encode(['resultado' => 'Contraseña válida.']));
    		}else{
    			die(json_encode(['resultado' => 'Error de contraseña', 'error' => 'Contraseña incorrecta.']));
    		}

    	} catch (\PDOException $e){
    		return $e;
    	}

    }

    public function getEditar($foto, $nombre, $apellido, $cedulaNueva, $correo, $cedulaVieja, $borrar = false){

	    if(preg_match_all("/^[a-zA-ZÀ-ÿ]{3,30}$/", $nombre) == false){
	      $resultado = ['resultado' => 'Error de nombre' , 'error' => 'Nombre invalido.'];
	      echo json_encode($resultado);
	      die();
	    }
	    if(preg_match_all("/^[a-zA-ZÀ-ÿ]{3,30}$/", $apellido) == false){
	      $resultado = ['resultado' => 'Error de apellido' , 'error' => 'Apellido invalido.'];
	      echo json_encode($resultado);
	      die();
	    }
	    if(preg_match_all("/^[0-9]{7,10}$/", $cedulaNueva) == false){
	      $resultado = ['resultado' => 'Error de cedula' , 'error' => 'Cédula invalida.'];
	      echo json_encode($resultado);
	      die();
	    }
	    if(preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $correo) == false){
	      $resultado = ['resultado' => 'Error de email' , 'error' => 'Correo invalida.'];
	      echo json_encode($resultado);
	      die();
	    }

	    $this->foto = $foto;
	    $this->nombre = $nombre;
	    $this->apellido = $apellido;
	    $this->cedulaNueva = $cedulaNueva;
	    $this->correo = $correo;
	    $this->cedulaVieja = $cedulaVieja;
	    $this->borrar = $borrar;

        $this->key = parent::KEY();
        $this->iv = parent::IV();
        $this->cipher = parent::CIPHER();
		
		$this->cedulaNueva = openssl_encrypt($this->cedulaNueva, $this->cipher, $this->key, 0, $this->iv);
		$this->correo = openssl_encrypt($this->correo, $this->cipher, $this->key, 0, $this->iv);

	    $resultadoEdit = $this->editarDatos();

	    if($this->borrar != false){
	    	$resultadoFoto = $this->borrarImagen();
	    }
	    if(isset($this->foto['name'])){
	    	$resultadoFoto = $this->subirImagen();
	    }

	    echo json_encode(['edit' => $resultadoEdit, 'foto' => $resultadoFoto]);
	    die();
	}

	private function editarDatos(){
		try {
			parent::conectarDB();
			$new = $this->con->prepare("UPDATE `usuario` SET `cedula`= ?,`nombre`= ?,`apellido`= ?,`correo`= ? WHERE cedula = ?");
            $new->bindValue(1, $this->cedulaNueva);
            $new->bindValue(2, $this->nombre);
            $new->bindValue(3,$this->apellido);
            $new->bindValue(4, $this->correo);
            $new->bindValue(5, $this->cedulaVieja);
            $new->execute();
			parent::desconectarDB();
            $_SESSION['cedula'] = $this->cedulaNueva;
            $_SESSION['nombre'] = $this->nombre;
            $_SESSION['apellido'] = $this->apellido;
            $_SESSION['correo'] = $this->correo;

            return ['respuesta' => 'Editado correctamente'];;

		} catch (\PDOException $error) {
			return $error;
		}
	}

	private function subirImagen(){

		if($this->foto['error'] > 0){
			return ['respuesta' => 'Imagen Error', 'error' => 'Error de imágen'];
		}
		if($this->foto['type'] != 'image/jpeg' && $this->foto['type'] != 'image/jpg' && $this->foto['type'] != 'image/png'){
			return ['respuesta' => 'Error', 'error' => 'Tipo de imagen inválido.'];
		}

		// switch($this->foto['type']){
		// 	case 'image/jpeg' : $foto = imagecreatefromjpeg($this->foto['tmp_name']);
		// 	break;
		// 	case 'image/png' : $foto = imagecreatefrompng($this->foto['tmp_name']);
		// 	break;
		// 	default : $foto = imagecreatefromjpeg($this->foto['tmp_name']);
		// }

		// $ancho = imagesx($foto);
		// $largo = imagesy($foto);

		// if($ancho > 1080 || $largo > 1080){
		// 	return ['respuesta' => 'Error', 'error' => 'La imagen no puede ser mayor de 1080 x 1080'];
		// }

		$repositorio = "assets/img/perfil/";
		$extension = pathinfo($this->foto['name'], PATHINFO_EXTENSION);
		$date = date('m/d/Yh:i:sa', time());
		$rand = rand(1000,9999);
		$imgName = $date.$rand;
		$nameEnc = md5($imgName);
		$nombre =  $repositorio.$nameEnc.'.'.$extension;

		if(move_uploaded_file($this->foto['tmp_name'], $nombre)){
			try {
				parent::conectarDB();
				$new = $this->con->prepare('SELECT img FROM usuario WHERE cedula = ?');
				$new->bindValue(1, $this->cedulaNueva);
				$new->execute();
				$data = $new->fetchAll(\PDO::FETCH_OBJ);
				$fotoActual = $data[0]->img;

				if($fotoActual != $this->imagenPorDefecto){
					unlink($fotoActual);				
				}

				$new = $this->con->prepare('UPDATE usuario SET img = ? WHERE cedula = ?');
				$new->bindValue(1, $nombre);
				$new->bindValue(2, $this->cedulaNueva);
				$new->execute();
				parent::desconectarDB();
				$_SESSION['fotoPerfil'] = $nombre;

				return ['respuesta' => 'Imagen guardada.', 'url' => $nombre];

			} catch (\PDOException $error) {
				return $error;
			}
		}else{
			return ['respuesta' => 'No se guardó la imagen.'];
		}
		
	}

	private function borrarImagen(){

		try {
			parent::conectarDB();
			$new = $this->con->prepare("UPDATE usuario SET img = ? WHERE cedula = ?");
			$new->bindValue(1, $this->imagenPorDefecto);
			$new->bindValue(2, $this->cedulaNueva);
			$new->execute();
			parent::desconectarDB();
			$fotoActual = $_SESSION['fotoPerfil'];
			if($fotoActual != $this->imagenPorDefecto){
				unlink($fotoActual);				
			}

			$_SESSION['fotoPerfil'] = $this->imagenPorDefecto;

			return ['respuesta' => 'Imagen eliminada.', 'url' => $this->imagenPorDefecto];

		} catch (\PDOException $e) {
			return $e;
		}

	}

	public function getCambioContra($cedula, $passwordAct, $passwordNew, $passwordNewR){

		if(preg_match_all("/^[A-Za-z0-9 *?=&_!¡()@#]{8,30}$/", $passwordNew) == false) {
			$resultado = ['resultado' => 'Error de contraseña' , 'error' => 'Correo inválida.'];
			echo json_encode($resultado);
			die();
		}
		if($passwordNew != $passwordNewR) {
			$resultado = ['resultado' => 'Error de repass' , 'error' => 'Las contraseñas no coinciden.'];
			echo json_encode($resultado);
			die();
		}

		$this->cedula = $cedula;
		$this->passwordAct = $passwordAct;
		$this->passwordNew = $passwordNew;

		$this->cambioContra();

	}

	private function cambioContra(){
		try {

			parent::conectarDB();
			
			$this->hash = password_hash($this->passwordNew, PASSWORD_BCRYPT);

			$new = $this->con->prepare("SELECT password FROM usuario WHERE cedula = ?");
			$new->bindValue(1, $this->cedula);
			$new->execute();
			$data = $new->fetchAll();

			if (password_verify($this->passwordAct, $data[0]['password'])) {
				
				$new = $this->con->prepare("UPDATE usuario SET password= ? WHERE cedula = ?");
				$new->bindValue(1, $this->hash);
				$new->bindValue(2, $this->cedula);
				$new->execute();
				$resultado = ['resultado' => 'Editada Contraseña'];
				echo json_encode($resultado);
				die();
			}else{
				$resultado = ['resultado' => 'Error de contraseña' , 'error' => 'Contraseña incorrecta.'];
				echo json_encode($resultado);
				die();
			}
			parent::desconectarDB();
		} catch (\PDOException $error) {
			return $error;
		}

	}

}



?>