<?php

    if(!isset($_SESSION["id"])) {
        // On n est pas connecté, il faut retourner à la pgae de login
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
            echo '<br>';
            echo 'Posté le '.$line['dateEcrit'];
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

        $sql="SELECT * FROM ecrit  WHERE idAuteur=? ORDER BY dateEcrit DESC";
        $query = $pdo->prepare($sql);
        $query->execute(array($idPers));
        echo '<h1>Profil de '.$prenomPers.' '.$nomPers.'</h1>';
        echo '<div class="conteneurposts">';
        while($line = $query->fetch()){
            echo '<div class="postmur">
                    <div class="auteur"><img class="imgpost" src="avatars/'.$avatarPers.'"><p>'.$prenomPers.' '.$nomPers.'</p>
                    </div>
                    <p class="titrepost">'.$line['titre'].'</p><br>';
            echo $line['contenu'];
            echo '<br>';
            echo 'Posté le '.$line['dateEcrit'];
            echo '</div>';
        };
        echo '</div>';
        }
    
    }
?>  
