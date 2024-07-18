<?php

namespace  bin\controlador;

use config\componentes\configSistema as configSistema;

class frontControlador extends configSistema
{
  private $url;
  private $directory;
  private $controlador;

  public function __construct($request)
  {
    $this->url = $request["url"] ?? 'inicio';
    $sistem = new configSistema();
    $this->directory = $sistem->_Dir_();
    $this->controlador = $sistem->_Control_();
    $this->validarURL();
  }

  private function validarURL()
  {
    $pattern = preg_match_all("/^[a-zA-Z0-9-@\/.=:_#$ ]{1,700}$/", $this->url);
    if ($pattern == 1) {
      $this->_loadPage($this->url);
    } else {
      die('LA URL INGRESADA ES INVÁLIDA');
    }
  }

  private function _loadPage($url)
  {
    if (file_exists($this->directory . $url . $this->controlador)) {
      require_once($this->directory . $url . $this->controlador);
    } else {
      $url = "inicio";
      if (file_exists($this->directory . $url . $this->controlador)) {
        die("<script>location='error'</script>");
        // require_once($this->directory . $url . $this->controlador);
      }
    }
  }
}
