<?php
  if (isset($_SESSION['id'])){
    header('Location: index.php?action=profil');
  }
?>

<form class="" action="index.php?action=connexion" method="post">
  <input type="text" name="email">
  <input type="password" name="password">
  <input type="submit" name="valider" value="Connexion">
</form>
