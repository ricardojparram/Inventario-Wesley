<?php

namespace modelo;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use utils\validar;
use config\connect\DBConnect as DBConnect;
use Error;

use function PHPUnit\Framework\throwException;

class reportes extends DBConnect
{
    use validar;
    private $tipo;
    private $fechaInicio;
    private $fechaFinal;
    private $reporte;
    private $lista;
    private $datos_reporte;
    private $sheet;
    private $tiposDeReporteValidos;
    private $grafico;
    private $sede;

    public function __construct()
    {
        parent::__construct();
        $this->tiposDeReporteValidos = [
            'donaciones' => 'Donaciones',
            'productos' => 'Estado de productos',
            'salida' => 'Salida de inventario',
            'entrada' => 'Entrada de inventario'
        ];
        $this->sede = $_SESSION['id_sede'];
    }

    public function tiposDeReporte(): array
    {
        return $this->tiposDeReporteValidos;
    }

    private function obtenerReporte()
    {
        $queries = match ($this->tipo) {

            'donaciones' => function () {
                $this->datos_reporte = [
                    'columnas' => [
                        'fecha' => 'Fecha',
                        'tipo_donacion' => 'Tipo de donación',
                        'id' => 'Identificaión',
                        'nombre' => 'Nombre'
                    ],
                    'titulo' => "Reporte de donaciones {$this->sede}"
                ];
                $sql = "SELECT
                            DATE_FORMAT(CAST(dpt.fecha AS DATETIME), '%d/%m/%y %H:%i') as fecha,
                            dpt.tipo_donacion,
                            dpt.id,
                            dpt.nombre
                        FROM
                            vw_donaciones_por_tipo as dpt
                        WHERE
                            dpt.id_sede = :id_sede
                            AND dpt.fecha BETWEEN :inicio
                            AND :final
                        ORDER BY
                            dpt.fecha;";
                $new = $this->con->prepare($sql);
                $new->execute([
                    ':id_sede' => $this->sede,
                    ':inicio' => $this->fechaInicio,
                    ':final' => $this->fechaFinal
                ]);
                return ['data' => $new->fetchAll(\PDO::FETCH_ASSOC), 'columns' => $this->datos_reporte['columnas']];
            },

            'productos' => function () {
                $this->datos_reporte = [
                    'columnas' => [
                        'nombre_sede' => 'Sede',
                        'presentacion_producto' => 'Producto',
                        'lote' => 'Lote',
                        'cantidad' => 'Cantidad',
                        'fecha_vencimiento' => 'Fecha de vencimiento',
                        'estado_producto' => 'Estado',
                        'dias' => 'Dias'
                    ],
                    'titulo' => 'Reporte de productos'
                ];
                $sql = "SELECT
                            ps.nombre as nombre_sede,
                            ps.presentacion_producto as presentacion_producto,
                            ps.lote as lote,
                            ps.cantidad as cantidad,
                            DATE_FORMAT(CAST(ps.fecha_vencimiento AS DATE), '%d/%m/%y') AS fecha_vencimiento,
                            CASE
                                WHEN ps.fecha_vencimiento < CURDATE() THEN 'VENCIDO'
                                ELSE 'VIGENTE'
                            END AS estado_producto,
                            DATEDIFF(ps.fecha_vencimiento, CURDATE()) AS dias
                        FROM
                            vw_producto_sede_detallado ps
                        WHERE 
                            ps.id_sede = :id_sede;";
                $new = $this->con->prepare($sql);
                $new->execute([':id_sede' => $this->sede]);
                return ['data' => $new->fetchAll(\PDO::FETCH_ASSOC), 'columns' => $this->datos_reporte['columnas']];
            },

            'entrada' => function () {
                $this->datos_reporte = [
                    'columnas' => [
                        'presentacion_producto' => 'Producto',
                        'cantidad' => 'Cantidad',
                        'tipo' => 'Tipo',
                        'ultimo_movimiento' => 'Último movimiento'
                    ],
                    'titulo' => "Reporte de entrada de inventario {$this->sede}"
                ];
                $sql = "SELECT
                            vei.presentacion_producto,
                            SUM(vei.cantidad) as cantidad,
                            vei.tipo,
                            DATE_FORMAT(CAST(vei.fecha AS DATE), '%d/%m/%Y') as 'ultimo_movimiento'
                        FROM
                            vw_entrada_inventario vei
                        WHERE
                            vei.id_sede = :id_sede
                            AND vei.fecha BETWEEN :inicio
                            AND :final
                        GROUP BY
                            vei.presentacion_producto,
                            vei.tipo;";
                $new = $this->con->prepare($sql);
                $new->execute([
                    ':id_sede' => $this->sede,
                    ':inicio' => $this->fechaInicio,
                    ':final' => $this->fechaFinal
                ]);
                return ['data' => $new->fetchAll(\PDO::FETCH_ASSOC), 'columns' => $this->datos_reporte['columnas']];
            },
            'salida' => function () {
                $this->datos_reporte = [
                    'columnas' => [
                        'presentacion_producto' => 'Producto',
                        'cantidad' => 'Cantidad',
                        'tipo' => 'Tipo',
                        'origen' => 'Origen',
                        'ultimo_movimiento' => 'Último movimiento'
                    ],
                    'titulo' => "Reporte de salida de inventario {$this->sede}"
                ];
                $sql = "SELECT
                            vsi.presentacion_producto,
                            SUM(vsi.cantidad) as cantidad,
                            vsi.tipo,
                            vsi.origen as origen,
                            DATE_FORMAT(CAST(vsi.fecha AS DATE), '%d/%m/%Y') as 'ultimo_movimiento'
                        FROM
                            vw_salida_inventario vsi
                        WHERE
                            vsi.id_sede = :id_sede
                            AND vsi.fecha BETWEEN :inicio
                            AND :final
                        GROUP BY
                            vsi.presentacion_producto,
                            vsi.tipo;";
                $new = $this->con->prepare($sql);
                $new->execute([
                    ':id_sede' => $this->sede,
                    ':inicio' => $this->fechaInicio,
                    ':final' => $this->fechaFinal
                ]);
                return ['data' => $new->fetchAll(\PDO::FETCH_ASSOC), 'columns' => $this->datos_reporte['columnas']];
            },
            default => fn () => $this->http_error(404, "Tipo de reporte no existe.")
        };
        try {
            $this->conectarDB();
            $reporte = $queries();
            $this->desconectarDB();
            return $reporte;
        } catch (\Throwable $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getMostrarReporte($tipo, $inicio, $final)
    {
        if (!$this->validarFecha($inicio)) {
            return $this->http_error(404, 'Fecha de inicio inválida');
        }
        if (!$this->validarFecha($final)) {
            return $this->http_error(404, 'Fecha final inválida');
        }
        if (!isset($this->tiposDeReporteValidos[$tipo])) {
            return $this->http_error(404, 'Tipo de reporte inválido');
        }

        $this->tipo = $tipo;
        $this->fechaInicio = $inicio;
        $this->fechaFinal = $final;

        return [
            'reporte' => $this->mostrarReporte(),
            'grafico' => $this->datosGrafico()
        ];
    }

    private function mostrarReporte()
    {
        $this->reporte = $this->obtenerReporte();
        $this->conectarDB();
        $this->binnacle("Reporte", $_SESSION['cedula'], "Genero reporte de " . $this->tipo);
        $this->desconectarDB();
        return $this->reporte;
    }

    public function getExportar($tipo, $fecha1, $fecha2, $grafico)
    {
        if (!$this->validarFecha($fecha1)) {
            return $this->http_error(400, 'Fecha de inicio inválida');
        }
        if (!$this->validarFecha($fecha2)) {
            return $this->http_error(400, 'Fecha de final inválida');
        }
        if (!isset($this->tiposDeReporteValidos[$tipo])) {
            return $this->http_error(400, 'Tipo de reporte inválido');
        }

        $this->tipo = $tipo;
        $this->fechaInicio = $fecha1;
        $this->fechaFinal = $fecha2;
        $this->grafico = $grafico;

        return $this->exportarReporte();
    }

    private function exportarReporte()
    {

        ['data' => $reporte] = $this->obtenerReporte();
        if (empty($reporte)) {
            return $this->http_error(400, "El reporte está vacío");
        }
        $nombre = "estadisticas_{$this->tipo}_{$this->fechaInicio}_{$this->fechaFinal}";
        $titulo = $this->datos_reporte['titulo'];
        $subTitulo = "$this->fechaInicio a $this->fechaFinal";

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

        $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'M'];
        $listBegin = 'B5';
        $columnas = $this->datos_reporte['columnas'];
        $col_final = $cells[count($columnas)];
        $this->sheet->fromArray($columnas, null, $listBegin);
        $this->sheet->getStyle('B5:' . $col_final . '5')->applyFromArray($styleColumns);
        $this->sheet->getStyle('B2:' . $col_final . '3')->applyFromArray($styleTitle);
        $this->sheet->setCellValue('B2', $titulo);
        $this->sheet->setCellValue('B3', $subTitulo);
        $this->sheet->mergeCells('B2:' . $col_final . '2');
        $this->sheet->mergeCells('B3:' . $col_final . '3');

        $this->sheet->fromArray($reporte, null, 'B6');
        $rows_count = count($reporte) + 5;

        $this->sheet->getStyle('B6:' . $col_final . $rows_count)->applyFromArray($styleRows);


        $base64img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->grafico));
        $img = "assets/img/" . uniqid('grafico');
        file_put_contents($img, $base64img);
        $drawing = new Drawing();
        $drawing->setName('Image');
        $drawing->setDescription('Image');
        $drawing->setPath($img);
        $drawing->setCoordinates($cells[count($columnas) + 2] . '5');
        $drawing->setHeight(400);
        $drawing->setWorksheet($this->sheet);

        $writer = new Xlsx($spreadsheet);
        $repositorio = 'assets/reportes/' . $nombre . '.xlsx';
        $writer->save($repositorio);
        unlink($img);

        // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($repositorio);
        // $phpWord = $reader->load($repositorio);
        //
        // $xmlWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($phpWord, 'Mpdf');
        //
        // $repositorioPdf = "assets/reportes/".$nombre.".pdf";
        // $xmlWriter->save($repositorioPdf);
        //
        // if(file_exists($repositorio)) {
        //     unlink($repositorio);
        // }

        $respuesta = ['respuesta' => 'Archivo guardado', 'ruta' => $repositorio];
        return $respuesta;
    }

    private function datosGrafico()
    {
        $this->conectarDB();
        $tipos = match ($this->tipo) {
            'donaciones' => function () {
                $sql = "SELECT
                            DATE_FORMAT(CAST(d.fecha AS DATE), '%d/%m/%y') as x,
                            SUM(
                                CASE
                                    WHEN di.id_donaciones IS NOT NULL THEN 1
                                    ELSE 0
                                END
                            ) as donativos_int,
                            SUM(
                                CASE
                                    WHEN dp.id_donaciones IS NOT NULL THEN 1
                                    ELSE 0
                                END
                            ) as donativos_pac,
                            SUM(
                                CASE
                                    WHEN dper.id_donaciones IS NOT NULL THEN 1
                                    ELSE 0
                                END
                            ) as donativos_per
                        FROM
                            vw_donaciones_por_tipo d
                            LEFT JOIN donativo_int di ON di.id_donaciones = d.id_donaciones
                            LEFT JOIN donativo_pac dp ON dp.id_donaciones = d.id_donaciones
                            LEFT JOIN donativo_per dper ON dper.id_donaciones = d.id_donaciones
                        WHERE
                            d.id_sede = :id_sede
                            AND 
                            d.fecha BETWEEN :inicio AND :final
                        GROUP BY
                            CAST(d.fecha AS DATE)
                        ORDER BY
                            d.fecha;";
                $new = $this->con->prepare($sql);
                $new->execute([
                    ':id_sede' => $this->sede,
                    ':inicio' => $this->fechaInicio,
                    ':final' => $this->fechaFinal
                ]);
                $res = $new->fetchAll(\PDO::FETCH_ASSOC);
                $this->desconectarDB();
                return [
                    'fechas' => array_column($res, 'x'),
                    'donativos_int' => array_column($res, 'donativos_int'),
                    'donativos_pac' => array_column($res, 'donativos_pac'),
                    'donativos_per' => array_column($res, 'donativos_per'),
                ];
            },
            'productos' => function () {
                $sql = "SELECT
                            s.nombre,
                            SUM(
                                CASE
                                    WHEN ps.fecha_vencimiento < CURDATE() THEN ps.cantidad
                                    ELSE 0
                                END
                            ) AS cantidad_vencidos,
                            SUM(
                                CASE
                                    WHEN ps.fecha_vencimiento >= CURDATE() THEN ps.cantidad
                                    ELSE 0
                                END
                            ) AS cantidad_vigentes
                        FROM
                            producto_sede ps
                            INNER JOIN sede s ON s.id_sede = ps.id_sede
                        WHERE 
                            ps.id_sede = :id_sede
                        GROUP BY
                            ps.id_sede;";
                $new = $this->con->prepare($sql);
                $new->execute([':id_sede' => $this->sede]);
                $res = $new->fetchAll(\PDO::FETCH_ASSOC);
                $this->desconectarDB();
                return [
                    'labels' => array_column($res, 'nombre'),
                    'vencidos' => array_map(fn ($n) => ['x' => $n["nombre"], 'y' => $n['cantidad_vencidos']], $res),
                    'vigentes' => array_map(fn ($n) => ['x' => $n["nombre"], 'y' => $n['cantidad_vigentes']], $res)
                ];
            },
            'entrada' => function () {
                $sql = "SELECT
                            vei.presentacion_producto,
                            SUM(vei.cantidad) as cantidad,
                            vei.tipo,
                            DATE_FORMAT(CAST(vei.fecha AS DATE), '%d/%m/%Y') as 'ultimo_movimiento'
                        FROM
                            vw_entrada_inventario vei
                        WHERE
                            vei.id_sede = :id_sede
                            AND vei.fecha BETWEEN :inicio
                            AND :final
                        GROUP BY
                            vei.presentacion_producto
                        ORDER BY
                            SUM(vei.cantidad) DESC
                        LIMIT
                            8;";
                $new = $this->con->prepare($sql);
                $new->execute([':id_sede' => $this->sede, ':final' => $this->fechaFinal, ':inicio' => $this->fechaInicio]);
                $res = $new->fetchAll();
                return [
                    'labels' => array_column($res, 'presentacion_producto'),
                    'data' => array_map(fn ($n) => ['x' => $n["cantidad"], 'y' => $n['presentacion_producto']], $res),
                ];
                return 'xd';
            },
            'salida' => function () {
                $sql = "SELECT
                            vsi.presentacion_producto,
                            SUM(vsi.cantidad) as cantidad,
                            vsi.tipo as tipo,
                            vsi.origen as origen,
                            DATE_FORMAT(CAST(vsi.fecha AS DATE), '%d/%m/%Y') as 'ultimo_movimiento'
                        FROM
                            vw_salida_inventario vsi
                        WHERE
                            vsi.id_sede = :id_sede
                            AND vsi.fecha BETWEEN :inicio
                            AND :final
                        GROUP BY
                            vsi.presentacion_producto
                        ORDER BY
                            SUM(vsi.cantidad) DESC
                        LIMIT
                            8;";
                $new = $this->con->prepare($sql);
                $new->execute([':id_sede' => $this->sede, ':final' => $this->fechaFinal, ':inicio' => $this->fechaInicio]);
                $res = $new->fetchAll();
                return [
                    'labels' => array_column($res, 'presentacion_producto'),
                    'data' => array_map(fn ($n) => ['x' => $n["cantidad"], 'y' => $n['presentacion_producto']], $res),
                ];
                return 'xd';
            },
            default => fn () => $this->http_error(404, 'El tipo de grafico no existe.')
        };

        return $tipos();
    }
}
