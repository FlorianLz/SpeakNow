<?php
if(isset($_POST['comm']) && !empty($_POST['comm']) && isset($_POST['idpost']) && !empty($_POST['idpost']) && isset($_SESSION['id']) && !empty($_SESSION['id'])){
    $monid=$_SESSION['id'];
    $idPost=$_POST['idpost'];
    $commentaire=htmlspecialchars(addslashes($_POST['comm']));
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO commentaires VALUES ('','$commentaire','$idPost','$monid','$date')";
    $query = $pdo->prepare($sql);
    $query->execute();
    if(isset($_POST['idredirection'])){
        $redir=$_POST['idredirection'];
        header("Location: index.php?action=mur&id=".$redir."#post".$idPost);
    }else if(isset($_POST['filredirection']) && $_POST['filredirection'] == "ok"){
        header("Location: index.php?action=fil#post".$idPost);
    }else{
        header("Location: index.php?action=mur");
    }

}else{
    //$_SESSION['alertecomm']='Impossible de poster le commentaire';
    //header("Location: index.php?action=mur");
    print_r($_POST);
}



?>