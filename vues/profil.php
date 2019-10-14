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
<div class="infosprofil">
  <div class="photoprofil">
    <img class="avatar" src="avatars/<?php echo $avatar ?>">
    <form enctype="multipart/form-data" action="traitement/ajoutavatar.php" method="post">
      <input type="file" name="avatar" >
      <input type="submit" value="Valider">
    </form>
  </div>
    <form class="formprofil" action="index.php?action=modification" method="post">
      <h1>Modification du profil de : <?php echo '<p contenteditable="true">'.$_SESSION['nom'].'</p>' ?></h1>
        <label for="prenom">Prénom : </label><input type="text" name="prenom" value="<?php echo $prenom; ?>"><br>
        <label for="prenom">Nom : </label><input type="text" name="nom" value="<?php echo $nom; ?>"><br>
        <label for="prenom">Email : </label><input type="text" name="email" value="<?php echo $email; ?>"><br>
        <label for="prenom">Mot de passe : </label><input type="password" name="password" required><br>
        <input type="submit" name="valider" value="valider">
    </form>
</div>
