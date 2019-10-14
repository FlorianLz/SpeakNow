<?php
unset($_SESSION['id']);
unset($_SESSION['nom']);
unset($_SESSION['prenom']);
unset($_SESSION['avatar']);
unset($_SESSION['email']);
header('Location: index.php?action=accueil');
?>
