<?php if (isset($_SESSION['id'])){
  header('Location: index.php?action=mur');
}
?>
<div class="contenuaccueil">

  <div class="accueilgauche">
      <h1>Pas encore inscrit ?</h1>
    <div class="formulaireinscription">
      <?php include("vues/inscription.php");?>
    </div>
  </div>

  <div class="accueildroite">
    <h1>Déjà inscrit ?</h1>
    <div class="formulaireconnexion">
        <?php include("vues/login.php");?>
    </div>
  </div>

</div>
