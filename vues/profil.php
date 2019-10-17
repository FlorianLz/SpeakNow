<?php
  $sql = "SELECT * FROM utilisateurs WHERE id=?";
  // Etape 1  : preparation
  $query = $pdo->prepare($sql);
  // Etape 2 : execution : 2 paramètres dans la requêtes !!
  $query->execute(array($_SESSION['id']));

  // un seul fetch
  $line = $query->fetch();

  $nom=$line['nom'];
  $prenom=$line['prenom'];
  $email=$line['email'];
  $mdp=$line['mdp'];
  $avatar=$line['avatar'];
?>
<div class="contenuprofil">
  <h1>Modification du profil de : <?php echo $_SESSION['prenom'].' '.$_SESSION['nom'] ?></h1>
  <div class="infosprofil">
    <div class="photoprofil">
      <img class="avatar" src="avatars/<?php echo $avatar ?>">
      <form enctype="multipart/form-data" action="traitement/ajoutavatar.php" method="post">
        <input type="file" name="avatar" >
        <input type="submit" value="Modifier ma photo de profil">
      </form>
    </div>
      <form class="formprofil" action="index.php?action=modification" method="post">
        <table>
          <tr><td><label for="prenom">Prénom : </label></td><td><input type="text" name="prenom" value="<?php echo $prenom; ?>"></td></tr>
          <tr><td><label for="nom">Nom : </label></td><td><input type="text" name="nom" value="<?php echo $nom; ?>"></td></tr>
          <tr><td><label for="email">Email : </label></td><td><input type="text" name="email" value="<?php echo $email; ?>"></td></tr>
          <tr><td><label for="password">Mot de passe : </label></td><td><input type="password" name="password" required></td></tr>
        </table>
          <input type="submit" name="valider" value="Mettre à jour mes informations">
      </form>
  </div>
</div>
