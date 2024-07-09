<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use utils\validar;

class mantenimiento extends DBConnect
{
    use validar;
    private $usuario;
    private $contra;
    private $local;
    private $nameBD;
    private $repositorio = 'bin/config/SQL/Respaldos/';

    public function getHistorial()
    {
        try {
            parent::conectarDB();
            $new = $this->con->prepare("SELECT concat(u.nombre,' ',u.apellido) as nombre, b.descripcion, b.fecha FROM bitacora b INNER JOIN usuario u ON b.cedula = u.cedula WHERE b.status = 1 AND b.descripcion = ?");
            $new->bindValue(1, "Creo un Copia de Seguridad");
            $new->execute();
            $data = $new->fetchAll();
            return $data;
            $this->binnacle("", $_SESSION['cedula'], "Consultó listado de Mantenimiento");

            parent::desconectarDB();
        } catch (\PDOException $error) {
            return $this->http_error(500, $error);
        }
    }

    public function getRespaldo()
    {
        $this->usuario = parent::_USER_();
        $this->contra = parent::_PASS_();
        $this->local = parent::_LOCAL_();
        $this->nameBD = parent::_BD_();


        return $this->respaldo();
    }

    private function respaldo()
    {
        try {
            date_default_timezone_set('America/Caracas');
            $date = new \DateTimeImmutable();
            $fecha = $date->format("Y-m-d_h-i-s");
            $nombreArchivo = $this->nameBD . '_' . $fecha . '.sql';
            if (!is_dir($this->repositorio)) {
                mkdir($this->repositorio, 0755, true);
            }


            $handle = fopen($this->repositorio . $nombreArchivo, 'w');
            fwrite($handle, "DROP DATABASE IF EXISTS `$this->nameBD`;\n\n");
            fwrite($handle, "CREATE DATABASE `$this->nameBD` CHARACTER SET utf8mb4;\n\n");
            fwrite($handle, "USE `$this->nameBD`;\n\n");

            parent::conectarDB();
            // Obtener todas las tablas de la base de datos
            $new = $this->con->prepare("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
            $new->execute();
            $tables = $new->fetchAll(\PDO::FETCH_COLUMN);
            $views = ['vw_producto_sede_detallado', 'vw_venta_detallada', 'vw_entrada_inventario', 'vw_salida_inventario', 'vw_donaciones_por_tipo'];

            // Desactivar restricciones de llaves foráneas
            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n\n");


            foreach ($tables as $table) {

                // Obtener la estructura de la tabla
                $new = $this->con->prepare("SHOW CREATE TABLE `$table`");
                $new->execute();
                $create_table = $new->fetchAll(\PDO::FETCH_ASSOC);

                fwrite($handle, "--\n");
                fwrite($handle, "-- Estructura de la tabla `$table`\n");
                fwrite($handle, "--\n");
                fwrite($handle, $create_table[0]['Create Table'] . ";\n\n");

                // Obtener los datos de la tabla
                $new = $this->con->prepare("SELECT * FROM `$table`");
                $new->execute();
                $result = $new->fetchAll(\PDO::FETCH_ASSOC);


                if (count($result) > 0) {
                    fwrite($handle, "--\n");
                    fwrite($handle, "-- Datos de la tabla `$table`\n");
                    foreach ($result as $row) {
                        $values = array();
                        foreach ($row as $value) {
                            // Usar addslashes para escapar valores
                            $values[] = addslashes($value);
                        }
                        fwrite($handle, "INSERT INTO `$table` VALUES ('" . implode("','", $values) . "');\n"); // Escribir los datos en el archivo de backup
                    }
                    fwrite($handle, "\n");
                }
            }

            foreach ($views as $view) {
                $new = $this->con->prepare("SHOW CREATE VIEW `$view`");
                $new->execute();
                $create_view = $new->fetchAll();
                if ($create_view) {

                    fwrite($handle, "--\n");
                    fwrite($handle, "-- Estructura de la vista `$view`\n");
                    fwrite($handle, "--\n");
                    fwrite($handle, $create_view[0]['Create View'] . ";\n\n");
                }
            }

            $new = $this->con->prepare("SHOW TRIGGERS");
            $new->execute();
            $triggers = $new->fetchAll();
            foreach ($triggers as $triggerRow) {
                $triggerName = $triggerRow['Trigger'];
                $new = $this->con->prepare("SHOW CREATE TRIGGER `$triggerName`");
                $new->execute();
                $createTriggerRow = $new->fetchAll();
                fwrite($handle, "--\n");
                fwrite($handle, "-- Estructura del trigger `$triggerName`\n");
                fwrite($handle, "--\n");
                fwrite($handle, $createTriggerRow[0]['SQL Original Statement'] . ";\n\n");
            }

            // Reactivar restricciones de llaves foráneas
            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n\n");

            fclose($handle);


            if(file_exists($this->repositorio . $nombreArchivo)){
                $this->binnacle("", $_SESSION['cedula'], "Creo un Copia de Seguridad");
                return ['respuesta' => 'ok', 'msg' => "respaldo correctamente"];
            }
            return $this->http_error(500, 'No se Creo la Copia de Seguridad');;



            
            parent::desconectarDB();
        } catch (\PDOException $error) {
            return $this->http_error(500, $error);
        }
    }
}
