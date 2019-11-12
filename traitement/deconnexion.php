<?php
unset($_SESSION['nom']);
unset($_SESSION['prenom']);
unset($_SESSION['avatar']);
unset($_SESSION['email']);
if (isset($_COOKIE['remember'])){
    setcookie('remember','',time()-3600);
    $sql="UPDATE utilisateurs SET remember='' where id=?";
    $query = $pdo->prepare($sql);
    $query->execute(array($_SESSION['id']));
    unset($_SESSION['id']);
    header('Location: index.php?action=accueil');
}else{
    unset($_SESSION['id']);
    header('Location: index.php?action=accueil');
}
?>
