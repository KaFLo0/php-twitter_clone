<?php

namespace MF\Controller;

abstract class Action {
  protected $view;

  public function __construct() {
    $this->view = new \stdClass();
  }

  protected function render($view, $layout = 'layout') {
    $this->view->page = $view;
    if(file_exists("../App/Views/".$layout.".phtml")) {
      require_once "../App/Views/".$layout.".phtml";
    } else {
      $this->content();
    }
  }

  protected function content() {
    $self_class = get_class($this);
    $self_class = str_replace('App\\Controllers\\', '', $self_class);
    $self_class = strtolower(str_replace('Controller', '', $self_class));
    require_once "../App/Views/".$self_class."/".$this->view->page.".phtml";
  }
}

?>