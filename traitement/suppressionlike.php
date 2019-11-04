<?php

if (isset($_POST['idPost']) && isset($_SESSION['id'])){
    $idPost=htmlspecialchars(addslashes($_POST['idPost']));
    $monid=$_SESSION['id'];
    $sql="DELETE FROM aime WHERE idUtilisateur=? AND idEcrit=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($monid, $idPost));
    if(isset($_POST['idredirection'])){
        $redir=$_POST['idredirection'];
        if(isset($_POST['iPpost'])){
            $idpost=$_POST['idPost'];
            header("Location: index.php?action=mur&id=".$redir."#post".$idpost);
        }else{
            header("Location: index.php?action=mur&id=".$redir);
        }
    }else{
        header("Location: index.php?action=mur");
    }
}else{
    header("Location: index.php?action=mur");

}

?>