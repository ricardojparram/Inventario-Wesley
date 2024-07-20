<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class donativoPersonal extends DBConnect
{

	use validar;
	private $id;
	private $personal;
	private $beneficiario;
	private $datos;
	private $producto;
	private $cantidad;


	public function getMostrarDonativosPersonal($bitacora , $id_sede)
	{
		try {
			parent::conectarDB();

			$query = "SELECT d.id_donaciones, d.fecha , dp.cedula , CONCAT(p.nombres, ' ',p.apellidos) AS beneficiario FROM donaciones d INNER JOIN donativo_per dp ON dp.id_donaciones = d.id_donaciones INNER JOIN det_donacion dd ON dd.id_donaciones = d.id_donaciones INNER JOIN producto_sede ps ON ps.id_producto_sede = dd.id_producto_sede INNER JOIN personal p ON p.cedula = dp.cedula WHERE d.status = 1 AND ps.id_sede = ?";
			$new = $this->con->prepare($query);
			$new->bindValue(1, $id_sede);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			if ($bitacora)
				$this->binnacle("Donativo Personal", $_SESSION['cedula'], "Consultó listado donativo personal.");

			parent::desconectarDB();

			return $data;
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}

	public function getMostrarDonaciones()
	{

		try {
			parent::conectarDB();

			$query = "SELECT 
					d.id_donaciones, 
					d.fecha, 
					CONVERT(dp.cedula USING utf8mb4) AS identificador, 
					CONCAT(p.nombres, ' ', p.apellidos) AS beneficiario,
					'personal' AS tipo_donativo
				FROM donaciones d
				INNER JOIN donativo_per dp ON dp.id_donaciones = d.id_donaciones
				INNER JOIN personal p ON p.cedula = dp.cedula
				WHERE d.status = 1
				UNION ALL

				SELECT 
					d.id_donaciones, 
					d.fecha, 
					CONVERT(dp.ced_pac USING utf8mb4) AS identificador,
					CONCAT(p.nombre, ' ', p.apellido) AS beneficiario,
					'paciente' AS tipo_donativo
				FROM donaciones d
					INNER JOIN donativo_pac dp ON d.id_donaciones = dp.id_donaciones
					INNER JOIN det_donacion dd ON dd.id_donaciones = d.id_donaciones
					INNER JOIN pacientes p ON p.ced_pac = dp.ced_pac
				WHERE d.status = 1

				UNION ALL

				SELECT 
					d.id_donaciones, 
					d.fecha, 
					CONVERT(di.rif_int USING utf8mb4) AS identificador,
					i.razon_social AS beneficiario,
					'instituciones' AS tipo_donativo
				FROM donaciones d
					INNER JOIN donativo_int di ON d.id_donaciones = di.id_donaciones
					INNER JOIN det_donacion dd ON dd.id_donaciones = d.id_donaciones
					INNER JOIN instituciones i ON i.rif_int = di.rif_int 
				WHERE d.status = 1
				GROUP BY d.id_donaciones;";

			$new = $this->con->prepare($query);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;

		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}

	public function getDetalleDonacion($id)
	{
		if (!$this->validarString('entero', $id))
			return $this->http_error(400, 'id inválido.');

		$this->id = $id;

		return $this->detalleDonacion();
	}

	private function detalleDonacion()
	{
		try {
			parent::conectarDB();

			$new = $this->con->prepare("SELECT d.id_donaciones , dd.cantidad , CONCAT(tp.nombrepro,' ',pr.peso ,'',m.nombre) AS nombrepro FROM det_donacion dd INNER JOIN donaciones d ON d.id_donaciones = dd.id_donaciones INNER JOIN producto_sede ps ON ps.id_producto_sede = dd.id_producto_sede INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida = pr.id_medida WHERE d.status = 1 AND d.id_donaciones = ?");
			$new->bindValue(1, $this->id);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}

	public function selectPersonal()
	{
		try {
			parent::conectarDB();

			$new = $this->con->prepare('SELECT p.cedula , p.nombres , p.apellidos FROM personal p WHERE  p.status = 1');
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}

	public function selectProductos($id_sede)
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT ps.id_producto_sede, ps.presentacion_producto AS producto, ps.lote FROM vw_producto_sede_detallado ps INNER JOIN sede s ON s.id_sede = ps.id_sede WHERE s.status = 1 AND ps.cantidad > 0 AND s.id_sede = ? AND ps.id_producto_sede NOT IN ( SELECT cp.id_producto_sede FROM compra_producto cp ) ORDER BY ps.fecha_vencimiento");

			$new->bindValue(1, $id_sede);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}

	public function detallesProductoFila($id)
	{
		if (!$this->validarString('entero', $id))
			return $this->http_error(400, 'id inválido.');

		$this->id = $id;

		return $this->productoFila();
	}

	private function productoFila()
	{
		try {
			parent::conectarDB();

			$new = $this->con->prepare('SELECT ps.cantidad FROM producto_sede ps INNER JOIN producto p ON p.cod_producto = ps.cod_producto WHERE p.status = 1 AND ps.id_producto_sede = ?');

			$new->bindValue(1, $this->id);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();

			return $data;
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}


	public function validarCedula($cedula)
	{

		if (!$this->validarString('documento', $cedula))
			return $this->http_error(400, 'Cedula inválido.');


		$this->personal = $cedula;

		parent::conectarDB();

		return $this->validCedula();
	}

	private function validCedula()
	{
		try {

			$new = $this->con->prepare("SELECT 'Personal' AS tipo, pe.cedula AS cedula FROM personal pe WHERE pe.cedula = :cedula AND pe.status = 1");

			$new->bindValue(':cedula', $this->personal);
			$new->execute();
			$data = $new->fetchAll();

			$mensaje = 'La cedula ' . $this->personal . ' no existe';

			if (isset($data[0]['cedula'])) {
				return ['resultado' => 'cedula valida', 'res' => true];
			} else {
				return ['resultado' => 'error', 'msg' => $mensaje, 'res' => false];
			}
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}

	private function validProductos()
	{
		try {
			$mensaje = '';

			// Construir una lista de ids de productos y un mapeo de id_producto_sede 
			$ids_productos_sede = array_column($this->datos, 'producto');

			// Convertir los ids de productos a una cadena separada por comas para la consulta
			$ids_str = implode(',', array_map('intval', $ids_productos_sede));


			$sql = "SELECT 
						ps.id_producto_sede, 
						ps.cantidad, 
						CONCAT(tp.nombrepro, ' ', pr.peso, '', m.nombre) AS producto 
					FROM producto_sede ps 
					INNER JOIN producto p ON p.cod_producto = ps.cod_producto 
					INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod 
					INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres 
					INNER JOIN medida m ON m.id_medida = pr.id_medida 
					WHERE ps.id_producto_sede IN ($ids_str)";

			$stmt = $this->con->prepare($sql);
			$stmt->execute();
			$resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			// Convertir los resultados en un mapa de id_producto_sede a detalles del producto
			$productos_existentes = [];
			foreach ($resultados as $resultado) {
				$productos_existentes[$resultado['id_producto_sede']] = $resultado;
			}

			foreach ($this->datos as $producto) {
				$id_producto_sede = $producto['producto'];
				$cantidad = $producto['cantidad'];

				if (!isset($productos_existentes[$id_producto_sede])) {
					$mensaje .= "Error ID $id_producto_sede no existe. ";
				} else if ($productos_existentes[$id_producto_sede]['cantidad'] < $cantidad) {
					$producto_nombre = $productos_existentes[$id_producto_sede]['producto'];
					$mensaje .= "Error no hay suficiente $producto_nombre disponible. ";
				}
			}

			if ($mensaje) {
				return ['resultado' => 'error', 'msg' => $mensaje, 'res' => false];
			} else {
				return ['resultado' => 'producto valido', 'res' => true];
			}
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}

	public function getRegistrarDonacion($cedulaPersonal, $datos)
	{

		if (!$this->validarString('documento', $cedulaPersonal))
			return $this->http_error(400, 'Cedula inválido.');

		$estructura_productos = [
			'producto' => 'string',
			'cantidad' => 'string',
		];

		if (!$this->validarEstructuraArray($datos, $estructura_productos, true))
			return $this->http_error(400, 'Productos inválidos.');

		$this->personal = $cedulaPersonal;
		$this->datos = $datos;

		return $this->registrarDonacion();
	}

	private function registrarDonacion()
	{
		try {
			parent::conectarDB();
			$this->con->beginTransaction();

			$validarCedula = $this->validCedula();

			if ($validarCedula['res'] === false) {
				$this->con->rollBack();
				return $this->http_error(400, $validarCedula['msg']);
			}

			$new = $this->con->prepare('INSERT INTO `donaciones`(`id_donaciones`, `fecha`, `status`) VALUES (DEFAULT ,DEFAULT , 1)');
			$new->execute();

			$this->id = $this->con->lastInsertId();


			$new = $this->con->prepare('INSERT INTO `donativo_per`(`id_donativo`, `cedula`, `id_donaciones`) VALUES (DEFAULT , ? , ?)');
			$new->bindValue(1, $this->personal);
			$new->bindValue(2, $this->id);
			$new->execute();

			$validarProductos = $this->validProductos();

			if ($validarProductos['res'] === false) {
				$this->con->rollBack();
				return $this->http_error(400, $validarProductos['msg']);
			}

			foreach ($this->datos as $dato) {
				$this->producto = $dato['producto'];
				$this->cantidad = $dato['cantidad'];

				$new = $this->con->prepare('INSERT INTO `det_donacion`(`id_detalle`, `id_producto_sede`, `cantidad`, `id_donaciones`) VALUES (DEFAULT , ? , ? , ?)');
				$new->bindValue(1, $this->producto);
				$new->bindValue(2, $this->cantidad);
				$new->bindValue(3, $this->id);
				$new->execute();

				$this->inventario_historial("Donativo Personal", "", "x", "",  $this->producto, $this->cantidad);

				$new = $this->con->prepare('SELECT ps.cantidad FROM producto_sede ps WHERE ps.id_producto_sede = ?');
				$new->bindValue(1, $this->producto);
				$new->execute();
				$data = $new->fetchAll();

				$NewCantidad = $data[0]['cantidad'] - $this->cantidad;

				if ($NewCantidad < 0) {
					$this->con->rollBack();
					return $this->http_error(400, "Cantidad insuficiente para el producto ID {$this->producto}.");
				}

				$new = $this->con->prepare("UPDATE producto_sede ps SET ps.cantidad = ? WHERE ps.id_producto_sede = ?");
				$new->bindValue(1, $NewCantidad);
				$new->bindValue(2, $this->producto);
				$new->execute();
			}

			$this->con->commit();

			$this->binnacle("Donativo Personal", $_SESSION['cedula'], "Registró donativo por personal.");

			parent::desconectarDB();

			return ['resultado' => 'registrado con exito'];
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}

	public function validarExistencia($id)
	{
		if (!$this->validarString('entero', $id))
			return $this->http_error(400, 'id inválido.');

		$this->id = $id;

		parent::conectarDB();

		return $this->validExistencia();
	}


	private function validExistencia()
	{
		try {
			$new = $this->con->prepare('SELECT d.id_donaciones FROM donaciones d WHERE d.status = 1 AND d.id_donaciones = ?');
			$new->bindValue(1,  $this->id);
			$new->execute();
			$data = $new->fetchAll();

			if (isset($data[0]["id_donaciones"])) {
				return ['resultado' => 'donacion valida', 'res' => true];
			} else {
				return ['resultado' => 'error', 'msg' => 'La donación no existe', 'res' => false];
			}
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}


	public function getEliminarDonacion($id)
	{
		if (!$this->validarString('entero', $id))
			return $this->http_error(400, 'id inválido.');

		$this->id = $id;

		return $this->eliminarDonacion();
	}

	private function eliminarDonacion()
	{
		try {
			parent::conectarDB();

			$this->con->beginTransaction();

			$validarFactura = $this->validExistencia();

			if ($validarFactura['res'] === false) {
				$this->con->rollBack();
				return ['resultado' => 'error', 'msg' => 'La donacion no existe'];
			}

			$new = $this->con->prepare("SELECT ps.id_producto_sede, dd.cantidad , ps.cantidad as stock FROM det_donacion dd INNER JOIN producto_sede ps ON ps.id_producto_sede = dd.id_producto_sede WHERE dd.id_donaciones = ?");

			$new->bindValue(1, $this->id);
			$new->execute();
			$result = $new->fetchAll(\PDO::FETCH_OBJ);

			foreach ($result as $data) {

				$stockAct = $data->stock;
				$cantidad = $data->cantidad;
				$idProductoSede = $data->id_producto_sede;

				$NewStock = $cantidad + $stockAct;

				$new = $this->con->prepare("UPDATE producto_sede ps SET ps.cantidad = ? WHERE ps.id_producto_sede = ?");
				$new->bindValue(1, $NewStock);
				$new->bindValue(2, $idProductoSede);
				$new->execute();
			}

			$new = $this->con->prepare('UPDATE donaciones d SET d.status = 0 WHERE d.id_donaciones = ?');
			$new->bindValue(1, $this->id);
			$new->execute();

			$this->binnacle("Donativo Personal", $_SESSION['cedula'], "Anulo donativo por personal.");

			$resultado = ['resultado' => 'Eliminado'];

			$this->con->commit();

			parent::desconectarDB();

			return $resultado;
		} catch (\PDOException $e) {
			return $this->http_error(500, $e->getMessage());
		}
	}
}
