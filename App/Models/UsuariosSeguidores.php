<?php

namespace App\Models;

use MF\Model\Model;

class UsuariosSeguidores extends Model {
  private $id;
  private $id_usuario;
  private $id_usuario_follower;

  public function __get($atributo) {
    return $this->$atributo;
  }

  public function __set($atributo, $valor) {
    $this->$atributo = $valor;
  }

  public function seguirUsuario($id_usuario_follower) {
    $query =
      "INSERT INTO usuarios_seguidores(
        id_usuario, id_usuario_follower)
      VALUES(
        :id_usuario, :id_usuario_follower
    )";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->bindValue(':id_usuario_follower', $id_usuario_follower);
    $stmt->execute();

    return true;
  }

  public function deixarSeguirUsuario($id_usuario_follower) {
    $query =
      "DELETE FROM
        usuarios_seguidores
      WHERE
        id_usuario = :id_usuario AND id_usuario_follower = :id_usuario_follower
    ";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->bindValue(':id_usuario_follower', $id_usuario_follower);
    $stmt->execute();

    return true;
  }
}

?>