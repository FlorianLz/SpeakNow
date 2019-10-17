<?php
if(isset($_POST['message']) && !empty($_POST['message']) && isset($_POST['titre']) && !empty($_POST['titre'])) {
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
    header('Location: index.php?action=mur');
}



?>