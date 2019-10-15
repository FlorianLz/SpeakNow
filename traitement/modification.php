<?php

$id=$_SESSION['id'];

if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['password']) && !empty($_POST['email'])){
  $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
  //On vérifie si le format de l'adresse mail est correct
  if (preg_match($regex, $_POST['email'])){
    include("config/bd.php");
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mdp = $_POST['password'];
    $email = $_POST['email'];
      try{
        $sql = 'UPDATE utilisateurs SET nom = ?, prenom=? ,mdp=PASSWORD(?), email=? WHERE id=?';

        // Etape 1  : preparation
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($nom,$prenom,$mdp,$email,$id));
        // Etape 3 : ici le login est unique, donc on sait que l'on peut avoir zero ou une  seule ligne.

        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;

        header('Location: index.php?action=profil');

      } catch (Exception $e) {
        echo 'Le format de l\'adresse mail est incorrect !';
        }
    }else{
    echo 'Erreur';
    include 'vues/inscription.php';
    }
}
