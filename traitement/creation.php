<?php
if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['password']) && !empty($_POST['email'])){
  $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
  //On vérifie si le format de l'adresse mail est correct
  if (preg_match($regex, $_POST['email'])){
    include("config/bd.php");
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $mdp = $_POST['password'];
    $email = htmlspecialchars($_POST['email']);
      try{
        $sql = "INSERT INTO utilisateurs VALUES(NULL,'$nom', '$prenom',PASSWORD('$mdp'),'$email',NULL,'default.jpg',NULL)";
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
        $_SESSION['avatar'] = "default.jpg";
        header('Location: index.php?action=profil');
      } catch (Exception $e) {
        $_SESSION['erreurinscription'] = 'Cette adresse mail est déjà utilisée !';
        header('Location: index.php?action=accueil');
        }
    }else{
    $_SESSION['erreurinscription'] = 'Le format de votre adresse e-mail est incorrect !';
    header('Location: index.php?action=accueil');
}
}
?>
