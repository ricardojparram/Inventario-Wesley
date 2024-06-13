<?php


session_start();

if (file_exists('vendor/autoload.php')) {

  require 'vendor/autoload.php';
} else {
  return "Error: no se encontró el autoload.";
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use config\componentes\configSistema as configSistema;

$GlobalConfig = new configSistema();
$GlobalConfig->_int();

use bin\controlador\frontControlador as frontControlador;

$IndexSystem = new frontControlador($_REQUEST);
?>