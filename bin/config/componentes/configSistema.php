<?php

namespace config\componentes;

define("_URL_", $_ENV["URL"]);
define("_BD_", $_ENV['DB']);
define("_PASS_", $_ENV['DB_PASS']);
define("_USER_", $_ENV['DB_USER']);
define("_LOCAL_", $_ENV['DB_HOST']);
define("_PORT_", $_ENV['DB_PORT']);
define("DIRECTORY", "bin/controlador/");
define("MODEL", "modelo/");
define("CONTROLADOR", "Controlador.php");
define("_SMTP", $_ENV['SMTP']);
define("_SMTP_USER", $_ENV['SMTP_USER']);
define("_SMTP_PASS", $_ENV['SMTP_PASS']);

class configSistema
{
    public function _int()
    {
        if (!file_exists("bin/controlador/frontControlador.php")) {
            return "Error configSistema!";
        }
    }

    public function _URL_()
    {
        return _URL_;
    }
    public function _BD_()
    {
        return _BD_;
    }
    public function _PASS_()
    {
        return _PASS_;
    }
    public function _PORT_()
    {
        return _PORT_;
    }
    public function _USER_()
    {
        return _USER_;
    }
    public function _LOCAL_()
    {
        return _LOCAL_;
    }
    public function _Dir_()
    {
        return DIRECTORY;
    }
    public function _MODEL_()
    {
        return MODEL;
    }
    public function _Control_()
    {
        return CONTROLADOR;
    }
}
