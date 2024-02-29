<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
  public function timeline() {
    $this->validaAutenticacao();

    // Recuperação dos tweets
    $tweet = Container::getModel('Tweet');

    $tweet->__set('id_usuario', $_SESSION['id']);

    $tweets = $tweet->recuperarTweets();

    $this->view->tweets = $tweets;

    $this->render('timeline');
  }

  public function tweet() {
    $this->validaAutenticacao();

    $tweet = Container::getModel('Tweet');

    $tweet->__set('tweet', $_POST['tweet']);
    $tweet->__set('id_usuario', $_SESSION['id']);

    $tweet->salvar();

    header('Location: /timeline');
  }

  public function validaAutenticacao() {
    session_start();
    if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
      header('Location: /?login=erro');
    }
  }

  public function quemSeguir() {
    $this->validaAutenticacao();

    $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : null;

    $usuarios = array();
    if(!is_null($pesquisarPor)) {
      $usuario = Container::getModel('Usuario');
      $usuario->__set('nome', $pesquisarPor);
      $usuario->__set('id', $_SESSION['id']);
      $usuarios = $usuario->getAll();
    }

    $this->view->usuarios = $usuarios;

    $this->render('quemSeguir');
  }

  public function acao() {
    $this->validaAutenticacao();

    $acao = isset($_GET['acao']) ? $_GET['acao'] : null;
    $id_usuario_follower = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

    $usuario = Container::getModel('UsuariosSeguidores');
    $usuario->__set('id_usuario', $_SESSION['id']);

    if($acao == 'seguir') {
      $usuario->seguirUsuario($id_usuario_follower);
    } else if($acao == 'deixar_de_seguir') {
      $usuario->deixarSeguirUsuario($id_usuario_follower);
    }
  }
}

?>