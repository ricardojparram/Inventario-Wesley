<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class sede extends DBConnect
{
	use validar;

	private $nombre;
	private $telefono;
	private $direccion;
	private $id;
	private $idedit;

	public function getMostrarSede($bitacora = false)
	{
		try {
			$this->conectarDB();
			$new = $this->con->prepare("SELECT s.id_sede , s.nombre , s.telefono , s.direccion  FROM sede s WHERE s.status = 1");
			$new->execute();
			$data = $new->fetchAll();
			if ('true' == $bitacora) {
				$this->binnacle('Sede', $_SESSION['cedula'], 'Consultó el listado de sedes.');
			}
			$this->desconectarDB();
			return $data;
		} catch (\PDOException $error) {
			return $this->http_error(500, $error->getMessage());
		}
	}
	public function getAgregarSede($nombre, $telefono, $direccion)
	{

		if (!$this->validarString('nombre', $nombre)) {
			return $this->http_error(400, 'Nombre inválido.');
		}
		if (!$this->validarString("numero", $telefono)) {
			return $this->http_error(400, "Teléfono inválido.");
		}
		if (!$this->validarString("direccion", $direccion)) {
			return $this->http_error(400, 'Dirección inválida.');
		}

		$this->nombre = $nombre;
		$this->telefono = $telefono;
		$this->direccion = $direccion;

		return $this->agregarSede();
	}
	private function agregarSede()
	{
		try {
			$this->conectarDB();
			$new = $this->con->prepare("INSERT INTO sede(id_sede, nombre, telefono, direccion, status) VALUES(DEFAULT,?,?,?,1)");
			$new->bindValue(1, $this->nombre);
			$new->bindValue(2, $this->telefono);
			$new->bindValue(3, $this->direccion);
			$new->execute();
			$this->binnacle('Sede', $_SESSION['cedula'], 'Se registró la sede correctamente.');
			$this->desconectarDB();
			return ['resultado' => 'ok', 'msg' => 'Sede registrada con éxito'];
		} catch (\PDOException $error) {
			return $this->http_error(500, $error->getMessage());
		}
	}

	public function getSede($id)
	{
		if (!$this->validarString('numero', $id)) {
			return $this->http_error(400, "Id invalida.");
		}
		$this->idedit = $id;
		return $this->mostrarSedePorId();
	}
	private function mostrarSedePorId()
	{
		try {
			$this->conectarDB();
			$sql = "SELECT
						s.nombre,
						s.telefono,
						s.direccion
					FROM
						sede s
					WHERE
						s.id_sede = ?";
			$new = $this->con->prepare($sql);
			$new->bindValue(1, $this->idedit);
			$new->execute();
			$data = $new->fetch(\PDO::FETCH_OBJ);
			$this->desconectarDB();
			return $data;
		} catch (\PDOexception $error) {
			return $this->http_error(500, $error->getMessage());
		}
	}

	public function getEditarSede($nombre, $telefono, $direccion, $id)
	{

		if (!$this->validarString('nombre', $nombre)) {
			return $this->http_error(400, 'Nombre inválido.');
		}
		if (!$this->validarString("numero", $telefono)) {
			return $this->http_error(400, "Teléfono inválido.");
		}
		if (!$this->validarString("direccion", $direccion)) {
			return $this->http_error(400, 'Dirección inválida.');
		}
		if (!$this->validarString('numero', $id)) {
			return $this->http_error(400, "Id invalida.");
		}

		$this->nombre = $nombre;
		$this->telefono = $telefono;
		$this->direccion = $direccion;
		$this->idedit = $id;

		return $this->editarSede();
	}
	private function editarSede()
	{
		try {
			$this->conectarDB();
			$sql = "UPDATE
						sede
					SET
						nombre = ?,
						telefono = ?,
						direccion = ?
					WHERE
						id_sede = ?";
			$new = $this->con->prepare($sql);
			$new->bindValue(1, $this->nombre);
			$new->bindValue(2, $this->telefono);
			$new->bindValue(3, $this->direccion);
			$new->bindValue(4, $this->idedit);
			$new->execute();
			$this->binnacle('Sede', $_SESSION['cedula'], 'Se editó la sede correctamente.');
			$this->desconectarDB();
			return ['resultado' => 'ok', 'msg' => "Sede editada correctamente."];
		} catch (\PDOexception $error) {
			return $this->http_error(500, $error->getMessage());
		}
	}

	public function getElimarSede($id)
	{
		if ($id == "1") {
			return $this->http_error(403, "No se puede eliminar la sede principal.");
		}
		if (!$this->validarSedeSiTieneRegistros($id)) {
			return $this->http_error(403, "No se puede eliminar la sede porque ya tiene registros.");
		}
		if (!$this->validarString('numero', $id)) {
			return $this->http_error(400, "Id invalida.");
		}
		$this->id = $id;
		return $this->eliminarSede();
	}
	private function eliminarSede()
	{
		try {
			$this->conectarDB();
			$new = $this->con->prepare("UPDATE sede s SET s.status ='0' WHERE s.id_sede = ?");
			$new->bindValue(1, $this->id);
			$new->execute();
			$this->binnacle('Sede', $_SESSION['cedula'], 'Se eliminó la sede correctamente.');
			return ['resultado' => 'ok', 'msg' => 'Sede eliminada correctamente.'];
			$this->desconectarDB();
		} catch (\PDOException $error) {
			return $this->http_error(500, $error->getMessage());
		}
	}

	private function validarSedeSiTieneRegistros($id)
	{
		try {
			$this->conectarDB();
			$sql = "SELECT
						(
							COUNT(ps.id_sede) + COUNT(p.id_sede) + COUNT(t.id_sede) + COUNT(ca.id_sede) + COUNT(d.id_sede) + COUNT(h.id_sede)
						) AS count
					FROM
						sede s
						LEFT JOIN producto_sede ps ON ps.id_sede = s.id_sede
						LEFT JOIN personal p ON p.id_sede = s.id_sede
						LEFT JOIN transferencia t ON t.id_sede = s.id_sede
						LEFT JOIN cargo ca ON ca.id_sede = s.id_sede
						LEFT JOIN descargo d ON d.id_sede = s.id_sede
						LEFT JOIN historial h ON h.id_sede = s.id_sede
					WHERE
						s.id_sede = :id_sede;";
			$new = $this->con->prepare($sql);
			$new->execute([':id_sede' => $id]);
			$data = $new->fetch(\PDO::FETCH_OBJ);
			$this->desconectarDB();
			return intval($data->count) === 0;
		} catch (\PDOException $error) {
			return $this->http_error(500, $error->getMessage());
		}
	}
}
