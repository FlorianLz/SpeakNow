<?php

if (isset($_POST['idPost']) && isset($_SESSION['id'])){
    $idPost=htmlspecialchars(addslashes($_POST['idPost']));
    $monid=$_SESSION['id'];
    $sql="DELETE FROM aime WHERE idUtilisateur=? AND idEcrit=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($monid, $idPost));
    if(isset($_POST['idredirection'])){
        $redir=$_POST['idredirection'];
        header("Location: index.php?action=mur&id=".$redir."#post".$idPost);
    }else if(isset($_POST['filredirection']) && $_POST['filredirection'] == "ok"){
            header("Location: index.php?action=fil#post".$idPost);
    }else{
        header("Location: index.php?action=mur");
    }
}else{
    header("Location: index.php?action=mur");

}

?>