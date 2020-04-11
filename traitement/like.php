<?php
session_start();
//Ajout
if (isset($_POST['idPost']) && isset($_SESSION['id'])){
    include('../config/config.php');
    include('../config/bd.php');

    $idPost=htmlspecialchars(addslashes($_POST['idPost']));
    $monid=$_SESSION['id'];

    //On vérifie si j'aime déjà la publication ou non

    $sql="SELECT id FROM aime WHERE idEcrit=? and idUtilisateur=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($idPost,$monid));
    $count=$query->rowCount();

    if($count == 0){
        $sqladd="INSERT INTO aime VALUES (NULL,'$idPost','$monid')";
        $queryadd = $pdo->prepare($sqladd);
        $queryadd->execute();

        $sql="SELECT id FROM aime WHERE idEcrit=?";
        $query = $pdo->prepare($sql);
        $query->execute(array($idPost));
        $count=$query->rowCount();

        echo "<i class='fas fa-thumbs-up boutonlike'>$count</i>";
    }else{
        $sqldelete="DELETE FROM aime WHERE idUtilisateur=? AND idEcrit=?";
        $querydelete = $pdo->prepare($sqldelete);
        $querydelete->execute(array($monid, $idPost));

        $sql="SELECT id FROM aime WHERE idEcrit=?";
        $query = $pdo->prepare($sql);
        $query->execute(array($idPost));
        $count=$query->rowCount();

        echo "<i class='fas fa-thumbs-up boutonpaslike'>$count</i>";
    }
}
?>