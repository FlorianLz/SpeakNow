<?php
  if (isset($_SESSION['id'])){
    header('Location: index.php?action=profil');
  }
?>
<div>
  <form class="" action="index.php?action=connexion" method="post">
    <input type="text" name="email" placeholder="Email...">
    <input type="password" name="password" placeholder="Mot de passe...">
    <input type="submit" name="valider" value="Connexion">
    <?php 
      if (isset($_SESSION['erreurlogin'])){
        echo '<p>'.$_SESSION['erreurlogin'].'</p>';
        unset ($_SESSION['erreurlogin']);
      } 
    ?>
  </form>
</div>
