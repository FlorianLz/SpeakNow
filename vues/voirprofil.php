<?php
  if (isset ($_GET['id'])){
    $id=$_GET['id'];
    $sql = 'SELECT * FROM utilisateurs WHERE id=?';

    // Etape 1  : preparation
    $query = $pdo->prepare($sql);
    // Etape 2 : execution : 2 paramètres dans la requêtes !!
    $query->execute(array($id));
    while($line=$q->fetch()){
      $prenom = $line['prenom'];
      $email = $line['email'];
    }
    echo $email;
  }


?>
