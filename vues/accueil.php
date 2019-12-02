<?php if (isset($_SESSION['id'])){
  header('Location: index.php?action=mur');
}
?>
<div class="contenuaccueil">
  <div id="login" class="masque">
    <div class="boutonlogin"><a href="#login">Déjà inscrit ? Connexion</a></div>
    <div class="boutoninscription"><a href="#">Pas encore inscrit ? Inscription</a></div>
  </div>
  <div class="accueilgauche">
      <h1>Pas encore inscrit ?</h1>
    <div class="formulaireinscription">
      <?php include("vues/inscription.php");?>
    </div>
    <div class="boutonloginresp"><a href="#login">Déjà inscris ? Connexion</a></div>
  </div>

  <div class="accueildroite" id="loginmobile">
    <h1>Déjà inscrit ?</h1>
    <div class="formulaireconnexion">
        <?php include("vues/login.php");?>
    </div>
    <div class="boutonloginresp"><a href="#">Pas encore inscris ? Inscription</a></div>
  </div>

</div>
