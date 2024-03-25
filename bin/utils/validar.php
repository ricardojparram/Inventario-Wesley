<?php

namespace utils;

use DateTime;

trait validar {
    public function validarFecha($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
