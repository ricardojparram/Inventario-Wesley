<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;
use utils\CryptoService;
use utils\JWTService;

class perfil extends DBConnect
{
	use validar;
	private $id;
	private $cedula;
	private $cedulaVieja;
	private $cedulaNueva;
	private $nombre;
	private $apellido;
	private $correo;
	private $foto;
	private $borrar;
	private $imagenPorDefecto = 'assets/img/profile_photo.jpg';


	private $actPassword;
	private $newPassword;
	private $session;

	public function __construct($session)
	{
		$this->session = $session;
		parent::__construct();
	}


	public function mostrarDatos()
	{
		try {

			parent::conectarDB();
			$new = $this->con->prepare("SELECT u.cedula, u.nombre, u.apellido, u.correo, n.nombre as nivel FROM usuario u INNER JOIN rol n ON n.id_rol = u.rol WHERE u.cedula = ?");
			$new->bindValue(1, $this->session['cedula']);
			$new->execute();
			parent::desconectarDB();
			return $new->fetchAll(\PDO::FETCH_OBJ);
		} catch (\PDOException $error) {
			return $this->http_error(500, $error);
		}
	}

	public function mostrarUsuarios()
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT img, CONCAT(nombre, ' ', apellido) AS nombre FROM usuario
				WHERE status = 1
				ORDER BY RAND() LIMIT 4");
			$new->execute();
			parent::desconectarDB();
			return $new->fetchAll(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			return $this->http_error(500, $e);
		}
	}

	public function getValidarContraseña($pass)
	{
		if (!$this->validarString('contraseña', $pass))
			return $this->http_error(400, 'Contraseña invalida.');

		$this->cedulaVieja = $this->session['cedula'];
		$this->actPassword = $pass;

		return $this->validarContraseña();
	}

	private function validarContraseña()
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT password FROM usuario WHERE cedula = ? AND status = 1");
			$new->bindValue(1, $this->cedulaVieja);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);
			parent::desconectarDB();
			if (!password_verify($this->actPassword, $data[0]->password))
				return $this->http_error(400, 'Contraseña incorrecta.');

			return ['resultado' => 'Contraseña válida.'];
		} catch (\PDOException $e) {
			return $this->http_error(500, $e);
		}
	}

	public function getEditar($foto, $nombre, $apellido, $cedulaNueva, $correo, $cedulaVieja, $borrar = false)
	{
		if (!$this->validarString('nombre', $nombre))
			return $this->http_error(400, 'Nombre inválido.');

		if (!$this->validarString('nombre', $apellido))
			return $this->http_error(400, 'Apellido inválido.');

		if (!$this->validarString('documento', $cedulaNueva))
			return $this->http_error(400, 'Cédula inválida.');

		if (!$this->validarString('correo', $correo))
			return $this->http_error(400, 'Correo inválido.');

		$this->foto = $foto;
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->cedulaNueva = $cedulaNueva;
		$this->correo = $correo;
		$this->cedulaVieja = $cedulaVieja;
		$this->borrar = $borrar;

		$resultadoEdit = $this->editarDatos();
		$resultadoFoto = "";
		if ($this->borrar !== false)
			$resultadoFoto = $this->borrarImagen();

		if (isset($this->foto['name']))
			$resultadoFoto = $this->subirImagen();

		return ['edit' => $resultadoEdit, 'foto' => $resultadoFoto];
	}

	private function editarDatos()
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("UPDATE usuario SET cedula= ?,nombre= ?,apellido= ?,correo= ? WHERE cedula = ?");
			$new->bindValue(1, $this->cedulaNueva);
			$new->bindValue(2, $this->nombre);
			$new->bindValue(3, $this->apellido);
			$new->bindValue(4, $this->correo);
			$new->bindValue(5, $this->cedulaVieja);
			$new->execute();
			parent::desconectarDB();

			$resultado = ['respuesta' => 'ok', 'msg' => "Editado correctamente"];
			if (isset($_SESSION['nivel'])) {
				$_SESSION['cedula'] = $this->cedulaNueva;
				$_SESSION['nombre'] = $this->nombre;
				$_SESSION['apellido'] = $this->apellido;
				$_SESSION['correo'] = $this->correo;
			} else {
				$userData = [
					'cedula' => $this->cedulaNueva,
					'nombre' => $this->nombre,
					'apellido' => $this->apellido,
					'correo' => $this->correo
				];
				$resultado['token'] = JWTService::updateToken($userData);
			}

			return $resultado;
		} catch (\PDOException $e) {
			return $this->http_error(500, $e);
		}
	}

	private function subirImagen()
	{

		if ($this->foto['error'] > 0)
			return ['respuesta' => 'Imagen Error', 'error' => 'Error de imágen'];

		if ($this->foto['type'] != 'image/jpeg' && $this->foto['type'] != 'image/jpg' && $this->foto['type'] != 'image/png')
			return ['respuesta' => 'Error', 'error' => 'Tipo de imagen inválido.'];

		$repositorio = "assets/img/perfil/";
		$extension = pathinfo($this->foto['name'], PATHINFO_EXTENSION);
		$date = date('m/d/Yh:i:sa', time());
		$rand = rand(1000, 9999);
		$imgName = $date . $rand;
		$nameEnc = md5($imgName);
		$nombre =  $repositorio . $nameEnc . '.' . $extension;

		if (!move_uploaded_file($this->foto['tmp_name'], $nombre))
			die(json_encode($this->http_error(500, 'No se pudo guardar la imagen.')));

		try {
			parent::conectarDB();
			$new = $this->con->prepare('SELECT img FROM usuario WHERE cedula = ?');
			$new->bindValue(1, $this->cedulaNueva);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);
			$fotoActual = $data[0]->img;

			if ($fotoActual != $this->imagenPorDefecto)
				if (file_exists($fotoActual)) unlink($fotoActual);

			$new = $this->con->prepare('UPDATE usuario SET img = ? WHERE cedula = ?');
			$new->bindValue(1, $nombre);
			$new->bindValue(2, $this->cedulaNueva);
			$new->execute();
			parent::desconectarDB();
			$_SESSION['fotoPerfil'] = $nombre;

			return ['respuesta' => 'ok', 'msg' => "Imagen guardada", 'url' => $nombre];
		} catch (\PDOException $e) {
			return $this->http_error(500, $e);
		}
	}

	private function borrarImagen()
	{

		try {
			parent::conectarDB();
			$new = $this->con->prepare("UPDATE usuario SET img = ? WHERE cedula = ?");
			$new->bindValue(1, $this->imagenPorDefecto);
			$new->bindValue(2, $this->cedulaNueva);
			$new->execute();
			parent::desconectarDB();
			$fotoActual = $_SESSION['fotoPerfil'];
			if ($fotoActual != $this->imagenPorDefecto) {
				unlink($fotoActual);
			}

			$_SESSION['fotoPerfil'] = $this->imagenPorDefecto;
			return ['respuesta' => 'ok', 'msg' => "Imagen guardada", 'url' => $this->imagenPorDefecto];
		} catch (\PDOException $e) {
			return $this->http_error(500, $e);
		}
	}

	public function getCambioContra($data)
	{
		if (isset($data['data'])) {
			$crypto = new CryptoService;
			$decrypted = $crypto->decrypt($data['data']);
			if ($decrypted['resultado'] !== "ok") {
				return $decrypted;
			}
			$data = json_decode($decrypted['msg'], true);
		}
		if ($data['actPassword'] === $data['newPassword'])
			return $this->http_error(400, 'No hubo cambio en la contraseña.');

		if (!$this->validarString('contraseña', $data['actPassword']))
			return $this->http_error(400, 'Contraseña actual invalida.');

		if (!$this->validarString('contraseña', $data['newPassword']))
			return $this->http_error(400, 'Contraseña nueva invalida.');

		$this->cedula = $this->session['cedula'];
		$this->actPassword = $data['actPassword'];
		$this->newPassword = $data['newPassword'];

		return $this->cambioContra();
	}

	private function cambioContra()
	{
		try {
			parent::conectarDB();

			$hash = password_hash($this->newPassword, PASSWORD_BCRYPT);

			$new = $this->con->prepare("SELECT password FROM usuario WHERE cedula = ?");
			$new->bindValue(1, $this->cedula);
			$new->execute();
			$data = $new->fetchAll();

			if (!password_verify($this->actPassword, $data[0]['password'])) {
				parent::desconectarDB();
				return $this->http_error(400, "Contraseña incorrecta.");
			}

			$new = $this->con->prepare("UPDATE usuario SET password = ? WHERE cedula = ?");
			$new->bindValue(1, $hash);
			$new->bindValue(2, $this->cedula);
			$new->execute();
			parent::desconectarDB();
			return ['resultado' => 'ok', 'msg' => 'Contraseña editada correctamente.'];
		} catch (\PDOException $e) {
			return $this->http_error(500, $e);
		}
	}

	public function getValidarCedula($cedula, $id)
	{
		$this->cedula = $cedula;
		$this->id = $id;
		return $this->validarCedula();
	}

	private function validarCedula()
	{
		try {

			if ($this->cedula === $this->id)
				return ['resultado' => 'Correcto'];

			parent::conectarDB();
			$new = $this->con->prepare("SELECT cedula, status FROM usuario WHERE cedula = ?");
			$new->bindValue(1, $this->cedula);
			$new->execute();
			$data = $new->fetchAll();
			parent::desconectarDB();

			if (!isset($data[0])) return ['resultado' => 'Correcto'];

			if ($data[0]['status'] == 0)
				return $this->http_error(403, 'No se puede registrar la cedula.');

			if ($data[0]['cedula'] == $this->cedula && $data[0]['status'] == 1)
				return $this->http_error(400, 'El documeto ya esta registrado');
		} catch (\PDOException $e) {
			return $this->http_error(500, $e);
		}
	}

	public function getValidarCorreo($correo, $id)
	{
		$this->correo = $correo;
		$this->id = $id;
		return $this->validarCorreo();
	}

	private function validarCorreo()
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT correo, status FROM usuario WHERE cedula != ? and correo = ?");
			$new->bindValue(1, $this->id);
			$new->bindValue(2, $this->correo);
			$new->execute();
			$data = $new->fetchAll();
			parent::desconectarDB();

			if (isset($data[0]['correo']) && $data[0]['status'] === 1)
				return $this->http_error(400, 'El correo ya está registrado.');

			return ['resultado' => 'ok', 'msg' => 'Correo válido.'];
		} catch (\PDOException $e) {
			return $this->http_error(500, $e);
		}
	}
}
