<?php
session_start();
if (isset($_POST['idCommentaire']) && isset($_POST['idPost']) && isset($_SESSION['id'])){
    include('../config/config.php');
    include('../config/bd.php');
    $idCommentaire=htmlspecialchars($_POST['idCommentaire']);
    $monid=htmlspecialchars($_SESSION['id']);
    $idPost=htmlspecialchars($_POST['idPost']);

    $sql = 'DELETE FROM commentaires WHERE id=? AND idAuteur=? AND idPost=?';
    // Etape 1  : preparation
    try{
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($idCommentaire,$monid,$idPost));
        echo 'ok';
    } catch (Exception $e) {
        echo 'erreur';
        }
}else{
    header("Location: index.php?action=mur");
}