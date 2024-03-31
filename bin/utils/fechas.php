<?php

namespace utils;

use DateTime;

trait fechas {
    public function convertirFecha($date, $formatIn, $formatOut = 'Y-m-d') {
        $d = DateTime::createFromFormat($formatIn, $date);
        return $d->format($formatOut);
    }
}
