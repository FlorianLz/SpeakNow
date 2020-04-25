<?php
session_start();
    if (isset($_POST['idAmi']) && !empty($_POST['idAmi']) && isset($_SESSION['id'])){
        $idAmi=htmlspecialchars($_POST['idAmi']);
        $monid=htmlspecialchars($_SESSION['id']);

        $sql = "UPDATE lien SET etat = 'banni' WHERE idUtilisateur1=? AND idUtilisateur2=? OR idUtilisateur1=? AND idUtilisateur2=? ";
        // Etape 1  : preparation
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($idAmi,$monid,$monid,$idAmi));
        // Etape 3 : ici le login est unique, donc on sait que l'on peut avoir zero ou une  seule ligne.
        header("Location: index.php?action=mur");
    }else{
        include('../config/config.php');
        include('../config/bd.php');
        if(isset($_POST['idami']) && isset($_POST['page']) && isset($_POST['action'])){
            //On update
            $idAmi=$_POST['idami'];
            $monid=$_SESSION['id'];

            $sql = "UPDATE lien SET etat = 'banni' WHERE idUtilisateur1=? AND idUtilisateur2=? OR idUtilisateur1=? AND idUtilisateur2=? ";
            $query = $pdo->prepare($sql);
            $query->execute(array($idAmi,$monid,$monid,$idAmi));

            $sql='SELECT nom,prenom,avatar FROM utilisateurs WHERE id=?';
            // Etape 1  : preparation
            $query = $pdo->prepare($sql);
            // Etape 2 : execution : 2 paramètres dans la requêtes !!
            $query->execute(array($idAmi));

            $line=$query->fetch();


            if($_POST['page'] == 'recherche'){
                echo '<div><a href="index.php?action=mur&id='.$idAmi.'">
                        <img class="imgami" src="avatars/'.$line['avatar'].'"></a>
                        <a href="index.php?action=mur&id='.$idAmi.'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a>
                       </div>';
                echo '<div><i class="far fa-frown"></i><p> Utilisateur Banni</p></div>';
            }
        }
    }


?>