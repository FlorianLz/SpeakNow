<?php
if (isset($_POST['id']) && isset($_POST['id']) && isset($_POST['id']) && isset($_POST['id'])){
    $id=htmlspecialchars($_POST['id']);
    $idconnecte=htmlspecialchars($_SESSION['id']);
    $titre=htmlspecialchars($_POST['titre']);
    $contenu=htmlspecialchars($_POST['message']);
    $date=htmlspecialchars($_POST['date']);

    $sql = 'DELETE FROM ecrit WHERE id=? AND titre=? AND contenu=? AND dateEcrit=? AND idAuteur=?';
    // Etape 1  : preparation
    try{
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($id,$titre,$contenu,$date,$idconnecte));
        header("Location: index.php?action=mur");

    } catch (Exception $e) {
        $_SESSION['erreur'] = 'Erreur lors de la suppression du message.';
        }



}

?>