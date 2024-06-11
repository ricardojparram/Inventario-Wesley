<?php

namespace utils;

use DateTime;
use \utils\validar;

trait fechas
{
    use validar;
    public function convertirFecha($date, $formatIn, $formatOut = 'Y-m-d')
    {
        if (!$this->validarFecha($date, $formatIn)) {
            return false;
        }
        $d = DateTime::createFromFormat($formatIn, $date);
        return $d->format($formatOut);
    }
}
