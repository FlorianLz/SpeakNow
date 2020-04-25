<div class="contenu">
    <div class="infoscote">
        <img src="img/logosn.png" alt="Logo" class="logomenu" onclick="accueil();">
        <div class="monprofil">
            <a href="mur"><div class="imageprofil" style="background-image:url('avatars/<?php echo $_SESSION['avatar'];?>');"></div></a>
            <div class="txtprofil">
                <h1><?php echo $_SESSION['prenom'].' '.$_SESSION['nom']; ?></h1>
                <div class="activeprofil"><a href="profil"><i class="fas fa-user-edit"></i><p>Modifier mon profil</p></a></div>
            </div>
            <div class="deconnexion">
                <a href="deconnexion"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
        <div class="menu">
            <p>MENU</p>
            <div class="itemmenu"><a href="fil"><i class="fas fa-home"></i><p>Fil d'actus</p></a></div>
            <div class="itemmenu"><a href="recherche"><i class="fas fa-search"></i><p>Recherche</p></a></div>
            <div class="itemmenu"><a href="mur"><i class="fas fa-user"></i></i><p>Mon mur</p></a></div>
        </div>
        <div class="partieamis">
        <?php
            $sql = "SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUTilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUTilisateur1=?)";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id'],$_SESSION['id']));
            $nbamis=$query->rowCount();
            /*if($nbamis == 0){
                echo '<h2>Vous n\'avez aucun ami</h2>';
            }else*/ if($nbamis == 1){
                echo '<div class="itemmenu"><i class="fas fa-user-friends"></i><p onclick="afficherlisteamis();">Vous avez '.$nbamis.' ami</p></div>';
            }else{
                echo '<div class="itemmenu"><i class="fas fa-user-friends"></i><p onclick="afficherlisteamis();">Vous avez '.$nbamis.' amis</p></div>';
            }
            echo '<div id="mesamis">';
            while($line = $query->fetch()){
                echo '<div class="ami"><a href="mur-'.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="mur-'.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a><a href="prives-'.$line['id'].'"><i class="fas fa-comment-dots chat"></i></a></div>';
            }
            echo '</div>';

            $sql = "SELECT utilisateurs.* FROM utilisateurs WHERE id IN(SELECT idUtilisateur1 FROM lien WHERE idUtilisateur2=? AND etat='attente') ";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id']));
            $nbrecues=$query->rowCount();
            /*if($nbrecues == 0){
                echo '<div class="itemmenu"><i class="fas fa-user-plus"></i><p>Aucune demande reçue</p></div>';
            }else */if($nbrecues == 1){
                echo '<div class="itemmenu"><i class="fas fa-user-plus"></i><p onclick="afficherlisterecues();">'.$nbrecues.' demande reçue</p></div>';
            }else if($nbrecues > 1){
                echo '<div class="itemmenu"><i class="fas fa-user-plus"></i><p onclick="afficherlisterecues();">'.$nbrecues.' demandes reçues</p></div>';
            }
            echo '<div id="listerecues">';
            while($line = $query->fetch()){
                demandesrecues($line['id'],$line['avatar'],$line['prenom'],$line['nom']);
            }
            echo '</div>';

            $sql = "SELECT utilisateurs.* FROM utilisateurs INNER JOIN lien ON utilisateurs.id=idUtilisateur2 AND etat='attente' AND idUtilisateur1=?";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id']));
            $nbenvoyees=$query->rowCount();
            /*if($nbenvoyees == 0){
                echo '<div class="itemmenu"><i class="fas fa-user-check"></i><p>Aucune demande envoyée</p></div>';
            }else*/ if ($nbenvoyees == 1){
                echo '<div class="itemmenu"><i class="fas fa-user-check"></i><p onclick="afficherlisteenvoyees();">'.$nbenvoyees.' demande envoyée</p></div>';
            }else if($nbenvoyees > 1){
                echo '<div class="itemmenu"><i class="fas fa-user-check"></i><p onclick="afficherlisteenvoyees();">'.$nbenvoyees.' demandes envoyées</p></div>';
            }
            echo '<div id="listeenvoyees">';
            while($line = $query->fetch()){
                demandeenvoyees($line['id'],$line['avatar'],$line['prenom'],$line['nom'],0);
            }
            echo '</div>';

            ?>
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
            <form class="formprofil" action="modification" method="post">
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
<script src="./js/script.js"></script>
