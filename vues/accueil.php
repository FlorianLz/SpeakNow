<?php if (isset($_SESSION['id'])){
  header('Location: index.php?action=mur');
}
?>
<div class="accueil">
  <?php include("vues/inscription.php"); ?>
</div>
