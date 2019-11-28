<?php
session_start();
include("../config/config.php");
include("../config/bd.php");
if(isset($_GET['afaire']) AND $_GET['afaire'] == "refresh"){
    $id=$_SESSION['id'];
    $idPers = $_GET['idAmiMP'];
    $sqlmp="SELECT prives.*, nom,prenom,avatar FROM prives JOIN utilisateurs ON utilisateurs.id=? WHERE (idAuteurMP=? AND idAmiMP=?) OR (idAuteurMP=? AND idAmiMP=?) ORDER BY prives.id ASC";
    $querymp = $pdo->prepare($sqlmp);
    $querymp->execute(array($idPers,$id,$idPers,$idPers,$id));
    $texte = ''; 
    while($linemp = $querymp->fetch()){
        if($linemp['idAuteurMP'] == $id){
            $texte .='<div class="mp droite"><p>'.$linemp['contenuMP'].'</p></div><br>';
        }else{
            $texte .='<div class="mp gauche"><p>'.$linemp['contenuMP'].'</p></div><br>';
        }
    }
    if($texte != ""){
        echo $texte;
    }else{
        echo "Vous n'avez pas encore envoyé de messages à cet ami !";
    }
}
elseif(isset($_GET['afaire']) AND isset($_GET['idAmiMP']) AND isset($_GET['message']) AND $_GET['idAmiMP'] != "" AND $_GET['message'] != "" AND $_GET['afaire'] == "envoi" ){
    $idsession=$_SESSION['id'];
    $idAmiMP=$_GET['idAmiMP'];
    $message  = addslashes(htmlspecialchars($_GET['message']));
    
    $sql = "INSERT INTO prives VALUES (NULL,'$idsession','$idAmiMP','$message', NOW())";
    $query = $pdo->prepare($sql);
    $query->execute();
    echo "Message envoyé";
    
    }else echo "Le message n'a pas pu être envoyé";
    
    ?>