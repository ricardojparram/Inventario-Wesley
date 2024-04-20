<?php

namespace modelo;

use FPDF as FPDF;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Border;
use utils\validar;
use config\connect\DBConnect as DBConnect;

class reportes extends DBConnect
{
    use validar;
    private $tipo;
    private $fechaInicio;
    private $fechaFinal;
    private string $sql;
    private $reporte;
    private $lista;
    private $sheet;
    private $tiposDeReporteValidos;


    public function __construct()
    {
        parent::__construct();
        $this->tiposDeReporteValidos = [
            'compra' => '',
            'venta' => ''
        ];
    }

    private function obtenerReporte()
    {
        $this->key = parent::KEY();
        $this->iv = parent::IV();
        $this->cipher = parent::CIPHER();


        $queries = [
            "compra" => "SELECT c.orden_compra, p.razon_social, SUM(cp.cantidad) as cantidad, c.fecha,
							CONCAT(IF(MOD(c.monto_total / cm.cambio, 1) >= 0.5, CEILING(c.monto_total / cm.cambio), FLOOR(c.monto_total / cm.cambio) + 0.5), ' ', m.nombre) as 'total_divisa', c.monto_total, m.nombre as 'moneda',
							IF(MOD(c.monto_total / cm.cambio, 1) >= 0.5, CEILING(c.monto_total / cm.cambio), FLOOR(c.monto_total / cm.cambio) + 0.5) as 'divisa_total'
							FROM compra c 
							INNER JOIN compra_producto cp ON cp.cod_compra = c.cod_compra
							INNER JOIN proveedor p ON c.cod_prove = p.cod_prove
							INNER JOIN cambio cm ON cm.id_cambio = c.id_cambio
							INNER JOIN moneda m ON m.id_moneda = cm.moneda
							WHERE c.fecha BETWEEN ? AND ? AND c.status = 1
							GROUP BY cp.cod_compra ORDER BY c.fecha;",

            "venta" => "SELECT v.num_fact, c.cedula, CONCAT(c.nombre,' ',c.apellido) as nombre,
							DATE_FORMAT(v.fecha, '%d/%m/%Y') as fecha, CONCAT(IF(MOD(p.monto_total / cm.cambio, 1) >= 0.5, CEILING(p.monto_total / cm.cambio), FLOOR(p.monto_total / cm.cambio) + 0.5), ' ', m.nombre) as 'total_divisa' ,p.monto_total as 'monto_total', m.nombre as 'moneda',
							IF(MOD(p.monto_total / cm.cambio, 1) >= 0.5, CEILING(p.monto_total / cm.cambio), FLOOR(p.monto_total / cm.cambio) + 0.5) as 'divisa_total'
							FROM venta v 
							INNER JOIN cliente c ON v.cedula_cliente = c.cedula
							INNER JOIN pago p ON p.num_fact = v.num_fact
							INNER JOIN detalle_pago dp ON p.id_pago = dp.id_pago
							INNER JOIN cambio cm ON cm.id_cambio = dp.id_cambio
							INNER JOIN moneda m ON m.id_moneda = cm.moneda
							WHERE v.fecha BETWEEN CONCAT(?, ' 00:00:00') AND CONCAT(?, ' 23:59:59')
							GROUP BY p.num_fact;",
            "error" => ["resultado" => "error", "msg" => "Tipo de reporte inválido."]
        ];

        if(!isset($queries[$this->tipo])) {
            die(json_encode($queries["error"]));
        }
        $this->sql = $queries[$this->tipo];

        try {
            $this->conectarDB();
            $new = $this->con->prepare($this->sql);
            $new->bindValue(1, $this->fechaInicio);
            $new->bindValue(2, $this->fechaFinal);
            $new->execute();
            $reporte = $new->fetchAll();
            $this->desconectarDB();
            if (isset($reporte[0]['cedula'])) {
                foreach ($reporte as &$fila) {
                    $fila['cedula'] = openssl_decrypt($fila['cedula'], $this->cipher, $this->key, 0, $this->iv);
                    $fila[1] = openssl_decrypt($fila[1], $this->cipher, $this->key, 0, $this->iv);
                }
            }

            return $reporte;

        } catch (\PDOException $e) {
            return $e;
        }
    }

    public function getMostrarReporte($tipo, $inicio, $final)
    {
        if(!$this->validarFecha($inicio)) {
            return  ['resultado' => 'error', 'msg' => 'Fecha de inicio inválida'];
        }

        if(!$this->validarFecha($final)) {
            return  ['resultado' => 'error', 'msg' => 'Fecha final inválida'];
        }

        if(!isset($this->tiposDeReporteValidos[$tipo])) {
            return  ['resultado' => 'error', 'msg' => 'Tipo de reporte inválido'];
        }

        $this->tipo = $tipo;
        $this->fechaInicio = $inicio;
        $this->fechaFinal = $final;

        return $this->mostrarReporte();
    }

    private function mostrarReporte()
    {
        $this->reporte = $this->obtenerReporte();
        $this->conectarDB();
        $this->binnacle("Reporte", $_SESSION['cedula'], "Genero reporte de ".$this->tipo);
        $this->desconectarDB();
        return $this->reporte;

    }

    public function getExportar($tipo, $fecha1, $fecha2)
    {
        if($this->validateDate($fecha1) === false) {
            return  ['resultado' => 'error', 'msg' => 'Fecha de inicio inválida'];
        }

        if($this->validateDate($fecha2) === false) {
            return  ['resultado' => 'error', 'msg' => 'Fecha final inválida'];
        }

        if(!isset($this->tiposDeReporteValidos[$tipo])) {
            return  ['resultado' => 'error', 'msg' => 'Tipo de reporte inválido'];
        }

        $this->tipo = $tipo;
        $this->fechaInicio = $fecha1;
        $this->fechaFinal = $fecha2;

        return $this->exportarReporte();
    }

    private function exportarReporte()
    {
        $reporte = $this->obtenerReporte();
        if(empty($reporte)) {
            return ['resultado' => 'error', 'Error' => 'Reporte vacío.'];
        }
        $fechaI = date('d-m-Y', strtotime($this->fechaInicio));
        $fechaF = date('d-m-Y', strtotime($this->fechaFinal));
        $nombre = ($this->tipo == 'compra') ? 'compras_'.$fechaI.'_'.$fechaF.'.pdf' : 'ventas_'.$fechaI.'_'.$fechaF.'.pdf';
        $titulo = ($this->tipo == 'compra') ? 'Reporte de Compras' : 'Reporte de Ventas';
        $subTitulo = $fechaI.' a '.$fechaF;
        $columnas = ($this->tipo == 'compra') ? [0 => 'Orden', 1 => 'Proveedor', 2 => 'Cantidad', 3 => 'Fecha', 4 => 'Total Divisa', 5 => 'Monto Total'] : [0 => 'N°', 1 => 'Cédula', 2 => 'Nombre', 3 => 'Fecha', 4 => 'Total Divisa', 5 => 'Monto Total'];

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetMargins(15, 30, 15);

        $pdf->Image('assets/img/Logo_titulo.png', 15, 5, 40);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->setX(20);
        $pdf->setY(15);
        $pdf->Cell(0, 10, $titulo, 0, 1, 'C');
        $pdf->Cell(0, 10, $subTitulo, 0, 0, 'C');
        $pdf->Ln(18);

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetFillColor(210, 224, 137);

        $pdf->Cell(20, 10, utf8_decode($columnas[0]), 1, 0, 'C', 1);
        $pdf->Cell(30, 10, utf8_decode($columnas[1]), 1, 0, 'C', 1);
        $pdf->Cell(35, 10, utf8_decode($columnas[2]), 1, 0, 'C', 1);
        $pdf->Cell(35, 10, utf8_decode($columnas[3]), 1, 0, 'C', 1);
        $pdf->Cell(30, 10, utf8_decode($columnas[4]), 1, 0, 'C', 1);
        $pdf->Cell(30, 10, utf8_decode($columnas[5]), 1, 1, 'C', 1);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetFillColor(245, 245, 245);

        $total = 0;

        foreach ($reporte as $col => $value) {

            $pdf->Cell(20, 10, utf8_decode($value[0]), 1, 0, 'C', 1);
            $pdf->Cell(30, 10, utf8_decode($value[1]), 1, 0, 'C', 1);
            $pdf->Cell(35, 10, utf8_decode($value[2]), 1, 0, 'C', 1);
            $pdf->Cell(35, 10, utf8_decode($value[3]), 1, 0, 'C', 1);
            $pdf->Cell(30, 10, utf8_decode($value[4]), 1, 0, 'C', 1);
            $pdf->Cell(30, 10, utf8_decode($value[5]), 1, 1, 'C', 1);
            $total += $value[5];
        }

        $pdf->SetFillColor(210, 224, 137);
        $pdf->setX(135);
        $pdf->Cell(30, 10, utf8_decode('Monto total'), 1, 0, 'C', 1);
        $pdf->Cell(30, 10, utf8_decode($total), 1, 1, 'C', 1);

        $repositorio = 'assets/reportes/'.$nombre;
        $pdf->Output('F', $repositorio);

        $respuesta = ['respuesta' => 'Archivo guardado', 'ruta' => $repositorio];
        $this->conectarDB();
        $this->binnacle("Reporte", $_SESSION['cedula'], "Exportó reporte de ".$this->tipo);
        $this->desconectarDB();
        return $respuesta;
    }

    public function getReporteEstadistico($tipo, $fecha1, $fecha2)
    {
        if($this->validateDate($fecha1) === false) {
            return  ['resultado' => 'error', 'msg' => 'Fecha de inicio inválida'];
        }

        if($this->validateDate($fecha2) === false) {
            return  ['resultado' => 'error', 'msg' => 'Fecha final inválida'];
        }

        if(!isset($this->tiposDeReporteValidos[$tipo])) {
            return  ['resultado' => 'error', 'msg' => 'Tipo de reporte inválido'];
        }

        $this->tipo = $tipo;
        $this->fechaInicio = $fecha1;
        $this->fechaFinal = $fecha2;

        return $this->exportarReporteEstadistico();
    }

    private function exportarReporteEstadistico()
    {

        $reporte = $this->obtenerReporte();
        if(empty($reporte)) {
            return ['Error' => 'Reporte vacío.'];
        }

        $fechaI = $this->fechaInicio;
        $fechaF = $this->fechaFinal;
        $nombre = ($this->tipo == 'compra')
            ? 'estadisticas_compras_'.$fechaI.'_'.$fechaF
            : 'estadisticas_ventas_'.$fechaI.'_'.$fechaF;
        $titulo = ($this->tipo == 'compra') ? 'Reporte estadístico de Compras' : 'Reporte estadístico de Ventas';
        $subTitulo = $fechaI.' a '.$fechaF;
        $colValue = ($this->tipo == 'compra')
            ? [0 => 'Orden', 1 => 'Proveedor', 2 => 'Cantidad',
            3 => 'Fecha', 4 => 'Total', 5 => 'Divisa', 6 => 'Monto Total']
            : [0 => 'N°', 1 => 'Cédula', 2 => 'Nombre',
            3 => 'Fecha', 4 => 'Total', 5 => 'Divisa', 6 => 'Monto Total'];

        $spreadsheet = new Spreadsheet();
        $this->sheet = $spreadsheet->getActiveSheet();

        $col = range('A', 'L');
        foreach ($col as $columna) {
            $this->sheet->getColumnDimension($columna)->setAutoSize(true);
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                ],
            ],
        ];
        $tamaño_reporte = count($reporte) + 10;
        $this->sheet->getStyle("A1:L{$tamaño_reporte}")->applyFromArray($styleArray);

        $styleColumns = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'f1f9ca',
                ],
            ],
        ];
        $styleRows = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $styleTitle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $this->sheet->getStyle('B5:H5')->applyFromArray($styleColumns);
        $this->sheet->getStyle('D2:F3')->applyFromArray($styleTitle);
        $columnas = [
            [$colValue[0], $colValue[1], $colValue[2], $colValue[3], $colValue[4], $colValue[5], $colValue[6]]
        ];
        $listBegin = 'B5';
        $this->sheet->fromArray($columnas, null, $listBegin);
        $this->sheet->setCellValue('D2', $titulo);
        $this->sheet->setCellValue('D3', $subTitulo);
        $this->sheet->mergeCells('D2:F2');
        $this->sheet->mergeCells('D3:F3');

        $row = 6;
        foreach ($reporte as $col => $val) {
            $this->sheet->setCellValue('B'.$row, $val[0]);
            $this->sheet->setCellValue('C'.$row, $val[1]);
            $this->sheet->setCellValue('D'.$row, $val[2]);
            $this->sheet->setCellValue('E'.$row, $val['fecha']);
            $this->sheet->setCellValue('F'.$row, $val['divisa_total']);
            $this->sheet->setCellValue('G'.$row, $val['moneda']);
            $this->sheet->setCellValue('H'.$row, $val['monto_total']);
            $row++;
        }
        $row--;
        $lastRow = $row;
        $listEnd = "H{$row}";
        $this->sheet->setAutoFilter("{$listBegin}:{$listEnd}");
        $this->sheet->getStyle("{$listBegin}:{$listEnd}")->applyFromArray($styleRows);

        $this->funcionesEstadisticas($lastRow);
        ;


        $writer = new Xlsx($spreadsheet);
        $repositorio = 'assets/reportes/'.$nombre.'.xlsx';
        $writer->save($repositorio);

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($repositorio);
        $phpWord = $reader->load($repositorio);

        $xmlWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($phpWord, 'Mpdf');

        $xmlWriter->writeAllSheets();
        // $xmlWriter->setFooter("Sdfsdf");

        $repositorioPdf = "assets/reportes/".$nombre.".pdf";
        $xmlWriter->save($repositorioPdf);

        if(file_exists($repositorio)) {
            unlink($repositorio);
        }

        $respuesta = ['respuesta' => 'Archivo guardado', 'ruta' => $repositorioPdf];
        return $respuesta;
    }

    private function funcionesEstadisticas($lastRow)
    {

        $styleHover = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'color' => 'f1f9ca'
            ],
        ];

        $this->sheet->setCellValue('J5', "Suma total:");
        $this->sheet->setCellValue('K5', "=SUM(H5:H{$lastRow})");
        $this->sheet->setCellValue('J6', "Promedio monto total:");
        $this->sheet->setCellValue('K6', "=AVERAGE(H5:H{$lastRow})");

        if($this->tipo == "venta") {
            $datos_venta = $this->estadisticasVentas();

            $this->sheet->setCellValue('J7', "Cliente más frecuente:");
            $this->sheet->setCellValue('K7', "=MODE.SNGL(C5:C{$lastRow})");
            $this->sheet->setCellValue('J8', "Fecha con más ventas:");
            $this->sheet->setCellValue('K8', $datos_venta["fecha_mas_ventas"]->fecha);
            $this->sheet->setCellValue('J9', "Número de ventas esa fecha: ");
            $this->sheet->setCellValue('K9', "{$datos_venta["fecha_mas_ventas"]->ventas_del_dia}");
            $this->sheet->setCellValue('J9', "Número de ventas online: ");
            $this->sheet->setCellValue('K9', "{$datos_venta["count_ventas"]->ventas_online}");
            $this->sheet->setCellValue('J10', "Número de ventas presenciales: ");
            $this->sheet->setCellValue('K10', "{$datos_venta["count_ventas"]->ventas_presencial}");

        }

        if($this->tipo == "compra") {
            $datos_compra = $this->estadisticasCompras();

            $this->sheet->setCellValue('J7', "Mayor proveedor:");
            $this->sheet->setCellValue('K7', $datos_compra['count_proveedor']->proveedor_mas);
            $this->sheet->setCellValue('J8', "Produto más comprado:");
            $this->sheet->setCellValue('K8', $datos_compra["producto_mas_ventas"]->descripcion);
            $this->sheet->setCellValue('J9', "Cantidad comprada de ese producto: ");
            $this->sheet->setCellValue('K9', "{$datos_compra["producto_mas_ventas"]->cantidad}");

        }
    }

    private function estadisticasVentas()
    {
        try {

            $queries = [
                "count_ventas" => "
						SELECT COUNT(IF(online = 1, 1, NULL)) as ventas_online,COUNT(IF(online != 1, 1, NULL)) as ventas_presencial 
						FROM venta
						WHERE fecha BETWEEN CONCAT(?, ' 00:00:00') AND CONCAT(?, ' 23:59:59');
					",
                "fecha_mas_ventas" => "
						SELECT num_fact,DATE_FORMAT(fecha, '%d/%m/%Y') as fecha, COUNT(*) as ventas_del_dia FROM venta
						WHERE fecha BETWEEN CONCAT(?, ' 00:00:00') AND CONCAT(?, ' 23:59:59')
						GROUP BY fecha
						ORDER BY COUNT(*) DESC LIMIT 1;
					"
            ];

            $this->conectarDB();
            $new = $this->con->prepare($queries["count_ventas"]);
            $new->bindValue(1, $this->fechaInicio);
            $new->bindValue(2, $this->fechaFinal);
            $new->execute();
            $count_ventas = $new->fetchAll(\PDO::FETCH_OBJ);

            $new = $this->con->prepare($queries["fecha_mas_ventas"]);
            $new->bindValue(1, $this->fechaInicio);
            $new->bindValue(2, $this->fechaFinal);
            $new->execute();
            $fecha_mas_ventas = $new->fetchAll(\PDO::FETCH_OBJ);

            $data = ["count_ventas" => $count_ventas[0], "fecha_mas_ventas" => $fecha_mas_ventas[0]];

            $this->desconectarDB();
            return $data;

        } catch (\PDOException $e) {
            die($e);
        }
    }

    private function estadisticasCompras()
    {
        try {

            $queries = [
                "count_proveedor" => "
						SELECT COUNT(*) as proveedor_mas FROM compra c
						INNER JOIN proveedor p ON c.cod_prove = p.cod_prove
						WHERE fecha BETWEEN ? AND ?
						GROUP BY p.cod_prove
						ORDER BY COUNT(*) DESC LIMIT 1;
					",
                "producto_mas_ventas" => "
						SELECT p.descripcion, SUM(cp.cantidad) as cantidad FROM compra c 
						INNER JOIN compra_producto cp ON cp.cod_compra = c.cod_compra
						INNER JOIN producto p ON p.cod_producto = cp.cod_producto
						WHERE c.fecha BETWEEN ? AND ?
						GROUP BY p.cod_producto
						ORDER BY COUNT(*) DESC LIMIT 1;
					"
            ];

            $this->conectarDB();
            $new = $this->con->prepare($queries["count_proveedor"]);
            $new->bindValue(1, $this->fechaInicio);
            $new->bindValue(2, $this->fechaFinal);
            $new->execute();
            $count_proveedor = $new->fetchAll(\PDO::FETCH_OBJ);

            $new = $this->con->prepare($queries["producto_mas_ventas"]);
            $new->bindValue(1, $this->fechaInicio);
            $new->bindValue(2, $this->fechaFinal);
            $new->execute();
            $producto_mas_ventas = $new->fetchAll(\PDO::FETCH_OBJ);

            $data = ["count_proveedor" => $count_proveedor[0], "producto_mas_ventas" => $producto_mas_ventas[0]];

            $this->desconectarDB();
            return $data;

        } catch (\PDOException $e) {
            die($e);
        }
    }

    public function getGrafico($inicio, $final)
    {
        if(!$this->validarFecha($inicio)) {
            return  ['resultado' => 'error', 'msg' => 'Fecha de inicio inválida'];
        }

        if(!$this->validarFecha($final)) {
            return  ['resultado' => 'error', 'msg' => 'Fecha final inválida'];
        }
        $this->fechaInicio = $inicio;
        $this->fechaFinal = $final;
        return $this->datosGrafico();
    }

    private function datosGrafico()
    {
        $this->conectarDB();
        $sql = "SELECT 
              DATE_FORMAT(CAST(d.fecha AS DATE), '%d/%m/%y') as x,
              SUM(CASE WHEN di.id_donaciones IS NOT NULL THEN 1 ELSE 0 END) as donativos_int,
              SUM(CASE WHEN dp.id_donaciones IS NOT NULL THEN 1 ELSE 0 END) as donativos_pac,
              SUM(CASE WHEN dper.id_donaciones IS NOT NULL THEN 1 ELSE 0 END) as donativos_per
            FROM donaciones d
            LEFT JOIN donativo_int di ON di.id_donaciones = d.id_donaciones
            LEFT JOIN donativo_pac dp ON dp.id_donaciones = d.id_donaciones
            LEFT JOIN donativo_per dper ON dper.id_donaciones = d.id_donaciones
            WHERE d.fecha BETWEEN :inicio AND :final
            GROUP BY CAST(d.fecha AS DATE)
            ORDER BY d.fecha";
        $new = $this->con->prepare($sql);
        $new->bindValue(":inicio", $this->fechaInicio);
        $new->bindValue(":final", $this->fechaFinal);
        $new->execute();
        $res = $new->fetchAll(\PDO::FETCH_ASSOC);
        $donaciones = [
          'fechas' => array_column($res, 'x'),
          'donativos_int' => array_column($res, 'donativos_int'),
          'donativos_pac' => array_column($res, 'donativos_pac'),
          'donativos_per' => array_column($res, 'donativos_per'),
        ];

        $sql = "SELECT 
                  s.nombre,
                  SUM(CASE WHEN ps.fecha_vencimiento < CURDATE() THEN ps.cantidad ELSE 0 END) AS cantidad_vencidos,
                  SUM(CASE WHEN ps.fecha_vencimiento >= CURDATE() THEN ps.cantidad ELSE 0 END) AS cantidad_vigentes
                FROM producto_sede ps
                INNER JOIN sede s ON s.id_sede = ps.id_sede
                GROUP BY ps.id_sede;";

        $new = $this->con->prepare($sql);
        $new->execute();
        $res = $new->fetchAll(\PDO::FETCH_ASSOC);
        $productos = [
          'labels' => array_column($res, 'nombre'),
          'vencidos' => array_map(fn ($n) => ['x' => $n["nombre"], 'y' => $n['cantidad_vencidos']], $res),
          'vigentes' => array_map(fn ($n) => ['x' => $n["nombre"], 'y' => $n['cantidad_vigentes']], $res)
        ];

        return [
          'productos' => $productos,
          'donaciones' => $donaciones
        ];
    }

}
