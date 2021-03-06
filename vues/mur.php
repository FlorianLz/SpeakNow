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
            <div class="itemmenu"><a href="recherche"><i class="fas fa-search"></i><p>Recherche</p></a></div>
            <?php if (!isset($_GET['id']) || $_GET['id'] == $_SESSION['id']){
                echo '<div class="itemmenu active"><a href="mur"><i class="fas fa-user"></i></i><p>Mon mur</p></a></div>';
            }else{
                $sql="SELECT prenom, nom,id FROM utilisateurs WHERE id=?";
                $query=$pdo->prepare($sql);
                $query->execute(array($_GET['id']));
                $line=$query->fetch();
                echo '<div class="itemmenu active"><a href="mur-'.$line['id'].'"><i class="fas fa-user"></i></i><p>Mur de '.$line['prenom'].' '.$line['nom'].'</p></a></div>';
            }
            
            ?>
            
        </div>

        <div class="partieamis">
        <?php
            $sql = "SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUTilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUTilisateur1=?)";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id'],$_SESSION['id']));
            $nbamis=$query->rowCount();
            /*if($nbamis == 0){
                echo '<h2>Vous n\'avez aucun ami</h2>';
            }else */if($nbamis == 1){
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

        if(!isset($_SESSION["id"])) {
            // On n est pas connecté, il faut retourner à la page de login
            header("Location:accueil");
        }

        // On veut afficher notre mur ou celui d'un de nos amis et pas faire n'importe quoi
        $ok = false;

        if(!isset($_GET["id"]) || ($_GET["id"]==$_SESSION["id"])){ //Il n'y as pas d'id en GET ou celui-ci est celui de la session
            $id = $_SESSION["id"];
            $ok = true; // On a le droit d afficher notre mur
            $avatar=$_SESSION['avatar'];
            $nom=$_SESSION['nom'];
            $prenom=$_SESSION['prenom'];
            echo '<div class="monprofilmobile">
                <a href="mur"><div class="imageprofil" style="background-image:url(\'avatars/'.$avatar.'\');"></div></a>
                <div class="txtprofilmobile">
                    <h1>'.$prenom.' '.$nom.'</h1>
                    <div><a href="profil"><i class="fas fa-user-edit"></i><p>Modifier mon profil</p></a></div>
                </div>
                <div class="deconnexion">
                    <a href="deconnexion"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>';
                    //Formulaire permettant de poster sur SON mur
            formajoutpost($_SESSION['id'],"");

            if(isset($_SESSION['alerte'])){
                echo '<p>'.$_SESSION['alerte'].'</p>';
                unset($_SESSION['alerte']);
            }

            //Requête permettant de sélectionner tous les commentaires qui nous concerne
            $sql="SELECT nom, prenom, avatar, titre, ecrit.id, contenu, dateEcrit, DATE_FORMAT(dateEcrit, 'Le %d/%m/%Y à %Hh%i') AS dateEcritFormate, ecrit.image, idAuteur FROM utilisateurs JOIN ecrit ON utilisateurs.id=ecrit.idAuteur WHERE ecrit.idAmi=? ORDER BY dateEcrit DESC";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id']));
            //On affiche un conteneur pour les posts
            echo '<div class="conteneurposts">';
            //Pour chaque post, on crée une div
            while($line = $query->fetch()){
                afficherpost($line['id'],$line['idAuteur'],$line['avatar'],$line['prenom'],$line['nom'],$line['dateEcritFormate'],$_SESSION['id'],$line['titre'],$line['contenu'],$line['image'],$line['dateEcrit'],$_SESSION['id']);

                //Une image est liée au post ? On l'affiche
                if(isset($line['image']) && !empty($line['image'])){
                    echo '<img src="./imagesposts/'.$line['image'].'">';
                }
                $sqllike='SELECT * FROM aime WHERE idEcrit=? AND idUtilisateur=?';
                $querylike = $pdo->prepare($sqllike);
                $querylike->execute(array($line['id'],$_SESSION['id']));

                $sqlnblike="SELECT * FROM aime WHERE idEcrit=?";
                $querynblike = $pdo->prepare($sqlnblike);
                $querynblike->execute(array($line['id']));
                
                $nblike=$querynblike->rowCount();
                if($linelike = $querylike->fetch()){
                    formlike($line['id'],"boutonlike",$nblike);
                }else{
                    formlike($line['id'],"boutonpaslike",$nblike);
                }
                //On affiche le formulaire permettant de poster un commentaire
                echo '<div class="commentairespost" id="commentairespost'.$line['id'].'">';
                formajoutcommentaire($line['id'],$_SESSION['id']);
                
                //On affiche les commentaires
                $sql1="SELECT nom, prenom, avatar, commentaires.id, commentaires.commentaire, commentaires.idAuteur, DATE_FORMAT(dateCommentaire, 'Le %d/%m/%Y à %Hh%i') AS dateCommentaire FROM utilisateurs JOIN commentaires ON commentaires.idAuteur=utilisateurs.id WHERE commentaires.idPost=? ORDER BY commentaires.id ASC";
                $query1 = $pdo->prepare($sql1);
                $query1->execute(array($line['id']));
                while($line1 = $query1->fetch()){
                    echo '<div class="comm" id="comm'.$line1['id'].'">
                    <div class="auteur"><div><a href="mur-'.$line1['idAuteur'].'"><img class="imgpost" src="avatars/'.$line1['avatar'].'">
                        <div><p>'.$line1['prenom'].' '.$line1['nom'].'</p></a><p>'.$line1['dateCommentaire'].'</p></div></div>
                        <div>';
                        if($line1['idAuteur']==$_SESSION['id']){
                            formsupprimercommentaire($line['id'],$line1['id']);
                        }
                        echo '</div>
                        </div>
                        <p>'.$line1['commentaire'].'</p>';
                    echo '</div>';
                }        
                echo'</div></div>';
            };
            echo '</div></div>';

        } else { //On cherche à afficher le mur d'un membre
            $id=$_SESSION['id'];
            $idPers = $_GET['id'];
            // Vérifions si on est amis avec cette personne
            $sql = "SELECT * FROM lien WHERE etat='ami'AND ((idUtilisateur1=? AND idUtilisateur2=?) OR ((idUtilisateur1=? AND idUtilisateur2=?)))";
            $query = $pdo->prepare($sql);
            $query->execute(array($id,$idPers,$idPers,$id));
            $line = $query->fetch();
            //Si un résultat existe, c'est qu'on est amis avec la personne
            if($line == true){
                $ok=true;
            }
        }

        if(isset($_GET['id']) AND ($_GET['id'] != $_SESSION['id'])){
            //Si on est pas amis avec la personne ($ok=false)
            if($ok==false) {
                //On récupère les infos de la personne
                $infospers='SELECT nom,prenom,avatar FROM utilisateurs WHERE id=?';
                $querypers = $pdo->prepare($infospers);
                $querypers->execute(array($_GET['id']));
                $infos = $querypers->fetch();
                //On affiche son avatar, son nom et son prénom
                echo '<div class="profil"><div class="imgprofil"><img src=avatars/'.$infos['avatar'].'></div><div class="infoprofil">';
                echo '<h2>'.$infos['prenom'].' '.$infos['nom'].'</h2>';
                
                //On regarde maintenant si un lien existe
                $sql='SELECT * FROM lien WHERE (idUtilisateur1=? AND idUtilisateur2=?) OR ((idUtilisateur1=? AND idUtilisateur2=?))';
                $query = $pdo->prepare($sql);
                $query->execute(array($_SESSION['id'],$_GET['id'],$_GET['id'],$_SESSION['id']));
                //Si un résultat existe, c'est qu'une demande d'ami à été faite d'un côté ou que l'utilisateur est banni
                if ($line = $query->fetch()){
                    //Si l'état est en attente
                    if ($line['etat'] == 'attente'){
                        //Si l'utilisateur 1 correspond à notre id, c'est qu'on a demandé la personne en ami
                        if($line['idUtilisateur1'] == $_SESSION['id']){
                            echo '<p> Vous avez demandé en ami</p>';
                            echo '<form method="post" action="annulerajout">
                            <input type="hidden" name="idAmi" value="'.$_GET['id'].'">
                            <input type="hidden" name="idpage" value="'.$_GET['id'].'">
                            <input type="submit" value="Annuler">
                            </form></div></div></div>';
                        }else{
                            //Sinon c'est qu'elle nous a demandé en ami
                            echo '<p> Vous a demandé en ami</p><form method="post" action="ajoutami">
                            <input type="hidden" name="idAmi" value="'.$_GET['id'].'">
                            <input type="submit" value="Accepter">
                            </form>
                            <form method="post" action="refusami">
                            <input type="hidden" name="idAmi" value="'.$_GET['id'].'">
                            <input type="submit" value="Refuser">
                            </form></div></div></div>';
                        }
                                
                    }
                    //Si l'état est à banni, c'est qu'une dès 2 pers à banni l'autre
                    if ($line['etat'] == 'banni'){
                        echo '<p> Utilisateur Banni</p></div></div></div>';
                    }
                //Si aucun état, c'est que nous ne sommes pas encore amis avec la personne
                }else{
                    echo "Vous n êtes pas encore ami, vous ne pouvez voir son mur !!";
                    echo '<form action="demandeami" method="POST"><input type="hidden" name="idAmi" value="'.$_GET['id'].'"><input type="hidden" name="idpage" value="'.$_GET['id'].'"><input type="submit" value="Ajouter"></form></div></div></div>';
                }       
            } else { //Si on est amis avec la personne
            //Je récupère les infos de la personne
            $sql="SELECT nom, prenom, avatar FROM utilisateurs  WHERE id=?";
            $query = $pdo->prepare($sql);
            $query->execute(array($idPers));
            $line = $query->fetch();
            $nomPers=$line['nom'];
            $prenomPers=$line['prenom'];
            $avatarPers=$line['avatar'];
                echo '<div class="profil"><div class="imgprofil"><img src=avatars/'.$avatarPers.'></div><div class="infoprofil">';
                echo '<h2>'.$prenomPers.' '.$nomPers.'</h2>';
                echo '<a href="prives-'.$idPers.'"><i class="fas fa-comment-dots chat"></i></a>';
                echo '<form method="post" action="refusami">
                <input type="hidden" name="idAmi" value="'.$idPers.'">
                <input type="submit" value="Supprimer cet ami">
                </form></div></div>';
                //On affiche le formulaire permettant de mettre un post sur son mur
                formajoutpost($_GET['id'],$prenomPers); 

                if(isset($_SESSION['alerte'])){
                    echo '<p>'.$_SESSION['alerte'].'</p>';
                    unset($_SESSION['alerte']);
                }
                //Requête permettant de sélectionner tous les commentaires qui la concerne
                $sql="SELECT nom, prenom, avatar, titre, ecrit.id, contenu, dateEcrit, DATE_FORMAT(dateEcrit, 'Le %d/%m/%Y à %Hh%i') AS dateEcritFormate, ecrit.image, idAuteur FROM utilisateurs JOIN ecrit ON utilisateurs.id=ecrit.idAuteur WHERE ecrit.idAmi=? ORDER BY dateEcrit DESC";
                $query = $pdo->prepare($sql);
                $query->execute(array($idPers));
                //On affiche un conteneur pour les posts
                echo '<div class="conteneurposts">';
                //Pour chaque post, on crée une div
                while($line = $query->fetch()){
                    afficherpost($line['id'],$line['idAuteur'],$line['avatar'],$line['prenom'],$line['nom'],$line['dateEcritFormate'],$_SESSION['id'],$line['titre'],$line['contenu'],$line['image'],$line['dateEcrit'],$idPers);
                    //Une image est liée au post ? On l'affiche
                    if(isset($line['image']) && !empty($line['image'])){
                        echo '<img src="./imagesposts/'.$line['image'].'">';
                    }
                    $sqllike='SELECT * FROM aime WHERE idEcrit=? AND idUtilisateur=?';
                    $querylike = $pdo->prepare($sqllike);
                    $querylike->execute(array($line['id'],$_SESSION['id']));
                    $sqlnblike="SELECT * FROM aime WHERE idEcrit=?";
                    $querynblike = $pdo->prepare($sqlnblike);
                    $querynblike->execute(array($line['id']));
                    $nblike=$querynblike->rowCount();
                    if($linelike = $querylike->fetch()){
                        formlike($line['id'],"boutonlike",$nblike);
                    }else{
                        formlike($line['id'],"boutonpaslike",$nblike);
                    }
                    //On affiche le formulaire permettant de poster un commentaire
                    echo '<div class="commentairespost" id="commentairespost'.$line['id'].'">';
                    formajoutcommentaire($line['id'],$idPers);
                    //On affiche les commentaires
                    $sql1="SELECT nom, prenom, avatar, commentaires.id, commentaires.commentaire, commentaires.idAuteur, DATE_FORMAT(dateCommentaire, 'Le %d/%m/%Y à %Hh%i') AS dateCommentaire FROM utilisateurs JOIN commentaires ON commentaires.idAuteur=utilisateurs.id WHERE commentaires.idPost=? ORDER BY commentaires.id ASC";
                    $query1 = $pdo->prepare($sql1);
                    $query1->execute(array($line['id']));
                    while($line1 = $query1->fetch()){
                        echo '<div class="comm" id="comm'.$line1['id'].'">
                        <div class="auteur"><div><a href="mur-'.$line1['idAuteur'].'"><img class="imgpost" src="avatars/'.$line1['avatar'].'">
                            <div><p>'.$line1['prenom'].' '.$line1['nom'].'</p></a><p>'.$line1['dateCommentaire'].'</p></div></div>
                            <div>';
                            if($line1['idAuteur']==$_SESSION['id']){
                                formsupprimercommentaire($line['id'],$line1['id']);
                            }
                            echo '</div>
                            </div>
                            <p>'.$line1['commentaire'].'</p>';
                        echo '</div>';
                    }        
                    echo'</div></div>';
                };
                echo '</div></div>';
            
            }//Fin du else "Si on est amis avec la personne"
        }
        ?>  
    </div>
</div>
<div id="script"></div>
<script src="./js/script.js"></script>