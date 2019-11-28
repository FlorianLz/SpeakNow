<div class="contenumur">
    <!-- Partie de gauche : demande d'amis-->
    <div class="listeamis">
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
        echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a></div>';
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

    <!-- Partie centrale : fil d'actus -->
    <div class="filactu">
        <h3>Les dernières new's</h3>
        <?php
        if(!isset($_SESSION["id"])) {
            // On n est pas connecté, il faut retourner à la page de login
            header("Location:index.php?action=accueil");
        }else{
            //$sqlami="SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUtilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUtilisateur1=?)";
            //$queryami = $pdo->prepare($sqlami);
            //$queryami->execute(array($_SESSION['id'],$_SESSION['id']));
            //while($lineami = $queryami->fetch()){

                $sql="SELECT DISTINCT utilisateurs.id, nom, prenom, avatar, idAmi, idAuteur, ecrit.id, titre, contenu, dateEcrit, DATE_FORMAT(dateEcrit, 'Le %d/%m/%Y à %Hh%i') AS dateEcritFormate, image FROM ecrit JOIN utilisateurs ON utilisateurs.id=ecrit.idAuteur JOIN lien ON (utilisateurs.id=lien.idUtilisateur1 OR utilisateurs.id=lien.idUtilisateur2 ) WHERE NOT (idAmi=? AND idAuteur=?) AND ecrit.idAmi = ? AND ecrit.idAuteur=? OR ecrit.idAmi IN ( SELECT ecrit.idAmi FROM ecrit INNER JOIN lien ON idUtilisateur1=ecrit.idAmi AND etat='ami' AND idUtilisateur2=? UNION SELECT ecrit.idAmi FROM ecrit INNER JOIN lien ON idUtilisateur2=ecrit.idAmi AND etat='ami' AND idUtilisateur1=?) ORDER BY dateEcrit DESC";                $query = $pdo->prepare($sql);
                $query->execute(array($_SESSION['id'],$_SESSION['id'],$_SESSION['id'],$_SESSION['id'],$_SESSION['id'],$_SESSION['id']));
                //Pour chaque ami, on affiche les posts qui lui sont liés
                echo '<div class="conteneurposts">';
                //Pour chaque post, on crée une div
                while($line = $query->fetch()){
                    
                    if ($line['idAuteur'] != $line['idAmi']){
                        $req="SELECT nom, prenom FROM utilisateurs WHERE id=?";
                        $qreq = $pdo->prepare($req);
                        $qreq->execute(array($line['idAmi']));
                        $infos = $qreq->fetch();
                        afficherpostfil2($line['id'],$line['idAuteur'],$line['avatar'],$line['prenom'],$line['nom'],$line['dateEcritFormate'],$_SESSION['id'],$line['titre'],$line['contenu'],$line['image'],$line['dateEcrit'],$_SESSION['id'],$line['idAmi'],$infos['prenom'],$infos['nom']);
                    }else{
                        afficherpostfil($line['id'],$line['idAuteur'],$line['avatar'],$line['prenom'],$line['nom'],$line['dateEcritFormate'],$_SESSION['id'],$line['titre'],$line['contenu'],$line['image'],$line['dateEcrit'],$_SESSION['id']);
                    }
                    
                    $sqllike='SELECT * FROM aime WHERE idEcrit=? AND idUtilisateur=?';
                    $querylike = $pdo->prepare($sqllike);
                    $querylike->execute(array($line['id'],$_SESSION['id']));
                    if($linelike = $querylike->fetch()){
                        formlike($line['id'],$_SESSION['id'],"filredirection","boutonlike","suppressionlike");
                    }else{
                        formlike($line['id'],$_SESSION['id'],"filredirection","boutonpaslike","ajoutlike");
                    }

                    //Une image est liée au post ? On l'affiche
                    if(isset($line['image']) && !empty($line['image'])){
                        echo '<img src="./imagesposts/'.$line['image'].'">';
                    }
                    //On affiche le formulaire permettant de poster un commentaire
                    echo '<div class="commentairespost">';
                    formajoutcommentaire($line['id'],$_SESSION['id'],"filredirection");
                    alertecomm($line['id']);
                    //On affiche les commentaires
                    $sql1="SELECT nom, prenom, avatar, commentaires.id, commentaires.commentaire, commentaires.idAuteur, DATE_FORMAT(dateCommentaire, 'Le %d/%m/%Y à %Hh%i') AS dateCommentaire FROM utilisateurs JOIN commentaires ON commentaires.idAuteur=utilisateurs.id WHERE commentaires.idPost=? ORDER BY commentaires.id DESC";
                    $query1 = $pdo->prepare($sql1);
                    $query1->execute(array($line['id']));
                    while($line1 = $query1->fetch()){
                        echo '<div class="comm">
                        <div class="auteur"><div><a href="index.php?action=mur&id='.$line1['idAuteur'].'"><img class="imgpost" src="avatars/'.$line1['avatar'].'">
                            <div><p>'.$line1['prenom'].' '.$line1['nom'].'</p></a><p>'.$line1['dateCommentaire'].'</p></div></div>
                            <div>';
                            if($line1['idAuteur']==$_SESSION['id']){
                                formsupprimercommentaire($line['id'],$line1['id'],$line1['commentaire'],$_SESSION['id'], "filredirection");
                            }
                            echo '</div>
                            </div>
                            <p>'.$line1['commentaire'].'</p>';
                        echo '</div>';
                    }        
                    echo'</div></div>';
                };
                echo '</div>';
            //}

        }


        ?>
    
    </div>
</div>
<script src="./js/script.js"></script>