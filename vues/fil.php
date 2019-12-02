<div class="contenu">
    <div class="infoscote">
        <div class="monprofil">
            <a href="index.php?action=mur"><div class="imageprofil" style="background-image:url('avatars/<?php echo $_SESSION['avatar'];?>');"></div></a>
            <div class="txtprofil">
                <h1><?php echo $_SESSION['prenom'].' '.$_SESSION['nom']; ?></h1>
                <div><a href="index.php?action=profil"><i class="fas fa-user-edit"></i><p>Modifier mon profil</p></a></div>
            </div>
        </div>
        <div class="menu">
            <p>MENU</p>
            <div class="itemmenu active"><a href="index.php?action=fil"><i class="fas fa-home"></i><p>Fil d'actus</p></a></div>
            <div class="itemmenu"><a href="index.php?action=recherche"><i class="fas fa-search"></i><p>Recherche</p></a></div>
            <div class="itemmenu"><a href="index.php?action=mur"><i class="fas fa-user"></i></i><p>Mon mur</p></a></div>
            <div class="itemmenu"><a href="index.php?action=prives"><i class="fas fa-comment-dots"></i><p>Messenger</p></a></div>
            <div class="itemmenu"><a href="index.php?action=fil"><i class="fas fa-user-friends"></i><p>Amis</p></a></div>
        </div>
        <div class="deconnexion">
            <a href="index.php?action=deconnexion"><i class="fas fa-sign-out-alt"></i></a>
        </div>

    </div>
    <div class="infoscentre">
    <h1>Les dernières new's</h1>
        <?php
        if(!isset($_SESSION["id"])) {
            // On n est pas connecté, il faut retourner à la page de login
            header("Location:index.php?action=accueil");
        }else{
            //$sqlami="SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUtilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUtilisateur1=?)";
            //$queryami = $pdo->prepare($sqlami);
            //$queryami->execute(array($_SESSION['id'],$_SESSION['id']));
            //while($lineami = $queryami->fetch()){

                $sql="SELECT DISTINCT utilisateurs.id, nom, prenom, avatar, idAmi, idAuteur, ecrit.id, titre, contenu, dateEcrit, DATE_FORMAT(dateEcrit, 'Le %d/%m/%Y à %Hh%i') AS dateEcritFormate, image FROM ecrit JOIN utilisateurs ON utilisateurs.id=ecrit.idAuteur JOIN lien ON (utilisateurs.id=lien.idUtilisateur1 OR utilisateurs.id=lien.idUtilisateur2 ) WHERE ecrit.idAmi IN ( SELECT ecrit.idAmi FROM ecrit INNER JOIN lien ON idUtilisateur1=ecrit.idAmi AND etat='ami' AND idUtilisateur2=? UNION SELECT ecrit.idAmi FROM ecrit INNER JOIN lien ON idUtilisateur2=ecrit.idAmi AND etat='ami' AND idUtilisateur1=?) OR (ecrit.idAmi=? AND NOT ecrit.idAuteur=?) ORDER BY dateEcrit DESC";                
                $query = $pdo->prepare($sql);
                $query->execute(array($_SESSION['id'],$_SESSION['id'],$_SESSION['id'],$_SESSION['id']));
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
                    $sqlnblike="SELECT * FROM aime WHERE idEcrit=?";
                    $querynblike = $pdo->prepare($sqlnblike);
                    $querynblike->execute(array($line['id']));
                    $nblike=$querynblike->rowCount();
                    if($linelike = $querylike->fetch()){
                        formlike($line['id'],$_SESSION['id'],"filredirection","boutonlike","suppressionlike",$nblike);
                    }else{
                        formlike($line['id'],$_SESSION['id'],"filredirection","boutonpaslike","ajoutlike",$nblike);
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