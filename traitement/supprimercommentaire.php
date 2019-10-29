<?php
if (isset($_POST['idCommentaire']) && isset($_POST['commentaire']) && isset($_SESSION['id'])){
    $idCommentaire=htmlspecialchars($_POST['idCommentaire']);
    $commentaire=htmlspecialchars($_POST['commentaire']);
    $monid=htmlspecialchars($_SESSION['id']);

    $sql = 'DELETE FROM commentaires WHERE id=? AND commentaire=? AND idAuteur=?';
    // Etape 1  : preparation
    try{
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($idCommentaire,$commentaire,$monid));
        if(isset($_POST['idredirection'])){
            $redir=$_POST['idredirection'];
            header("Location: index.php?action=mur&id=".$redir);
        }else{
            header("Location: index.php?action=mur");
        }

    } catch (Exception $e) {
        $_SESSION['erreur'] = 'Erreur lors de la suppression du comentaire.';
        }
}else{
    header("Location: index.php?action=mur");
}