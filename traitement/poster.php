<?php

if(isset($_POST['message']) && !empty($_POST['message']) && isset($_POST['titre']) && !empty($_POST['titre']) && isset($_POST['idpers']) && !empty($_POST['idpers'])) {
    $monid=$_SESSION['id'];
    $idami=htmlspecialchars($_POST['idpers']);
    $titre=htmlspecialchars(addslashes($_POST['titre']));
    $message = htmlspecialchars(addslashes($_POST['message']));
    date_default_timezone_set('Europe/Paris');
    $date = date("Y-m-d H:i:s");
    print_r($_FILES);

    if (!empty($_FILES['photo']['name'])){
        $dossier = './imagesposts/';
        $fichier = basename($_FILES['photo']['name']);
        $taille_maxi = 10000000;
        $taille = filesize($_FILES['photo']['tmp_name']);
        $extensions = array('.png', '.gif', '.jpg', '.jpeg');
        $extension = strrchr($_FILES['photo']['name'], '.');
        //Début des vérifications de sécurité...
        if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
        {
             $erreur = 'Vous devez uploader un fichier de type png, gif, jpg ou jpeg...';
        }
        if($taille>$taille_maxi)
        {
             $erreur = 'Le fichier est trop gros...';
        }
        if(!isset($erreur)){ //S'il n'y a pas d'erreur, on upload
             //On formate le nom du fichier ici...
             $fichier = $monid.'_'.time().$extension;
  
             if(move_uploaded_file($_FILES['photo']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
             {
                  echo 'Upload effectué avec succès !';
                  //On insére les données dans la bdd
                  include("../config/config.php");
                  include("../config/bd.php");
                  try{
                    $sql = "INSERT INTO ecrit VALUES ('','$titre','$message','$date','$fichier','$monid','$idami')";
                    $query = $pdo->prepare($sql);
                    $query->execute();
                    header('Location: ./index.php?action=mur&id=' . $idami);
                  } catch (Exception $e) {
                    echo 'Erreur lors de l\'importaion de la photo ! ';
                    }
             }
             else //Sinon (la fonction renvoie FALSE).
             {
               $_SESSION['alerte']='Erreur lors de l\'importation de l\'image';

             }
        }
        else //S'il y a eu une erreur...
        {
             $_SESSION['alerte']=$erreur;
             header('Location: ./index.php?action=mur&id=' . $idami);
        }
      }else{
        $sql = "INSERT INTO ecrit VALUES (NULL,'$titre','$message','$date',NULL,'$monid','$idami')";
        $query = $pdo->prepare($sql);
        $query->execute();
        header('Location: ./index.php?action=mur&id=' . $idami);
      }
}else{
    if(empty($_POST['message']) || empty($_POST['titre']) && isset($_POST['idpers'])){
        $idami=htmlspecialchars($_POST['idpers']);
        $_SESSION['alerte']='Merci de remplir tous les champs !';
        header('Location: ./index.php?action=mur&id=' . $idami);
    }
}



?>