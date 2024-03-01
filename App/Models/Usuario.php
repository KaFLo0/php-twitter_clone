<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model {
  private $id;
  private $nome;
  private $email;
  private $senha;

  public function __get($atributo) {
    return $this->$atributo;
  }

  public function __set($atributo, $valor) {
    $this->$atributo = $valor;
  }

  // Salvar
  public function salvar() {
    $query = "INSERT INTO usuarios(nome, email, senha)VALUES(:nome, :email, :senha)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':nome', $this->__get('nome'));
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->bindValue(':senha', $this->__get('senha')); // md5() -> hash de 32 caracteres
    $stmt->execute();

    return $this;
  }

  // Validar se um cadastro pode ser feito
  public function validar() {
    $valido = true;

    if(strlen($this->__get('nome')) < 3 || strlen($this->__get('email')) < 3 || strlen($this->__get('senha')) < 3) {
      $valido = false;
    }

    return $valido;
  }

  // Recuperar um usuário por email
  public function getUsuarioPorEmail() {
    $query = "SELECT nome, email FROM usuarios WHERE email = :email";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // Recuperar informações do usuário atual para usar no nome do perfil
  public function getInfoUsuario() {
    $query = "SELECT nome FROM usuarios WHERE id = :id_usuario";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  // Validar se o usuário existe no banco, e as informações coincidem com o registro no banco
  public function autenticar() {
    $query = "SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->bindValue(':senha', $this->__get('senha'));
    $stmt->execute();

    $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

    if(!empty($usuario['id']) && !empty($usuario['nome'])) {
      $this->__set('id', $usuario['id']);
      $this->__set('nome', $usuario['nome']);
    }

    return $this;
  }

  // Recuperar quem o usuário está seguindo
  public function getAll() {
    $query =
      "SELECT
        u.id,
        u.nome,
        u.email,
        (
          SELECT
            COUNT(*)
          FROM
            usuarios_seguidores AS us
          WHERE
            us.id_usuario = :id_usuario AND us.id_usuario_follower = u.id
        ) AS is_following
      FROM
        usuarios AS u
      WHERE
        u.nome LIKE :nome AND u.id != :id_usuario
    ";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // Recuperar o total de Tweets
  public function getTotalTweets() {
    $query = "SELECT COUNT(*) AS total_tweets FROM tweets WHERE id_usuario = :id_usuario";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  // Recuperar o total de pessoas que o usuario segue
  public function getTotalSeguindo() {
    $query = "SELECT COUNT(*) AS total_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  // Recuperar o total de seguidores
  public function getTotalFollowers() {
    $query = "SELECT COUNT(*) AS total_followers FROM usuarios_seguidores WHERE id_usuario_follower = :id_usuario";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
}

?>