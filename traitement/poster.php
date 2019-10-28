<?php
if(isset($_POST['message']) && !empty($_POST['message']) && isset($_POST['titre']) && !empty($_POST['titre']) && isset($_POST['idpers']) && !empty($_POST['idpers'])) {
    $monid=$_SESSION['id'];
    $idami=htmlspecialchars($_POST['idpers']);
    $titre=htmlspecialchars(addslashes($_POST['titre']));
    $message = htmlspecialchars(addslashes($_POST['message']));
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO ecrit VALUES ('','$titre','$message','$date','','$monid','$idami')";
    $query = $pdo->prepare($sql);
    $query->execute();

    header('Location: index.php?action=mur&id=' . $idami);
}else{
    if(empty($_POST['message']) || empty($_POST['titre']) && isset($_POST['idpers'])){
        $idami=htmlspecialchars($_POST['idpers']);
        $_SESSION['alerte']='Merci de remplir tous les champs !';
        header('Location: index.php?action=mur&id=' . $idami);
    }
}



?>