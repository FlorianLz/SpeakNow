<div class="contenumur">
    <div class="demandesami">
        <?php 
        echo '<h1>Demandes reçues</h1>';
        $sql = "SELECT utilisateurs.* FROM utilisateurs WHERE id IN(SELECT idUtilisateur1 FROM lien WHERE idUtilisateur2=? AND etat='attente') ";
        $query = $pdo->prepare($sql);
        $query->execute(array($_SESSION['id']));
        while($line = $query->fetch()){
            echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a></div>';
            echo '<form method="post" action="index.php?action=ajoutami">
                        <input type="hidden" name="idAmi" value="'.$line['id'].'">
                        <input type="submit" value="Accepter">
                        </form>
                        <form method="post" action="index.php?action=refusami">
                        <input type="hidden" name="idAmi" value="'.$line['id'].'">
                        <input type="submit" value="Refuser">
                        </form>';
        }

        echo '<h1>Demandes envoyées</h1>';
        $sql = "SELECT utilisateurs.* FROM utilisateurs INNER JOIN lien ON utilisateurs.id=idUtilisateur2 AND etat='attente' AND idUtilisateur1=?";
        $query = $pdo->prepare($sql);
        $query->execute(array($_SESSION['id']));
        while($line = $query->fetch()){
            echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a></div>';
            echo '<form method="post" action="index.php?action=annulerajout">
                        <input type="hidden" name="idAmi" value="'.$line['id'].'">
                        <input type="submit" value="Annuler">
                        </form>';
        }
    
    ?>
    </div>
    <div class="filactu">
    <?php

        if(!isset($_SESSION["id"])) {
            // On n est pas connecté, il faut retourner à la page de login
            header("Location:index.php?action=login");
        }

        // On veut affchier notre mur ou celui d'un de nos amis et pas faire n'importe quoi
        $ok = false;

        if(!isset($_GET["id"]) || ($_GET["id"]==$_SESSION["id"])){
            $id = $_SESSION["id"];
            $ok = true; // On a le droit d afficher notre mur

            echo '<h1>Bienvenue sur ton mur '.$_SESSION['prenom'].' !</h1>';

            ?>
            <div class="poster">
                <form class="formposter" action="index.php?action=poster" method="post">
                    <h3>Nouvelle publication</h3>
                    <input type="text" name="titre" placeholder="Titre...">
                    <textarea name="message" placeholder="Message..."></textarea>
                    <input type="submit">
                </form>
            </div> 
            <?php

            $sql="SELECT * FROM ecrit WHERE idAuteur=? ORDER BY dateEcrit DESC";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id']));

            echo '<div class="conteneurposts">';
            while($line = $query->fetch()){
                echo '<div class="postmur">
                        <div class="auteur"><img class="imgpost" src="avatars/'.$_SESSION['avatar'].'"><p>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</p>
                        </div>
                        <p class="titrepost">'.$line['titre'].'</p><br>';
                echo $line['contenu'];
                echo '<br><br>';
                echo 'Posté le '.$line['dateEcrit'];
                echo '<form method="post" action="index.php?action=supprimerpost">
                    <input type="hidden" name="id" value="'.$line['id'].'">
                    <input type="hidden" name="titre" value="'.$line['titre'].'">
                    <input type="hidden" name="message" value="'.$line['contenu'].'">
                    <input type="hidden" name="date" value="'.$line['dateEcrit'].'">
                    <input type="submit" value="Supprimer">
                    </form>';
                echo '</div>';
            };
            echo '</div>';

        } else {
            $id=$_SESSION['id'];
            $idPers = $_GET['id'];
            // Verifions si on est amis avec cette personne
            $sql = "SELECT * FROM lien WHERE etat='ami'AND ((idUtilisateur1=? AND idUtilisateur2=?) OR ((idUtilisateur1=? AND idUtilisateur2=?)))";
            $query = $pdo->prepare($sql);
            $query->execute(array($id,$idPers,$idPers,$id));
            $line = $query->fetch();
            if($line == true){
                $ok=true;
            }
            // les deux ids à tester sont : $_GET["id"] et $_SESSION["id"]
            // A completer. Il faut récupérer une ligne, si il y en a pas ca veut dire que lon est pas ami avec cette personne
        }

        if($ok==false) {
            echo "Vous n êtes pas encore ami, vous ne pouvez voir son mur !!";       
        } else {
        // A completer
        // Requête de sélection des éléments dun mur
        // SELECT * FROM ecrit WHERE idAmi=? order by dateEcrit DESC
        // le paramètre  est le $id

        //je récupère les infos de l'auteur
        if(isset($_GET['id'])){
            $sql="SELECT * FROM utilisateurs  WHERE id=?";
            $query = $pdo->prepare($sql);
            $query->execute(array($idPers));
            $line = $query->fetch();
            $nomPers=$line['nom'];
            $prenomPers=$line['prenom'];
            $avatarPers=$line['avatar'];
            ?>
            <div class="poster">
                <form class="formposter" action="index.php?action=poster" method="post">
                    <h3>Ecrire un message à <?php echo $prenomPers; ?></h3>
                    <input type="hidden" name="idpers" value="<?php echo $_GET['id'];?>">
                    <input type="text" name="titre" placeholder="Titre...">
                    <textarea name="message" placeholder="Message..."></textarea>
                    <input type="submit">
                </form>
            </div> 

            <?php

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
                echo 'Posté le '.$line['dateEcrit'];
                echo '</div>';
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
                    echo 'Posté le '.$line['dateEcrit'];
                    if($infos['id'] == $_SESSION['id']){
                        echo '<form method="post" action="index.php?action=supprimerpost&idredirection='.$_GET['id'].'">
                        <input type="hidden" name="id" value="'.$line['id'].'">
                        <input type="hidden" name="titre" value="'.$line['titre'].'">
                        <input type="hidden" name="message" value="'.$line['contenu'].'">
                        <input type="hidden" name="date" value="'.$line['dateEcrit'].'">
                        <input type="submit" value="Supprimer">
                        </form>';
                    }
                    echo '</div>';

                }
                
            };
            echo '</div>';
            }
        
        }
    ?>  
    </div>

    <div class="listeamis">
    <?php 
    echo '<h1>Liste de mes amis</h1>';
    $sql = "SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUTilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUTilisateur1=?)";
    $query = $pdo->prepare($sql);
    $query->execute(array($_SESSION['id'],$_SESSION['id']));
    while($line = $query->fetch()){
        echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a></div>';
    }
    
    ?>
    </div>
</div>
