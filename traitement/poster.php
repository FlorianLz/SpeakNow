<?php
if(isset($_POST['message']) && !empty($_POST['message'])){
    $id=$_SESSION['id'];
    $message = $_POST['message'];
    echo $message;
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO ecrit VALUES ('','titre','$message','$date','','$id','$id')";
    $query = $pdo->prepare($sql);
    $query->execute();

    header('Location: index.php?action=mur');
}



?>