<?php
function getConnexion($nom){
  $dsn = 'mysql:dbname=api;host=localhost';
  $user = 'root';
  $password = '';

  try {
    $dbh = new PDO($dsn, $user, $password);
      return $dbh;
  } catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
  }
}
 ?>
