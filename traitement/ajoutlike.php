<?php

if (isset($_POST['idPost']) && isset($_SESSION['id'])){
    $idPost=htmlspecialchars(addslashes($_POST['idPost']));
    $monid=$_SESSION['id'];
    $sql="INSERT INTO aime VALUES ('','$idPost','$monid')";
    $query = $pdo->prepare($sql);
    $query->execute();
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