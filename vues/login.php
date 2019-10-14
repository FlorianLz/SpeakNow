<?php
  if (isset($_SESSION['id'])){
    header('Location: index.php?action=profil');
  }
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Ma page de login</title>
  </head>
  <body>
    <form class="" action="index.php?action=connexion" method="post">
      <input type="text" name="email">
      <input type="password" name="password">
      <input type="submit" name="valider" value="Connexion">
    </form>
  </body>
</html>
