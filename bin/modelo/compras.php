<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;
use utils\fechas;

class compras extends DBConnect
{

	use validar;
	use fechas;
	private $id;
	private $proveedor;
	private $orden;
	private $fecha;
	private $montoT;
	private $productos;


	public function __construct()
	{
		parent::__construct();
	}

	public function mostrarCompras($bitacora = false)
	{

		try {

			parent::conectarDB();


			$query = "SELECT c.orden_compra, c.fecha, c.monto_total, c.ced_prove FROM compra c WHERE c.status = 1";

			$new = $this->con->prepare($query);
			$new->execute();
			$data = $new->fetchAll();

			echo json_encode($data);


			if ($bitacora) $this->binnacle("Compras", $_SESSION['cedula'], "Consultó listado.");

			parent::desconectarDB();
			die();
		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function mostrarProveedor()
	{
		try {

			parent::conectarDB();

			$new = $this->con->prepare("SELECT p.razon_social, p.rif_proveedor FROM proveedor p WHERE p.status = 1;
				");
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);
			parent::desconectarDB();
			return $data;
		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function productoDetalle($id)
	{
		if (preg_match_all("/^[0-9]{1,10}$/", $id) != 1) {
			die(json_encode(['error' => 'Id invalido.']));
		}
		$this->productos = $id;

		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT cp.cantidad, cp.precio_compra , CONCAT(tp.nombrepro,' ',pr.peso,'',m.nombre) AS producto,c.orden_compra FROM compra_producto cp INNER JOIN compra c ON cp.orden_compra = c.orden_compra INNER JOIN producto_sede ps ON ps.id_producto_sede = cp.id_producto_sede INNER JOIN producto p ON ps.cod_producto = p.cod_producto INNER JOIN tipo_producto tp ON p.id_tipoprod = tp.id_tipoprod INNER JOIN presentacion pr ON p.cod_pres = pr.cod_pres INNER JOIN medida m ON pr.id_medida = m.id_medida WHERE c.status = 1 AND c.orden_compra = ? ;
		");
			$new->bindValue(1, $this->productos);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			parent::desconectarDB();
			return $data;
		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function mostrarSelect()
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT p.cod_producto, CONCAT(tp.nombrepro,' ',pr.peso,'',m.nombre)AS producto FROM producto p INNER JOIN tipo_producto tp ON p.id_tipoprod = tp.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON pr.id_medida = m.id_medida WHERE p.status = 1;
			");
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);
			parent::desconectarDB();
			return $data;
		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function getRegistrarCompra($proveedor, $orden, $fecha, $monto, $productos)
	{
		if (!$this->validarString('rif', $proveedor))
			return $this->http_error(400, 'Proveedor inválido.');

		if (!$this->validarString('entero', $orden))
			return $this->http_error(400, 'Proveedor inválido.');

		if (!$this->validarFecha($fecha, 'Y-m-d'))
			return $this->http_error(400, 'Fecha inválida.');

		if (!$this->validarString('decimal', $monto))
			return $this->http_error(400, 'Monto inválido.');

		$estructura_productos = [
			'id_producto' => 'string',
			'lote' => 'string',
			'cantidad' => 'string',
			'precio' => 'string',
			'fecha_vencimiento' => 'string'
		];

		if (!$this->validarEstructuraArray($productos, $estructura_productos, true))
			return $this->http_error(400, 'Productos inválidos.');

		$this->proveedor = $proveedor;
		$this->orden = $orden;
		$this->fecha = $fecha;
		$this->montoT = $monto;
		$this->productos = $productos;

		return $this->registrarCompra();
	}

	private function registrarCompra()
	{
		try {
			parent::conectarDB();
			$new = $this->con->prepare("SELECT orden_compra FROM compra WHERE status = 1 AND orden_compra = ?");
			$new->bindValue(1, $this->orden);
			$new->execute();
			$data = $new->fetchAll(\PDO::FETCH_OBJ);

			if (isset($data[0]->orden_compra)) {
				die(json_encode(['resultado' => 'Error de orden', 'error' => 'Orden de compra ya registrada.']));
			}
			$new = $this->con->prepare("INSERT INTO `compra`(`orden_compra`, `fecha`, `monto_total`, `ced_prove`, `status`) VALUES (?,?,?,?,1)");
			$new->bindValue(1, $this->orden);
			$new->bindValue(2, $this->fecha);
			$new->bindValue(3, $this->montoT);
			$new->bindValue(4, $this->proveedor);
			$new->execute();

			foreach ($this->productos as $producto) {

				$fecha_vencimiento = $this->convertirFecha($producto['fecha_vencimiento'], 'd/m/Y');

				$new = $this->con->prepare("INSERT INTO `producto_sede`(`id_producto_sede`, `cod_producto`, `lote`, `fecha_vencimiento`, `id_sede`, `cantidad`) VALUES (DEFAULT,?,?,?,1,?)");
				$new->bindValue(1, $producto['id_producto']);
				$new->bindValue(2, $producto['lote']);
				$new->bindValue(3, $fecha_vencimiento);
				$new->bindValue(4, $producto['cantidad']);
				$new->execute();
				$this->id = $this->con->lastInsertId();

				$new = $this->con->prepare("INSERT INTO `compra_producto`(`id_detalle`, `id_producto_sede`, `orden_compra`, `cantidad`, `precio_compra`) VALUES (DEFAULT,?,?,?,?)");
				$new->bindValue(1, $this->id);
				$new->bindValue(2, $this->orden);
				$new->bindValue(3, $producto['cantidad']);
				$new->bindValue(4, $producto['precio']);
				$new->execute();
			}


			$resultado = ['resultado' => 'Registrado con exito'];

			$this->desconectarDB();
			return $resultado;
		} catch (\PDOException $e) {
			return $e;
		}
	}

	public function getEliminarCompra($id)
	{
            if (!$this->validarCompraSiTieneRegistros($id)) {
                return $this->http_error(400, "No se puede eliminar la compra ya tiene registros.");
            }
		$this->id = $id;
		return $this->eliminarCompra();
	}

	private function eliminarCompra()
	{

		try {
			parent::conectarDB();
			$new = $this->con->prepare("UPDATE `compra` SET status = 0 WHERE `orden_compra`= ? ");
			$new->bindValue(1, $this->id);
			$new->execute();
			$resultado = ['resultado' => 'Eliminado con exito.'];
			echo json_encode($resultado);
			parent::desconectarDB();
			die();
		} catch (\PDOException $error) {
			return $error;
		}
	}
	private function validarCompraSiTieneRegistros($id){
		try{
            $this->conectarDB();
            $sql = "SELECT cp.cantidad as cantidaCompra, ps.cantidad as cantidadSede FROM compra c INNER JOIN compra_producto cp ON c.orden_compra = cp.orden_compra INNER JOIN producto_sede ps ON cp.id_producto_sede = ps.id_producto_sede WHERE cp.orden_compra = :orde_compra;";
            $new = $this->con->prepare($sql);
            $new->execute([':orde_compra'=>$id]);
            $this->desconectarDB();
        }catch (\PDOException $error) {
            return $this->http_error(500, $error->getMessage());
        }
	}
}
