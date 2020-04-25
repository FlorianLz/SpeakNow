<?php
session_start();
    if (isset($_POST['idAmi']) && !empty($_POST['idAmi']) && !empty($_SESSION['id'])){
        $idAmi=htmlspecialchars($_POST['idAmi']);
        $monid=$_SESSION['id'];
        $texterecherche=htmlspecialchars($_POST['texterecherche']);
        $sql='INSERT INTO lien VALUES(NULL,?,?,"attente")';
        $query = $pdo->prepare($sql);
        $query->execute(array($monid,$idAmi));
        if(isset($_POST['idpage'])){
            $idpage=$_POST['idpage'];
            header('Location: index.php?action=mur&id='.$idpage);
        }else{
            header('Location: index.php?action=recherche&texterecherche='.$texterecherche);
        }
    }else{
        include('../config/config.php');
        include('../config/bd.php');
        if(isset($_POST['idami']) && isset($_POST['page']) && isset($_POST['action'])){
            //On update
            $idAmi=$_POST['idami'];
            $monid=$_SESSION['id'];

            $sql='INSERT INTO lien VALUES(NULL,?,?,"attente")';
            $query = $pdo->prepare($sql);
            $query->execute(array($monid,$idAmi));

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
                echo '<p> Vous avez demandé en ami</p>';
                echo '<button class="actionami" data-action="annuler" data-idami="'.$idAmi.'" data-page="recherche">Annuler</button>';
            }
        }
    }

?>