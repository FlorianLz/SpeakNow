<?php
if(isset($_POST['message']) && !empty($_POST['message']) && isset($_POST['titre']) && !empty($_POST['titre']) && !isset($_POST['idpers'])) {
    $id=$_SESSION['id'];
    $titre=htmlspecialchars(addslashes($_POST['titre']));
    $message = htmlspecialchars(addslashes($_POST['message']));
    echo $message;
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO ecrit VALUES ('','$titre','$message','$date','','$id','$id')";
    $query = $pdo->prepare($sql);
    $query->execute();

    header('Location: index.php?action=mur');
}else{
    $monid=$_SESSION['id'];
    $idami=htmlspecialchars($_POST['idpers']);
    $titre=htmlspecialchars(addslashes($_POST['titre']));
    $message = htmlspecialchars(addslashes($_POST['message']));
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO ecrit VALUES ('','$titre','$message','$date','','$monid','$idami')";
    $query = $pdo->prepare($sql);
    $query->execute();

    header('Location: index.php?action=mur&id=' . $idami);
}



?>