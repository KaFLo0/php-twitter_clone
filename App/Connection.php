<?php

namespace App;

class Connection {
  public static function getDb() {
    try {
      $conexao = new \PDO(
        "mysql:host=localhost;dbname=mvc;charset=utf8",
        "root",
        ""
      );
      return $conexao;
    } catch (\PDOException $e) {
      echo '<p>' . $e->getMessage() . '</p>';
    }
  }
}

?>