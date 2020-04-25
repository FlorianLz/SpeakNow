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
    $datenaissance=$_POST['datenaissance'];
      try{
        $sql = 'UPDATE utilisateurs SET nom = ?, prenom=? ,mdp=PASSWORD(?), email=?, datenaissance=? WHERE id=?';

        // Etape 1  : preparation
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($nom,$prenom,$mdp,$email,$datenaissance,$id));
        // Etape 3 : ici le login est unique, donc on sait que l'on peut avoir zero ou une  seule ligne.

        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['date']=$_POST['datenaissance'];

        header('Location: profil');

      } catch (Exception $e) {
        echo 'Le format de l\'adresse mail est incorrect !';
        }
    }else{
    echo 'Erreur';
    include 'vues/inscription.php';
    }
}
