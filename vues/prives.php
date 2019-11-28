<div class="contenumur">

    <div class="listeamis" onload="scrollbas();">
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
    <div id="listemessages" class="listemessages">
        <?php 
        if (isset($_SESSION['id']) && isset($_GET['id']) && ($_GET['id'] != $_SESSION['id'])){
            //On vérif si on es amis avec la personne
            $id=$_SESSION['id'];
            $idPers = $_GET['id'];
            $sql = "SELECT * FROM lien WHERE etat='ami'AND ((idUtilisateur1=? AND idUtilisateur2=?) OR ((idUtilisateur1=? AND idUtilisateur2=?)))";
            $query = $pdo->prepare($sql);
            $query->execute(array($id,$idPers,$idPers,$id));
            $line = $query->fetch();
            //Si un résultat existe, c'est qu'on est amis avec la personne
            if($line == true){
                echo '<div><div id="conteneurmp" class="conteneurmp">';
                
                echo '</div></div>';
                formMP($idPers);
            
            }else{
                header ('Location: index.php?action=mur');
            }
        }else{
            header ('Location: index.php?action=mur');
        }
        ?>
    </div>
</div>
<script src="./js/messagesprives.js"></script>