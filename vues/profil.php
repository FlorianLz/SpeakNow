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
  <h2>Modification du profil de : <?php echo $_SESSION['prenom'].' '.$_SESSION['nom'] ?></h2>
  <div class="infosprofil">
    <div class="photoprofil">
      
      <div class="photop">
        <label for="avatar"><img class="avatar" src="avatars/<?php echo $avatar ?>"></label>
        <label class='uploadavatar' for='avatar'><i class='fas fa-image'></i>Changer de photo</label>
      </div>
      
      <form enctype="multipart/form-data" action="traitement/ajoutavatar.php" method="post">
        <input type="submit" value="Modifier ma photo de profil">
        <input type="file" name="avatar" id="avatar" required>
      </form>
    </div>
      <form class="formprofil" action="index.php?action=modification" method="post">
        <input type="text" name="prenom" value="<?php echo $prenom; ?>">
          <input type="text" name="nom" value="<?php echo $nom; ?>">
          <input type="date">
          <input type="text" name="email" value="<?php echo $email; ?>">
          <input type="password" name="password" placeholder="Mot de passe..." required>
          <input type="password" name="password2" placeholder="Vérification du mot de passe..." required>
          <input type="submit" name="valider" value="Mettre à jour mes informations">
      </form>
  </div>
</div>
