<?php

namespace MF\Init;

abstract class Bootstrap {
  private $routes;

  abstract protected function initRoutes();

  public function __construct() {
    $this->initRoutes();
    $this->run($this->getUrl());
  }

  public function getRoutes() {
    return $this->routes;
  }

  public function setRoutes(array $routes) {
    $this->routes = $routes;
  }

  protected function run($url) {
    $routeActive = false;
    foreach ($this->getRoutes() as $key => $route) {
      if($url == $route['route']) {
        $class = "App\\Controllers\\".$route['controller'];
        $controller = new $class();
        $action = $route['action'];
        $controller->$action();
        $routeActive = true;
      }
    }
    if (!$routeActive) {
      echo '<h1 style="margin:15px;color:red;">Erro 404: Página não encontrada</h1>';
    }
  }

  protected function getUrl() {
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  }
}

?>