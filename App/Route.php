<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {
  protected function initRoutes() {
    $routes['home'] = array(
      'route' => '/',
      'controller' => 'IndexController',
      'action' => 'index'
    );

    $routes['increverse'] = array(
      'route' => '/inscreverse',
      'controller' => 'indexController',
      'action' => 'inscreverse'
    );

    $this->setRoutes($routes);
  }
}

?>