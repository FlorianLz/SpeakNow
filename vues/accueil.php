<?php if (isset($_SESSION['id'])){
  header('Location: index.php?action=profil');
}
?>
<h1>Bienvenue sur le mini facebook !</h1>
<div class="accueil">
  <div class="accueil_gauche">
    <p>Avec le mini Facebook, partagez et restez en contact avec votre entourage.</p>
    <img src="https://static.xx.fbcdn.net/rsrc.php/v3/yi/r/OBaVg52wtTZ.png" alt="">
  </div>
  <?php include("vues/inscription.php"); ?>
</div>
