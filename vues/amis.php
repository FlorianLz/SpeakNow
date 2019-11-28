<div class="listeamismobile">
    <?php 
    $sql = "SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUTilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUTilisateur1=?)";
    $query = $pdo->prepare($sql);
    $query->execute(array($_SESSION['id'],$_SESSION['id']));
    $nbamis=$query->rowCount();
    if($nbamis == 0){
        echo '<h2>Vous n\'avez aucun ami</h2>';
    }else if($nbamis == 1){
        echo '<h2 onclick="afficherlisteamis();">Vous avez '.$nbamis.' ami</h2>';
    }else{
        echo '<h2 onclick="afficherlisteamis();">Vous avez '.$nbamis.' amis</h2>';
    }
    echo '<div id="mesamis">';
    while($line = $query->fetch()){
        echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a><a href="index.php?action=prives&id='.$line['id'].'"><i class="far fa-comment-alt chat"></i></a></div>';
    }
    echo '</div>';
    
        $sql = "SELECT utilisateurs.* FROM utilisateurs WHERE id IN(SELECT idUtilisateur1 FROM lien WHERE idUtilisateur2=? AND etat='attente') ";
        $query = $pdo->prepare($sql);
        $query->execute(array($_SESSION['id']));
        $nbrecues=$query->rowCount();
        if($nbrecues == 0){
            echo '<h2>Aucune demande reçue</h2>';
        }else if($nbrecues == 1){
            echo '<h2 onclick="afficherlisterecues();">'.$nbrecues.' demande reçue</h2>';
        }else{
            echo '<h2 onclick="afficherlisterecues();">'.$nbrecues.' demandes reçues</h2>';
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
        if($nbenvoyees == 0){
            echo '<h2>Aucune demande envoyée</h2>';
        }else if ($nbenvoyees == 1){
            echo '<h2 onclick="afficherlisteenvoyees();">'.$nbenvoyees.' demande envoyée</h2>';
        }else{
            echo '<h2 onclick="afficherlisteenvoyees();">'.$nbenvoyees.' demandes envoyées</h2>';
        }
        echo '<div id="listeenvoyees">';
        while($line = $query->fetch()){
            demandeenvoyees($line['id'],$line['avatar'],$line['prenom'],$line['nom']);
        }
        echo '</div>';
    
    ?>
    </div>
    <script src="./js/script.js"></script>