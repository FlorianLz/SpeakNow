<?php
    if (isset($_POST['idAmi']) && !empty($_POST['idAmi']) && !empty($_SESSION['id'])){
        $idAmi=htmlspecialchars($_POST['idAmi']);
        $monid=$_SESSION['id'];
        $texterecherche=htmlspecialchars($_POST['texterecherche']);
        $sql='INSERT INTO lien VALUES(NULL,?,?,"attente")';
        $query = $pdo->prepare($sql);
        $query->execute(array($monid,$idAmi));

        header('Location: index.php?action=recherche&texterecherche='.$texterecherche);
    }else{
        echo 'erreur';
    }

?>