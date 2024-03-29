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
    // Variáveis de paginação
    $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
    $limit = 10;
    $offset = ($pagina - 1) * $limit;

    $tweets = $tweet->recuperarTweets($limit, $offset);
    $this->view->total_paginas = ceil($tweets[0]['total']/$limit);
    $this->view->pagina_ativa = $pagina;

    $this->view->tweets = $tweets;

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    $this->view->info_usuario = $usuario->getInfoUsuario();
    $this->view->total_tweets = $usuario->getTotalTweets();
    $this->view->total_seguindo = $usuario->getTotalSeguindo();
    $this->view->total_followers = $usuario->getTotalFollowers();

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

  public function deletarTweet() {
    $this->validaAutenticacao();

    $tweet = Container::getModel('Tweet');

    $tweet->__set('id_usuario', $_SESSION['id']);
    $id_tweet = isset($_GET['tweetid']) ? $_GET['tweetid'] : null;

    if(!is_null($id_tweet)) {
      $tweet->deletarTweet($id_tweet);
    }

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

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    $this->view->info_usuario = $usuario->getInfoUsuario();
    $this->view->total_tweets = $usuario->getTotalTweets();
    $this->view->total_seguindo = $usuario->getTotalSeguindo();
    $this->view->total_followers = $usuario->getTotalFollowers();

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
    header('Location: /quem_seguir');
  }
}

?>