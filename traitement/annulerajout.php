<?php
    if (isset($_POST['idAmi']) && !empty($_POST['idAmi']) && isset($_SESSION['id'])){
        $idAmi=htmlspecialchars($_POST['idAmi']);
        $monid=htmlspecialchars($_SESSION['id']);

        $sql = "DELETE FROM lien WHERE idUtilisateur1=? AND idUtilisateur2=? AND etat='attente'";
        // Etape 1  : preparation
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($monid,$idAmi));
        // Etape 3 : ici le login est unique, donc on sait que l'on peut avoir zero ou une  seule ligne.
        if(isset($_POST['texterecherche'])){
            $texterecherche=$_POST['texterecherche'];
            header('Location: index.php?action=recherche&texterecherche='.$texterecherche);
        }else{
            header("Location: index.php?action=mur");
        }
        
    }else{
        header("Location: index.php?action=mur");
    }


?>