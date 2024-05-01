<?php

use component\initcomponents as initcomponents;
use component\tienda as tienda;
use component\footerInicio as footerInicio;
use modelo\inicio as inicio;

$model = new inicio();

$VarComp = new initcomponents();
$tiendaComp = new tienda();
$footer = new footerInicio();
require "vista/inicio/inicioVista.php";

