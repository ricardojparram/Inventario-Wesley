<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;
use FPDF as FPDF;

class productoDanado extends DBConnect {
    use validar;
    private $id_descargo;
    private $num_descargo;
    private $fecha;
    private $productos;
    private $id_producto;

    public function mostrarDescargos($bitacora) {
        try {
            $this->conectarDB();
            $sql = "SELECT id_descargo, fecha, num_descargo FROM descargo 
                    WHERE status = 1;";
            $new = $this->con->prepare($sql);
            $new->execute();
            // if ($bitacora == "true") $this->binnacle("Laboratorio", $_SESSION['cedula'], "Consultó listado.");
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function getMostrarDetalle($id_descargo) {
        if (!$this->validarString('entero', $id_descargo))
            return $this->http_error(400, 'descargo inválido.');

        $this->id_descargo = $id_descargo;

        return $this->mostrarDetalle();
    }
    private function mostrarDetalle() {
        try {
            $this->conectarDB();
            $sql = "SELECT ps.lote, ps.presentacion_producto, ps.fecha_vencimiento, dc.cantidad, c.num_descargo FROM descargo c 
                    INNER JOIN detalle_descargo dc ON dc.id_descargo = c.id_descargo
                    INNER JOIN vw_producto_sede_detallado ps ON ps.id_producto_sede = dc.id_producto_sede
                    WHERE c.id_descargo = ?";
            $new = $this->con->prepare($sql);
            $new->bindValue(1, $this->id_descargo);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }

    public function mostrarProductos() {
        try {
            $this->conectarDB();
            $sql = "SELECT id_producto_sede, presentacion_producto, fecha_vencimiento, cantidad FROM vw_producto_sede_detallado
                    WHERE id_sede = 1";
            $new = $this->con->prepare($sql);
            $new->execute();
            $this->desconectarDB();
            return $new->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
   
public function exportar(){
    try{
        $this->conectarDB();
        $sql = "SELECT d.num_descargo, CONCAT(tp.nombrepro,' ',pr.peso,' ',m.nombre) AS producto ,d.fecha, dd.cantidad FROM descargo d INNER JOIN detalle_descargo dd ON dd.id_descargo = d.id_descargo INNER JOIN producto_sede ps ON ps.id_producto_sede = dd.id_producto_sede INNER JOIN producto p ON p.cod_producto = ps.cod_producto INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres INNER JOIN medida m ON m.id_medida WHERE d.status = 1 GROUP BY d.id_descargo ORDER BY d.id_descargo ASC";
        $new = $this->con->prepare($sql);
        $new->execute();
        $this->desconectarDB();
        $data = $new->fetchAll();

        $fechaHoraActual = date('Y-m-d H:i:s');
        $nombre = 'producto_danado_'.$fechaHoraActual.'.pdf';
        $titulo = 'Reporte de Productos dañados';
        $subTitulo = $fechaHoraActual;

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetMargins(15,30,15);
        
        $pdf->Image('assets/img/Logos Wesley/logoWesleyColor.png',15,5,40);
        $pdf->SetFont('Arial','B',16);
        $pdf->setX(20);
        $pdf->setY(15);
        $pdf->Cell(0,10,utf8_decode($titulo),0,1,'C');
        $pdf->Cell(0,10,$subTitulo,0,0,'C');
        $pdf->Ln(18); 

        $pdf->SetFont('Helvetica','B',9);
        $pdf->SetFillColor(150, 189, 13);

        $tableWidth = 140; 
        $pageWidth = $pdf->GetPageWidth();
        $startX = ($pageWidth - $tableWidth) / 2;

        $pdf->setX($startX);

        
      

        $pdf->Cell(20,10,utf8_decode('N°'),1,0,'C',1);
        $pdf->Cell(50,10,utf8_decode('Nombre'),1,0,'C',1);
        $pdf->Cell(35,10,utf8_decode('Fecha'),1,0,'C',1);
        $pdf->Cell(35,10,utf8_decode('Cantidad'),1,1,'C',1);

        $pdf->SetFont('Arial','',9);
        $pdf->SetFillColor(245,245,245);

        $total = 0;

        
        foreach ($data as $col => $value) {
            
        $tableWidth = 140; 
        $pageWidth = $pdf->GetPageWidth();
        $startX = ($pageWidth - $tableWidth) / 2;

        $pdf->setX($startX);

          
            $pdf->Cell(20,10,utf8_decode($value[0]),1,0,'C',1);
            $pdf->Cell(50,10,utf8_decode($value[1]),1,0,'C',1);
            $pdf->Cell(35,10,utf8_decode($value[2]),1,0,'C',1);
            $pdf->Cell(35,10,utf8_decode($value[3]),1,1,'C',1);
            $total += $value[3];
        }
        $pdf->SetFont('Helvetica','B',9);
        $pdf->SetFillColor(150, 189, 13);
        $pdf->setX(105);
        $pdf->Cell(35,10,utf8_decode('Total'),1,0,'C',1);
        $pdf->Cell(35,10,utf8_decode($total),1,1,'C',1);

        $repositorio = 'assets/tickets/'.$nombre = 'producto_dañado.pdf';;
        $pdf->Output('F',$repositorio);
        
        $respuesta = ['respuesta' => 'Archivo guardado', 'ruta' => $repositorio];
        return $respuesta;




    }catch(\PDOException $e){
        return $this->http_error(500, $e->getMessag());
    }

}

  
}
