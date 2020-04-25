<div class="contenu">
    <div class="infoscote">
        <img src="img/logosn.png" alt="Logo" class="logomenu" onclick="accueil();">
        <div class="monprofil">
            <a href="mur"><div class="imageprofil" style="background-image:url('avatars/<?php echo $_SESSION['avatar'];?>');"></div></a>
            <div class="txtprofil">
                <h1><?php echo $_SESSION['prenom'].' '.$_SESSION['nom']; ?></h1>
                <div><a href="profil"><i class="fas fa-user-edit"></i><p>Modifier mon profil</p></a></div>
            </div>
            <div class="deconnexion">
                <a href="deconnexion"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
        <div class="menu">
            <p>MENU</p>
            <div class="itemmenu"><a href="fil"><i class="fas fa-home"></i><p>Fil d'actus</p></a></div>
            <div class="itemmenu active"><a href="recherche"><i class="fas fa-search"></i><p>Recherche</p></a></div>
            <div class="itemmenu"><a href="mur"><i class="fas fa-user"></i></i><p>Mon mur</p></a></div>
        </div>
        
        <div class="partieamis">
        <?php
            $sql = "SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUTilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUTilisateur1=?)";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id'],$_SESSION['id']));
            $nbamis=$query->rowCount();
            if($nbamis == 0){
                echo '<h2>Vous n\'avez aucun ami</h2>';
            }else if($nbamis == 1){
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
        echo '<h2>Entrez le nom ou le prénom d\'une personne pour la rechercher !</h2>';
        formrecherche();
        ?>
        <div id="resultatsrecherche" class='resultatsrecherche'>
        </div>
    </div>
</div>
<script src="./js/script.js"></script>
