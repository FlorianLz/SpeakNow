<?php
  if (isset($_SESSION['id'])){
    header('Location: ./profil');
  }
?>
<div>
  <form class="" action="index.php?action=connexion" method="post">
    <input type="text" name="email" placeholder="Email...">
    <input type="password" name="password" placeholder="Mot de passe...">
    <div><label for="checkbox">Rester connecter ?<input type="checkbox" value="remember" name="checkbox"></div>
    <input type="submit" name="valider" value="Connexion">
    <?php 
      if (isset($_SESSION['erreurlogin'])){
        echo '<p>'.$_SESSION['erreurlogin'].'</p>';
        unset ($_SESSION['erreurlogin']);
      } 
    ?>
  </form>
</div>
