<?php
session_start();
if (isset($_GET['texterecherche']) && !empty($_GET['texterecherche'])){
    include('../config/config.php');
    include('../config/bd.php');
    $texterecherche=htmlspecialchars(strtolower($_GET['texterecherche']));
    echo "<h2> Résultats de la recherche pour : $texterecherche</h2>";
    $separation= explode(' ', $texterecherche, 2);
    if (!isset($separation[1])){
        $sql='SELECT * FROM utilisateurs WHERE lower(nom) LIKE "%"?"%"  OR lower(prenom) LIKE "%"?"%" ';
        // Etape 1  : preparation
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($separation[0],$separation[0]));

        while($line = $query->fetch()){
            echo '<div id="ami'.$line['id'].'" class="ami"><div><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a></div>';
            $sql1='SELECT * FROM lien WHERE (idUtilisateur1=? AND idUtilisateur2=?) OR ((idUtilisateur1=? AND idUtilisateur2=?))';
            $query1 = $pdo->prepare($sql1);
            $query1->execute(array($_SESSION['id'],$line['id'],$line['id'],$_SESSION['id']));
            if ($line1 = $query1->fetch()){
                if ($line1['etat'] == 'ami'){
                    echo '<div><i class="far fa-smile"></i><p> Vous êtes amis</p></div></div>';
                }
                if ($line1['etat'] == 'attente'){
                    if($line1['idUtilisateur1'] == $_SESSION['id']){
                        echo '<p> Vous avez demandé en ami</p>';
                        echo '<button class="actionami" data-action="annuler" data-idami="'.$line['id'].'" data-page="recherche">Annuler</button></div>';
                    }else{
                        echo '<div><button class="actionami" data-action="accepter" data-idami="'.$line['id'].'" data-page="recherche">Accepter</button>
                                    <button class="actionami" data-action="refuser" data-idami="'.$line['id'].'" data-page="recherche">Refuser</button></div></div>';
                    }

                }
                if ($line1['etat'] == 'banni'){
                    echo '<div><i class="far fa-frown"></i><p> Utilisateur Banni</p></div></div>';
                }
            }else{
                if($line['id'] != $_SESSION['id']){
                    echo '<button class="actionami" data-action="ajouter" data-idami="'.$line['id'].'" data-page="recherche">Ajouter</button>
                          </div>';
                }else{
                    echo '<div class="modifierprofil"><a href="index.php?action=profil">
                            <i class="fas fa-user-edit"></i>
                            <p>Modifier mon profil</p></a>
                          </div></div>';
                }

            }
        }
    }else{
        $sql='SELECT * FROM utilisateurs WHERE lower(nom) LIKE "%"?"%" AND lower(prenom) LIKE "%"?"%" OR lower(nom) LIKE "%"?"%" AND lower(prenom) LIKE "%"?"%" ';
        // Etape 1  : preparation
        $query = $pdo->prepare($sql);
        // Etape 2 : execution : 2 paramètres dans la requêtes !!
        $query->execute(array($separation[0],$separation[1],$separation[1],$separation[0]));

        while($line = $query->fetch()){
            echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a></div>';
        }
    }

}else{
    echo '';
}

?>
<div id="script"></div>

<script>
    /* Partie recherche */
    $('.actionami').on('click', function (e) {
        let action=$(this).attr('data-action');
        let idami=$(this).attr('data-idami');
        let page=$(this).attr('data-page');
        let formData={
            'action' : action,
            'idami' : idami,
            'page' : page,
        };

        if(action === 'ajouter') {
            $.post("./traitement/demandeami.php", formData, function (data) {
                $('#ami' + idami).html(data);
            }).done(function () {
                $.post("./traitement/recherchescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
                    $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
                });
            });
        };

        if(action === 'annuler') {
            $.post("./traitement/annulerajout.php", formData, function (data) {
                $('#ami' + idami).html(data);
            }).done(function () {
                $.post("./traitement/recherchescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
                    $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
                });
            });
        }

        if(action === 'accepter') {
            $.post("./traitement/ajoutami.php", formData, function (data) {
                $('#ami' + idami).html(data);
            }).done(function () {
                $.post("./traitement/recherchescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
                    $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
                });
            });
        }

        if(action === 'refuser') {
            $.post("./traitement/refusami.php", formData, function (data) {
                $('#ami' + idami).html(data);
            }).done(function () {
                $.post("./traitement/recherchescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
                    $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
                });
            });
        }

    })
</script>
