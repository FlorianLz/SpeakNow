<div class="contenu">
    <div class="infoscote">
        <div class="monprofil">
            <a href="index.php?action=mur"><div class="imageprofil" style="background-image:url('avatars/<?php echo $_SESSION['avatar'];?>');"></div></a>
            <div class="txtprofil">
                <h1><?php echo $_SESSION['prenom'].' '.$_SESSION['nom']; ?></h1>
                <div class="activeprofil"><a href="index.php?action=profil"><i class="fas fa-user-edit"></i><p>Modifier mon profil</p></a></div>
            </div>
        </div>
        <div class="menu">
            <p>MENU</p>
            <div class="itemmenu"><a href="index.php?action=fil"><i class="fas fa-home"></i><p>Fil d'actus</p></a></div>
            <div class="itemmenu"><a href="index.php?action=recherche"><i class="fas fa-search"></i><p>Recherche</p></a></div>
            <div class="itemmenu"><a href="index.php?action=mur"><i class="fas fa-user"></i></i><p>Mon mur</p></a></div>
            <div class="itemmenu"><a href="index.php?action=prives"><i class="fas fa-comment-dots"></i><p>Messenger</p></a></div>
            <div class="itemmenu"><a href="index.php?action=amis"><i class="fas fa-user-friends"></i><p>Amis</p></a></div>
        </div>
        <div class="deconnexion">
            <a href="index.php?action=deconnexion"><i class="fas fa-sign-out-alt"></i></a>
        </div>

    </div>
    <div class="infoscentre">
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
        $datenaissance = $line['datenaissance'];
        ?>
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
                <input type="text" name="prenom" value="<?php echo $prenom; ?>" required>
                <input type="text" name="nom" value="<?php echo $nom; ?>" required>
                <input type="date" name="datenaissance" value="<?php echo $datenaissance; ?>" required>
                <input type="text" name="email" value="<?php echo $email; ?>" required>
                <input type="password" name="password" placeholder="Mot de passe..." required>
                <input type="password" name="password2" placeholder="Vérification du mot de passe..." required>
                <input type="submit" name="valider" value="Mettre à jour mes informations">
            </form>
        </div>
    </div>
</div>
