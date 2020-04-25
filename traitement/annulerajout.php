<?php
session_start();
    if (isset($_POST['idAmi']) && !empty($_POST['idAmi']) && isset($_SESSION['id'])){
        $idAmi=htmlspecialchars($_POST['idAmi']);
        $monid=htmlspecialchars($_SESSION['id']);

        $sql = "DELETE FROM lien WHERE idUtilisateur1=? AND idUtilisateur2=? AND etat='attente'";
        // Etape 1  : preparation
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($monid,$idAmi));
        // Etape 3 : ici le login est unique, donc on sait que l'on peut avoir zero ou une  seule ligne.
        if(isset($_POST['texterecherche'])){
            $texterecherche=$_POST['texterecherche'];
            header('Location: index.php?action=recherche&texterecherche='.$texterecherche);
        }elseif(isset($_POST['idpage'])){
            $idpage=$_POST['idpage'];
            header('Location: index.php?action=mur&id='.$idpage);
        }elseif(isset($_POST['a']) && $_POST['a'] == 1){
            header("Location: index.php?action=amis");
        }else{
            header("Location: index.php?action=mur");
        }
        
    }else{
        include('../config/config.php');
        include('../config/bd.php');
        if(isset($_POST['idami']) && isset($_POST['page']) && isset($_POST['action'])){
            //On update
            $idAmi=$_POST['idami'];
            $monid=$_SESSION['id'];

            $sql = "DELETE FROM lien WHERE idUtilisateur1=? AND idUtilisateur2=? AND etat='attente'";
            $query = $pdo->prepare($sql);
            $query->execute(array($monid,$idAmi));

            $sql='SELECT nom,prenom,avatar FROM utilisateurs WHERE id=?';
            $query = $pdo->prepare($sql);
            $query->execute(array($idAmi));

            $line=$query->fetch();


            if($_POST['page'] == 'recherche'){
                echo '<div><a href="index.php?action=mur&id='.$idAmi.'">
                        <img class="imgami" src="avatars/'.$line['avatar'].'"></a>
                        <a href="index.php?action=mur&id='.$idAmi.'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a>
                       </div>';
                echo '<button class="actionami" data-action="ajouter" data-idami="'.$idAmi.'" data-page="recherche">Ajouter</button>';
            }
        }
    }


?>