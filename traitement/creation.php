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
      try{
        $sql = "INSERT INTO utilisateurs VALUES('','$nom', '$prenom',PASSWORD('$mdp'),'$email','','default.jpg')";
        $query = $pdo->prepare($sql);
        $query->execute();


        $sql1 = "SELECT * FROM utilisateurs WHERE email=?";

        // Etape 1  : preparation
        $query = $pdo->prepare($sql1);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($email));
        // Etape 3 : ici le login est unique, donc on sait que l'on peut avoir zero ou une  seule ligne.

        // un seul fetch
        $line = $query->fetch();
        session_start();
        $_SESSION['id'] = $line['id'];
        $_SESSION['email'] = $line['email'];
        $_SESSION['nom'] = $line['nom'];
        $_SESSION['prenom'] = $line['prenom'];
        header('Location: index.php');
        //header('Location: index.php?action=login');
      } catch (Exception $e) {
        echo 'Cette adresse mail est déjà utilisée ! Essayez de vous <a href="index.php?action=accueil">connecter</>';
        }
    }else{
    echo 'Erreur';
    include 'vues/inscription.php';
}
}
?>
