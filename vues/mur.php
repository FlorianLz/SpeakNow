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
    <?php

        if(!isset($_SESSION["id"])) {
            // On n est pas connecté, il faut retourner à la page de login
            header("Location:index.php?action=accueil");
        }

        // On veut afficher notre mur ou celui d'un de nos amis et pas faire n'importe quoi
        $ok = false;

        if(!isset($_GET["id"]) || ($_GET["id"]==$_SESSION["id"])){ //Il n'y as pas d'id en GET ou celui-ci est celui de la session
            $id = $_SESSION["id"];
            $ok = true; // On a le droit d afficher notre mur

            echo '<div class="profil"><div class="imgprofil"><img src=avatars/'.$_SESSION['avatar'].'></div><div class="infoprofil">';
            echo '<h2>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</h2></div></div>';

            ?>
            <!-- Formulaire permettant de poster sur SON mur-->
            <div class="poster">
                <form enctype="multipart/form-data" class="formposter" action="index.php?action=poster" method="post">
                    <h3>Nouvelle publication</h3>
                    <input type="text" name="titre" placeholder="Titre...">
                    <input type="hidden" name="idpers" value="<?php echo $_SESSION['id'];?>">
                    <textarea name="message" placeholder="Message..."></textarea>
                    <div class="uploadimage">
                        <label class="uploadfile" for="image"><i class="fas fa-image"></i></label>
                        <div class="cacherbtnfile">
                            <input type="file" name="photo" id="image" class="inputfile">
                        </div>
                    </div>
                    <input type="submit">
                </form>
            </div> 

            <?php
            if(isset($_SESSION['alerte'])){
                echo '<p>'.$_SESSION['alerte'].'</p>';
                unset($_SESSION['alerte']);
            }
            //Requête permettant de sélectionner tous les commentaires qui nous concerne
            $sql="SELECT * FROM ecrit WHERE idAmi=? ORDER BY dateEcrit DESC";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id']));
            //On affiche un conteneur pour les posts
            echo '<div class="conteneurposts">';
            //Pour chaque post, on crée une div
            while($line = $query->fetch()){
                //Si l'auteur du post correspond à notre SESSION id
                if($line['idAuteur']==$_SESSION['id']){
                    echo '<div class="postmur">
                        <div class="auteur"><img class="imgpost" src="avatars/'.$_SESSION['avatar'].'"><p>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</p>
                        </div>
                        <p class="titrepost">'.$line['titre'].'</p><br>';
                    echo $line['contenu'].'<br><br>';
                    if(isset($line['image']) && !empty($line['image'])){
                        echo '<img src="./imagesposts/'.$line['image'].'">';
                    }
                    echo '<p>Posté le '.$line['dateEcrit'].'</p>';
                    echo '<form method="post" action="index.php?action=supprimerpost">
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
                    echo '<div class="commentairespost">
                            <form method="post" action="index.php?action=ajoutcommentaire">
                                <img class="imgpost" src="avatars/'.$_SESSION['avatar'].'">
                                <textarea name="comm" placeholder="Votre commentaire..."></textarea>
                                <input type="hidden" name="idpost" value="'.$line['id'].'">
                                <input type="submit" value="" name="submit" id="submit"><label for="submit"><i class="fas fa-paper-plane"></i></label>
                            </form>';
                    if(isset($_SESSION['alertecomm'])){
                        echo ($_SESSION['alertecomm']);
                        unset($_SESSION['alertecomm']);
                    }

                    $sqlcomm="SELECT * FROM commentaires WHERE idPost=? ORDER BY dateCommentaire DESC";
                    $querycomm = $pdo->prepare($sqlcomm);
                    $querycomm->execute(array($line['id']));
                    while($linecomm = $querycomm->fetch()){

                        $sqlcomm1="SELECT * FROM utilisateurs  WHERE id=?";
                        $querycomm1 = $pdo->prepare($sqlcomm1);
                        $querycomm1->execute(array($linecomm['idAuteur']));
                        $infoscomm=$querycomm1->fetch();

                        $nomauteurcomm=$infoscomm['nom'];
                        $prenomauteurcomm=$infoscomm['prenom'];
                        $avatarauteurcomm=$infoscomm['avatar'];
                        echo '<div class="comm"><div class="auteur"><img class="imgpost" src="avatars/'.$avatarauteurcomm.'"><p>'.$prenomauteurcomm.' '.$nomauteurcomm.'</p>
                            </div><p>'.$linecomm['commentaire'].'</p>';
                        if($linecomm['idAuteur']==$_SESSION['id']){
                            echo '<form method="post" action="index.php?action=supprimercommentaire">
                                <input type="hidden" name="idCommentaire" value="'.$linecomm['id'].'">
                                <input type="hidden" name="commentaire" value="'.$linecomm['commentaire'].'">
                                <input type="hidden" name="idredirection" value="'.$_SESSION['id'].'">
                                <label for="supprimercomm"><i class="fas fa-times"></i></label>
                                <input type="submit" value="" id="supprimercomm">                                
                                </form>';
                        }
                        echo '</div><br>';
                    }
                    echo '</div></div>';
                }else{ //Si l'auteur du post ne correspond pas à notre SESSION id = un ami qui a publié sur notre mur
                    //On récupère les infos de cet ami
                    $sql1="SELECT * FROM utilisateurs  WHERE id=?";
                    $query1 = $pdo->prepare($sql1);
                    $query1->execute(array($line['idAuteur']));
                    $infos=$query1->fetch();

                    $nomauteur=$infos['nom'];
                    $prenomauteur=$infos['prenom'];
                    $avatarauteur=$infos['avatar'];
                    //On affiche une div par post
                    echo '<div class="postmur">
                        <div class="auteur"><img class="imgpost" src="avatars/'.$avatarauteur.'"><p>'.$prenomauteur.' '.$nomauteur.'</p>
                        </div>
                        <p class="titrepost">'.$line['titre'].'</p><br>';
                    echo $line['contenu'];
                    echo '<br><br>';
                    //Une image est liée au post ? On l'affiche
                    if(isset($line['image']) && !empty($line['image'])){
                        echo '<img src="./imagesposts/'.$line['image'].'">';
                    }
                    //On affiche la date du post
                    echo 'Posté le '.$line['dateEcrit'];
                    //On affiche les commentaires liés au post
                    echo '<div class="commentairespost">
                            <form method="post" action="index.php?action=ajoutcommentaire">
                                <img class="imgpost" src="avatars/'.$_SESSION['avatar'].'">
                                <textarea name="comm" placeholder="Votre commentaire..."></textarea>
                                <input type="hidden" name="idpost" value="'.$line['id'].'">
                                <input type="submit" value="" name="submit" id="submit"><label for="submit"><i class="fas fa-paper-plane"></i></label>
                            </form>';
                        if(isset($_SESSION['alertecomm'])){
                            echo $_SESSION['alertecomm'];
                            unset($_SESSION['alertecomm']);
                        }
                        //Requête permettant de sélectionner tous les commentaires liés au post
                        $sqlcomm="SELECT * FROM commentaires WHERE idPost=? ORDER BY dateCommentaire DESC";
                        $querycomm = $pdo->prepare($sqlcomm);
                        $querycomm->execute(array($line['id']));
                        while($linecomm = $querycomm->fetch()){
                        //On récupère les infos de l'auteur
                            $sqlcomm1="SELECT * FROM utilisateurs  WHERE id=?";
                            $querycomm1 = $pdo->prepare($sqlcomm1);
                            $querycomm1->execute(array($linecomm['idAuteur']));
                            $infoscomm=$querycomm1->fetch();
                            $nomauteurcomm=$infoscomm['nom'];
                            $prenomauteurcomm=$infoscomm['prenom'];
                            $avatarauteurcomm=$infoscomm['avatar'];
                            //On affiche le commentaire
                            echo '<div class="comm"><div class="auteur"><img class="imgpost" src="avatars/'.$avatarauteurcomm.'"><p>'.$prenomauteurcomm.' '.$nomauteurcomm.'</p>
                            </div><p>'.$linecomm['commentaire'].'</p>';
                            if($linecomm['idAuteur']==$_SESSION['id']){
                                echo '<form method="post" action="index.php?action=supprimercommentaire">
                                <input type="hidden" name="idCommentaire" value="'.$linecomm['id'].'">
                                <input type="hidden" name="commentaire" value="'.$linecomm['commentaire'].'">
                                <input type="hidden" name="idredirection" value="'.$_SESSION['id'].'">
                                <label for="supprimercomm"><i class="fas fa-times"></i></label>
                                <input type="submit" value="" id="supprimercomm">                                
                                </form>';
                            }
                            
                            echo'</div><br>';
                        }    
                    echo '</div></div>';

                }
                
            };
            echo '</div>';

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
        //Si on est pas amis avec la personne ($ok=false)
        if($ok==false) {
            //On récupère les infos de la personne
            $infospers='SELECT * FROM utilisateurs WHERE id=?';
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
                        echo '<form method="post" action="index.php?action=annulerajout">
                        <input type="hidden" name="idAmi" value="'.$_GET['id'].'">
                        <input type="hidden" name="idpage" value="'.$_GET['id'].'">
                        <input type="submit" value="Annuler">
                        </form></div></div></div>';
                    }else{
                        //Sinon c'est qu'elle nous a demandé en ami
                        echo '<p> Vous a demandé en ami</p></div></div></div>';
                    }
                            
                }
                //Si l'état est à banni, c'est qu'une dès 2 pers à banni l'autre
                if ($line['etat'] == 'banni'){
                    echo '<p> Utilisateur Banni</p></div></div></div>';
                }
            //Si aucun état, c'est que nous ne sommes pas encore amis avec la personne
            }else{
                echo "Vous n êtes pas encore ami, vous ne pouvez voir son mur !!";
                echo '<form action="index.php?action=demandeami" method="POST"><input type="hidden" name="idAmi" value="'.$_GET['id'].'"><input type="hidden" name="idpage" value="'.$_GET['id'].'"><input type="submit" value="Ajouter"></form></div></div></div>';                        
            }       
        } else { //Si on est amis avec la personne
        //Je récupère les infos de la personne
        if(isset($_GET['id']) && $_GET['id'] != $_SESSION['id']){
            $sql="SELECT * FROM utilisateurs  WHERE id=?";
            $query = $pdo->prepare($sql);
            $query->execute(array($idPers));
            $line = $query->fetch();
            $nomPers=$line['nom'];
            $prenomPers=$line['prenom'];
            $avatarPers=$line['avatar'];
            echo '<div class="profil"><div class="imgprofil"><img src=avatars/'.$avatarPers.'></div><div class="infoprofil">';
            echo '<h2>'.$prenomPers.' '.$nomPers.'</h2>';
            echo '<form method="post" action="index.php?action=refusami">
            <input type="hidden" name="idAmi" value="'.$idPers.'">
            <input type="submit" value="Supprimer cet ami">
            </form></div></div>';
            ?>
            <!-- On affiche le formulaire permettant de mettre un post sur son mur -->
            <div class="poster">
                <form enctype="multipart/form-data" class="formposter" action="index.php?action=poster" method="post">
                    <h3>Ecrire un message à <?php echo $prenomPers; ?></h3>
                    <input type="hidden" name="idpers" value="<?php echo $_GET['id'];?>">
                    <input type="text" name="titre" placeholder="Titre...">
                    <textarea name="message" placeholder="Message..."></textarea>
                    <div class="uploadimage">
                        <label class="uploadfile" for="image"><i class="fas fa-image"></i></label>
                        <div class="cacherbtnfile">
                            <input type="file" name="photo" id="image" class="inputfile">
                        </div>
                    </div>
                    <input type="submit">
                </form>
            </div> 

            <?php
            if(isset($_SESSION['alerte'])){
                echo '<p>'.$_SESSION['alerte'].'</p>';
                unset($_SESSION['alerte']);
            }

            $sql="SELECT * FROM ecrit  WHERE idAmi=? ORDER BY dateEcrit DESC";
            $query = $pdo->prepare($sql);
            $query->execute(array($idPers));
            echo '<div class="conteneurposts">';
            while($line = $query->fetch()){
                if ($line['idAuteur']==$idPers){
                    echo '<div class="postmur">
                        <div class="auteur"><img class="imgpost" src="avatars/'.$avatarPers.'"><p>'.$prenomPers.' '.$nomPers.'</p>
                        </div>
                        <p class="titrepost">'.$line['titre'].'</p><br>';
                echo $line['contenu'];
                echo '<br><br>';
                if(isset($line['image']) && !empty($line['image'])){
                    echo '<img src="./imagesposts/'.$line['image'].'">';
                }
                echo 'Posté le '.$line['dateEcrit'];
                echo '<div class="commentairespost">
                            <form method="post" action="index.php?action=ajoutcommentaire">
                                <img class="imgpost" src="avatars/'.$_SESSION['avatar'].'">
                                <textarea name="comm" placeholder="Votre commentaire..."></textarea>
                                <input type="hidden" name="idpost" value="'.$line['id'].'">
                                <input type="submit" value="" name="submit" id="submit"><label for="submit"><i class="fas fa-paper-plane"></i></label>
                            </form>';
                    if(isset($_SESSION['alertecomm'])){
                        echo $_SESSION['alertecomm'];
                        unset($_SESSION['alertecomm']);
                    }
                    $sqlcomm="SELECT * FROM commentaires WHERE idPost=? ORDER BY dateCommentaire DESC";
                    $querycomm = $pdo->prepare($sqlcomm);
                    $querycomm->execute(array($line['id']));
                    while($linecomm = $querycomm->fetch()){
                        if($linecomm['idAuteur']==$_SESSION['id']){
                            echo '<div class="comm"><div class="auteur"><img class="imgpost" src="avatars/'.$_SESSION['avatar'].'"><p>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</p>
                            </div><p>'.$linecomm['commentaire'].'</p></div><br>';
                        }else{
                            $sqlcomm1="SELECT * FROM utilisateurs  WHERE id=?";
                            $querycomm1 = $pdo->prepare($sqlcomm1);
                            $querycomm1->execute(array($linecomm['idAuteur']));
                            $infoscomm=$querycomm1->fetch();

                            $nomauteurcomm=$infoscomm['nom'];
                            $prenomauteurcomm=$infoscomm['prenom'];
                            $avatarauteurcomm=$infoscomm['avatar'];
                            echo '<div class="comm"><div class="auteur"><img class="imgpost" src="avatars/'.$avatarauteurcomm.'"><p>'.$prenomauteurcomm.' '.$nomauteurcomm.'</p>
                            </div><p>'.$linecomm['commentaire'].'</p></div><br>';
                        }
                    }
                echo '</div></div>';
                
                }else{
                    $sql1="SELECT * FROM utilisateurs  WHERE id=?";
                    $query1 = $pdo->prepare($sql1);
                    $query1->execute(array($line['idAuteur']));
                    $infos=$query1->fetch();

                    $nomauteur=$infos['nom'];
                    $prenomauteur=$infos['prenom'];
                    $avatarauteur=$infos['avatar'];

                    echo '<div class="postmur">
                        <div class="auteur"><img class="imgpost" src="avatars/'.$avatarauteur.'"><p>'.$prenomauteur.' '.$nomauteur.'</p>
                        </div>
                        <p class="titrepost">'.$line['titre'].'</p><br>';
                    echo $line['contenu'];
                    echo '<br><br>';
                    if(isset($line['image']) && !empty($line['image'])){
                        echo '<img src="./imagesposts/'.$line['image'].'">';
                    }
                    echo 'Posté le '.$line['dateEcrit'];
                    if($infos['id'] == $_SESSION['id']){
                        echo '<form method="post" action="index.php?action=supprimerpost&idredirection='.$_GET['id'].'">
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
                    }
                    echo '<div class="commentairespost">
                            <form method="post" action="index.php?action=ajoutcommentaire">
                                <img class="imgpost" src="avatars/'.$_SESSION['avatar'].'">
                                <textarea name="comm" placeholder="Votre commentaire..."></textarea>
                                <input type="hidden" name="idpost" value="'.$line['id'].'">
                                <input type="submit" value="" name="submit" id="submit"><label for="submit"><i class="fas fa-paper-plane"></i></label>
                            </form>';
                    if(isset($_SESSION['alertecomm'])){
                        echo $_SESSION['alertecomm'];
                        unset($_SESSION['alertecomm']);
                    }
                    $sqlcomm="SELECT * FROM commentaires WHERE idPost=? ORDER BY dateCommentaire DESC";
                    $querycomm = $pdo->prepare($sqlcomm);
                    $querycomm->execute(array($line['id']));
                    while($linecomm = $querycomm->fetch()){
                        if($linecomm['idAuteur']==$_SESSION['id']){
                            echo '<div class="comm"><div class="auteur"><img class="imgpost" src="avatars/'.$_SESSION['avatar'].'"><p>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</p>
                            </div><p>'.$linecomm['commentaire'].'</p></div><br>';
                        }else{
                            $sqlcomm1="SELECT * FROM utilisateurs  WHERE id=?";
                            $querycomm1 = $pdo->prepare($sqlcomm1);
                            $querycomm1->execute(array($linecomm['idAuteur']));
                            $infoscomm=$querycomm1->fetch();

                            $nomauteurcomm=$infoscomm['nom'];
                            $prenomauteurcomm=$infoscomm['prenom'];
                            $avatarauteurcomm=$infoscomm['avatar'];
                            echo '<div class="comm"><div class="auteur"><img class="imgpost" src="avatars/'.$avatarauteurcomm.'"><p>'.$prenomauteurcomm.' '.$nomauteurcomm.'</p>
                            </div><p>'.$linecomm['commentaire'].'</p></div><br>';
                        }
                    }
                echo '</div></div>';

                }
                
            };
            echo '</div>';
            }
        
        }//Fin du else "Si on est amis avec la personne"
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
