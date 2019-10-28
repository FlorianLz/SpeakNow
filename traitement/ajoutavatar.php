<?php
session_start();
print_r($_FILES);
echo $_SESSION['id'];
    //On vérifie si une image à été sélectionnée
    if (!empty($_FILES['avatar']['name'])){
        $dossier = '../avatars/';
        $fichier = basename($_FILES['avatar']['name']);
        $taille_maxi = 10000000;
        $taille = filesize($_FILES['avatar']['tmp_name']);
        $extensions = array('.png', '.gif', '.jpg', '.jpeg');
        $extension = strrchr($_FILES['avatar']['name'], '.');
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
             $fichier = $_SESSION['id'].$extension;
  
             if(move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
             {
                  echo 'Upload effectué avec succès !';
                  $_SESSION['avatar']=$fichier;
                  //On insére les données dans la bdd
                  include("../config/config.php");
                  include("../config/bd.php");
                  try{
                    $sql = 'UPDATE utilisateurs SET avatar=? WHERE id=?';
                    $query = $pdo->prepare($sql);
                    $query->execute(array($fichier,$_SESSION['id']));
                    header('Location: ../index.php?action=profil');
                  } catch (Exception $e) {
                    echo 'Erreur lors de l\'importaion de l\'avatar ! ';
                    }
             }
             else //Sinon (la fonction renvoie FALSE).
             {
               $_SESSION['erreur']='Une erreur est survenue durant l\'upload. Merci de réessayer';
               header('Location: ../index.php?action=profil');
             }
        }
        else //S'il y a eu une erreur...
        {
             echo $erreur;
        }
      }else{
          $_SESSION['erreur']='Une erreur est survenue. Merci de réessayer'; 
          header('Location: ../index.php?action=profil');
      }



?>