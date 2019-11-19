<?php if (isset($_SESSION['id'])){
  header('Location: index.php?action=mur');
}
?>
<div class="contenuaccueil">
<h1>Bienvenue sur le mini Facebook !</h1>
  <div class="accueil">
    <div class="gauche">
      <img src="img/logo.png">
      <p>Mini Facebook est un moyen simple et rapide de rester en contact avec vos proches et vos amis !<br>
      Qu'attendez-vous pour nous rejoindre ?</p>
    </div>
    <div class="formulaireinscription">
      <?php include("vues/inscription.php");
      if(isset($_SESSION['erreurinscription'])){
        echo '<p>'.$_SESSION['erreurinscription'].'</p>';
        unset($_SESSION['erreurinscription']);
      }
      ?>
      
    </div>
  </div>
</div>
