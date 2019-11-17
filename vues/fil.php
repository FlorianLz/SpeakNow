<div class="contenumur">
    <!-- Partie de gauche : demande d'amis-->

    <div class="demandesami">
        <?php 
        echo '<h2>Demandes reçues</h2>';
        $sql = "SELECT utilisateurs.* FROM utilisateurs WHERE id IN(SELECT idUtilisateur1 FROM lien WHERE idUtilisateur2=? AND etat='attente') ";
        $query = $pdo->prepare($sql);
        $query->execute(array($_SESSION['id']));
        while($line = $query->fetch()){
            echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a>';
            echo '<form method="post" action="index.php?action=ajoutami">
                        <input type="hidden" name="idAmi" value="'.$line['id'].'">
                        <input type="submit" value="Accepter">
                        </form>
                        <form method="post" action="index.php?action=refusami">
                        <input type="hidden" name="idAmi" value="'.$line['id'].'">
                        <input type="submit" value="Refuser">
                        </form></div>';
        }

        echo '<h2>Demandes envoyées</h2>';
        $sql = "SELECT utilisateurs.* FROM utilisateurs INNER JOIN lien ON utilisateurs.id=idUtilisateur2 AND etat='attente' AND idUtilisateur1=?";
        $query = $pdo->prepare($sql);
        $query->execute(array($_SESSION['id']));
        while($line = $query->fetch()){
            echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a>';
            echo '<form method="post" action="index.php?action=annulerajout">
                        <input type="hidden" name="idAmi" value="'.$line['id'].'">
                        <input type="submit" value="Annuler">
                        </form></div>';
        }
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
            $sql="SELECT DISTINCT nom, prenom, avatar, idAmi, idAuteur, ecrit.id, titre, contenu, dateEcrit, DATE_FORMAT(dateEcrit, 'Le %d/%m/%Y à %Hh%i') AS dateEcritFormate, image FROM ecrit JOIN utilisateurs ON ecrit.idAuteur = utilisateurs.id JOIN lien ON( lien.idUtilisateur1 = utilisateurs.id OR lien.idUtilisateur2 = utilisateurs.id ) WHERE lien.etat = 'ami' AND( ecrit.idAmi = lien.idUtilisateur1 OR ecrit.idAmi = lien.idUtilisateur2 AND( lien.idUtilisateur1 = ? OR lien.idUtilisateur2 = ? ) AND NOT idAuteur=idAmi) ORDER BY ecrit.id DESC";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id'],$_SESSION['id']));
            echo '<div class="conteneurposts">';
            while($line = $query->fetch()){
                echo '<div class="postmur" id="post'.$line['id'].'">
                <div class="auteur"><div><a href="index.php?action=mur&id='.$line['idAuteur'].'"><img class="imgpost" src="avatars/'.$line['avatar'].'">
                <div><div class="infosauteurs"><p>'.$line['prenom'].' '.$line['nom'].'</a></p>';
                if($line['idAmi'] != $line['idAuteur']){
                    $req="SELECT nom, prenom FROM utilisateurs WHERE id=?";
                    $qreq = $pdo->prepare($req);
                    $qreq->execute(array($line['idAmi']));
                    $infos = $qreq->fetch();
                    if($line['idAmi'] == $_SESSION['id']){
                        echo '<a href="index.php?action=mur&id='.$_SESSION['id'].'"><p class="infosbold">> '.$infos['prenom'].' '.$infos['nom'].'</p></a></div>';
                    }else{
                        echo '<a href="index.php?action=mur&id='.$line['idAmi'].'"><p>> '.$infos['prenom'].' '.$infos['nom'].'</p></a></div>';
                    }

                }else{
                    echo '</div>';
                }
                
                echo '<p>'.$line['dateEcritFormate'].'</p></div></div>
                <div>';
                if($line['idAuteur'] == $_SESSION['id']){
                    echo '<form method="post" action="index.php?action=supprimerpost&idredirection='.$_SESSION['id'].'">
                    <input type="hidden" name="id" value="'.$line['id'].'">
                    <input type="hidden" name="titre" value="'.$line['titre'].'">
                    <input type="hidden" name="message" value="'.$line['contenu'].'">';
                    if(isset($line['image']) && !empty($line['image'])){
                        echo '<input type="hidden" name="image" value="'.$line['image'].'">';
                    }
                echo '<input type="hidden" name="date" value="'.$line['dateEcrit'].'">
                    <label for="supprimer"><i class="fas fa-times"></i></label>
                    <input type="submit" value="" id="supprimer">
                    </form>';
                };
                echo '</div>
                </div>
                <p class="titrepost">'.$line['titre'].'</p><br>';
                echo '<p>'.$line['contenu'].'</p>';
                    $sqllike='SELECT * FROM aime WHERE idEcrit=? AND idUtilisateur=?';
                    $querylike = $pdo->prepare($sqllike);
                    $querylike->execute(array($line['id'],$_SESSION['id']));
                    if($linelike = $querylike->fetch()){
                        formlike($line['id'],"ok","filredirection","boutonlike","suppressionlike");
                    }else{
                        formlike($line['id'],"ok","filredirection","boutonpaslike","ajoutlike");
                    }
                //Une image est liée au post ? On l'affiche
                if(isset($line['image']) && !empty($line['image'])){
                    echo '<img src="./imagesposts/'.$line['image'].'">';
                }
                //On affiche le formulaire permettant de poster un commentaire
                echo '<div class="commentairespost">
                        <form method="post" action="index.php?action=ajoutcommentaire">
                            <img class="imgpost" src="avatars/'.$_SESSION['avatar'].'">
                            <textarea name="comm" placeholder="Votre commentaire..."></textarea>
                            <input type="hidden" name="filredirection" value="ok">
                            <input type="hidden" name="idpost" value="'.$line['id'].'">
                            <input type="submit" value="" name="submit" id="submit'.$line['id'].'"><label for="submit'.$line['id'].'"><i class="fas fa-paper-plane"></i></label>
                        </form>';
                if(isset($_SESSION['alertecomm'])){
                    echo $_SESSION['alertecomm'];
                    unset($_SESSION['alertecomm']);
                }
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
                            echo '<form method="post" action="index.php?action=supprimercommentaire">
                            <input type="hidden" name="idCommentaire" value="'.$line1['id'].'">
                            <input type="hidden" name="commentaire" value="'.$line1['commentaire'].'">
                            <input type="hidden" name="idpost" value="'.$line['id'].'">
                            <input type="hidden" name="filredirection" value="ok">
                            <label for="supprimercomm'.$line1['id'].'"><i class="fas fa-times"></i></label>
                            <input type="submit" value="" id="supprimercomm'.$line1['id'].'">                                
                            </form>';
                        }
                        echo '</div>
                        </div>
                        <p>'.$line1['commentaire'].'</p>';
                    echo '</div>';
                }        
                echo'</div></div>';
            };
            echo '</div>';
        }


        ?>
    
    </div>
    
    <div class="listeamis">
    <?php 
    echo '<h2>Liste de mes amis</h2>';
    $sql = "SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUTilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUTilisateur1=?)";
    $query = $pdo->prepare($sql);
    $query->execute(array($_SESSION['id'],$_SESSION['id']));
    while($line = $query->fetch()){
        echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a></div>';
    }
    
    ?>
    </div>













</div>