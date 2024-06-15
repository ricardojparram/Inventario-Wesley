<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;


class medida extends DBConnect
{
	use validar;
	private $medida;
	private $id;
	private $idedit;

	function __construct()
	{
		parent::__construct();
	}

	public function validarMedida($medida, $id)
	{
		if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $medida) == false) {
			return ['resultado' => 'error de metodo', 'error' => 'metodo invalido'];
		}

		$this->id = ($id === 'false') ? false : $id;
		$this->medida = $medida;

		return $this->validMedida();
	}

	private function validMedida()
	{
		try {
			parent::conectarDB();
			if ($this->id === false) {
				$new = $this->con->prepare('SELECT m.nombre FROM medida m WHERE m.status = 1 AND m.nombre = ?');
				$new->bindValue(1, $this->medida);
			} else {
				$new = $this->con->prepare('SELECT m.nombre FROM medida m WHERE m.status = 1 AND m.nombre = ? AND m.id_medida != ?');
				$new->bindValue(1, $this->medida);
				$new->bindValue(2, $this->id);
			}

			$new->execute();
			$data = $new->fetchAll();

			if (isset($data[0]['nombre'])) {
				$resultado = ['resultado' => 'error', 'msg' => 'La medida ya está registrado.', 'res' => false];
			} else {
				$resultado = ['resultado' => 'medida valida', 'res' => true];
			}

			parent::desconectarDB();
			return $resultado;
		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function getAgregarMedida($medida)
	{
		if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $medida) == false) {
			$resultado = ['resultado' => 'Error de nombre', 'error' => 'Nombre inválido.'];
			echo json_encode($resultado);
			die();
		}

		$this->medida = $medida;

		$this->id = false;
		$validarMedida = $this->validMedida();
		if ($validarMedida['res'] === false) {
			return $this->http_error(400, 'La medida ya está registrado.');
		}

		return $this->AgregarMedida();
	}

	private function agregarMedida()
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("INSERT INTO `medida`(`id_medida`, `nombre`, `status`) VALUES (DEFAULT,?,1)");
			$new->bindValue(1, $this->medida);
			$new->execute();
			$data = $new->fetchAll();

			$resultado = ['resultado' => 'Registrado con exito'];
			parent::desconectarDB();

			return $resultado;
		} catch (\PDOException $error) {
			return $error;
		}
	}
	public function getMostrarMedida()
	{

		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT m.id_medida, m.nombre FROM medida m WHERE m.status = 1");
			$new->execute();
			$data = $new->fetchAll();
			echo json_encode($data);
			parent::desconectarDB();
			die();
		} catch (\PDOException $error) {

			return $error;
		}
	}



	public function getEliminarMedida($id)
	{

		if (!$this->validarMedidaSiTieneRegistros($id)) {
			return $this->http_error(400, "No se puede eliminar la medida de producto ya tiene registros.");
		}
		$this->id = $id;

		$this->eliminarMedida();
	}

	private function eliminarMedida()
	{

		try {
			parent::conectarDB();
			$new = $this->con->prepare("UPDATE medida SET status = '0' WHERE id_medida = ?");
			$new->bindValue(1, $this->id);
			$new->execute();
			$resultado = ['resultado' => 'Eliminado'];
			echo json_encode($resultado);
			parent::desconectarDB();
			die();
		} catch (\PDOException $error) {
			return $error;
		}
	}

	private function validarMedidaSiTieneRegistros($id)
	{
		try {
			$this->conectarDB();
			$sql = " SELECT (COUNT(pr.id_medida)) AS count FROM medida m LEFT JOIN presentacion pr ON pr.id_medida = m.id_medida WHERE m.id_medida = :id_medida;";

			$new = $this->con->prepare($sql);
			$new->execute([':id_medida' => $id]);
			$data = $new->fetch(\PDO::FETCH_OBJ);
			$this->desconectarDB();
			return intval($data->count) === 0;
		} catch (\PDOException $error) {
			return $this->http_error(500, $error->getMessage());
		}
	}

	public function mostrarlot($lott)
	{
		$this->idedit = $lott;

		return $this->gol();
	}
	private function gol()
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT m.nombre FROM medida m WHERE m.id_medida = ?");
			$new->bindValue(1, $this->idedit);
			$new->execute();
			$data = $new->fetchAll();
			parent::desconectarDB();
			return $data;
		} catch (\PDOException $error) {
			return $error;
		}
	}
	public function getEditarMedida($medida, $id)
	{
		if (preg_match_all("/^[a-zA-ZÀ-ÿ ]{0,30}$/", $medida) == false) {
			$resultado = ['resultado' => 'Error de tipo de Producto', 'error' => 'Tipo inválido.'];
			echo json_encode($resultado);
			die();
		}
		$this->medida = $medida;
		$this->id = $id;
		$validarMedida = $this->validMedida();
		if ($validarMedida['res'] === false) {
			return ['resultado' => 'error', 'msg' => 'La medida ya está registrado.'];
		}

		return $this->editarMedida();
	}
	private function editarMedida()
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("UPDATE medida m SET m.nombre = ? WHERE m.status = 1 AND m.id_medida = ?");

			$new->bindValue(1, $this->medida);
			$new->bindValue(2, $this->id);
			$new->execute();
			$data = $new->fetchAll();

			$resultado = ['resultado' => 'Editado'];
			parent::desconectarDB();

			return $resultado;
		} catch (\PDOexception $error) {
			return $error;
		}
	}
}
