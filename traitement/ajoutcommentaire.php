<?php
if(isset($_POST['comm']) && !empty($_POST['comm']) && isset($_POST['idpost']) && !empty($_POST['idpost']) && isset($_SESSION['id']) && !empty($_SESSION['id'])){
    $monid=$_SESSION['id'];
    $idPost=$_POST['idpost'];
    $commentaire=htmlspecialchars(addslashes($_POST['comm']));
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO commentaires VALUES ('','$commentaire','$idPost','$monid','$date')";
    $query = $pdo->prepare($sql);
    $query->execute();
    header('Location: ./index.php?action=mur');

}else{
    $_SESSION['alertecomm']='Impossible de poster le commentaire';
    header("Location: index.php?action=mur");
}



?>