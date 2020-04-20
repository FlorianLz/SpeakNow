<?php
session_start();
if(isset($_POST['comm']) && !empty($_POST['comm']) && isset($_POST['idpost']) && !empty($_POST['idpost']) && isset($_SESSION['id']) && !empty($_SESSION['id'])){
    include('../config/config.php');
    include('../config/bd.php');
    $monid=$_SESSION['id'];
    $idPost=$_POST['idpost'];
    $commentaire=htmlspecialchars(addslashes($_POST['comm']));

    $sql = "INSERT INTO commentaires VALUES (NULL,'$commentaire','$idPost','$monid',NOW())";
    $query = $pdo->prepare($sql);
    $query->execute();

    $lastid=$pdo->lastInsertId();

    //On affiche le nouveau commentaire
    $sql1="SELECT nom, prenom, avatar, commentaires.id, commentaires.commentaire, commentaires.idAuteur, DATE_FORMAT(dateCommentaire, 'Le %d/%m/%Y à %Hh%i') AS dateCommentaire FROM utilisateurs JOIN commentaires ON commentaires.idAuteur=utilisateurs.id WHERE commentaires.id=?";
    $query1 = $pdo->prepare($sql1);
    $query1->execute(array($lastid));
    $line1 = $query1->fetch();
    echo '<div id="comm'.$lastid.'" class="comm" id="comm'.$line1['id'].'">
                        <div class="auteur"><div><a href="index.php?action=mur&id='.$line1['idAuteur'].'"><img class="imgpost" src="avatars/'.$line1['avatar'].'">
                            <div><p>'.$line1['prenom'].' '.$line1['nom'].'</p></a><p>'.$line1['dateCommentaire'].'</p></div></div>
                            <div>';
    if($line1['idAuteur']==$_SESSION['id']){
        echo '<form>
        <label for="supprimercomm'.$line1['id'].'"><i class="fas fa-times"></i></label>
        <input type="submit" value="" id="supprimercomm'.$line1['id'].'" class="inputsupprimercomm" data-idpost="'.$idPost.'" data-idcomm="'.$line1['id'].'">                                
        </form>';
    }
    echo '</div>
                            </div>
                            <p>'.$line1['commentaire'].'</p>';
    echo '</div>';


}else{
    $idPost=$_POST['idpost'];
    $_SESSION['alertecomm'.$idPost]='<p>Merci d\'entrer un commentaire valide !</p>';
    //header("Location: index.php?action=mur");
    if(isset($_POST['murredirection'])){
        $redir=$_POST['murredirection'];
        header("Location: index.php?action=mur&id=".$redir."#post".$idPost);
    }else if(isset($_POST['filredirection'])){
        header("Location: index.php?action=fil#post".$idPost);
    }else{
        header("Location: index.php?action=mur");
    }
}



?>