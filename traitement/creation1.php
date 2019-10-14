<?php
if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['password']) && !empty($_POST['email'])){
  $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
  //On vérifie si le format de l'adresse mail est correct
  if (preg_match($regex, $_POST['email'])){
    include("config/bd.php");
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mdp = $_POST['password'];
    $email = $_POST['email'];
    //On vérifie si une image à été sélectionnéecho
    if (!empty($_FILES['avatar']['name'])){
      $dossier = 'avatars/';
      $fichier = basename($_FILES['avatar']['name']);
      $taille_maxi = 100000;
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
           $fichier = time().$nom.$extension;

           if(move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
           {
                echo 'Upload effectué avec succès !';
                //On insére les données dans la bdd
                try{
                  $sql = "INSERT INTO utilisateurs VALUES('','$nom', '$prenom',PASSWORD('$mdp'),'$email','','$fichier')";
                  $query = $pdo->prepare($sql);
                  $query->execute();
                  header('Location: index.php?action=login');
                } catch (Exception $e) {
                  echo 'Erreur lors de l\'importaion de l\'avatar ! ';
                  }
           }
           else //Sinon (la fonction renvoie FALSE).
           {
                echo 'Echec de l\'upload !';
           }
      }
      else //S'il y a eu une erreur...
      {
           echo $erreur;
      }
    }else{ //S'il n'y a pas d'avatar choisi
      try{
        $sql = "INSERT INTO utilisateurs VALUES('','$nom', '$prenom',PASSWORD('$mdp'),'$email','','default.jpg')";
        $query = $pdo->prepare($sql);
        $query->execute();
        header('Location: index.php?action=login');
      } catch (Exception $e) {
        echo 'Cette adresse mail est déjà utilisée ! Essayez de vous <a href="index.php?action=accueil">connecter</>';
        }
    }

  }
}else{
  echo 'Erreur';
  include 'vues/inscription.php';
}
?>
