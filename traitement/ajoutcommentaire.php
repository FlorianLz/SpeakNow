<?php
if(isset($_POST['comm']) && !empty($_POST['comm']) && isset($_POST['idpost']) && !empty($_POST['idpost']) && isset($_SESSION['id']) && !empty($_SESSION['id'])){
    $monid=$_SESSION['id'];
    $idPost=$_POST['idpost'];
    $commentaire=htmlspecialchars(addslashes($_POST['comm']));
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO commentaires VALUES (NULL,'$commentaire','$idPost','$monid','$date')";
    $query = $pdo->prepare($sql);
    $query->execute();
    if(isset($_POST['murredirection'])){
        $redir=$_POST['murredirection'];
        header("Location: index.php?action=mur&id=".$redir."#post".$idPost);
    }else if(isset($_POST['filredirection'])){
        header("Location: index.php?action=fil#post".$idPost);
    }else{
        header("Location: index.php?action=mur");
    }

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