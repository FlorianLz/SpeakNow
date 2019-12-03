<div class="contenu">
    <div class="infoscote">
        <div class="monprofil">
            <a href="index.php?action=mur"><div class="imageprofil" style="background-image:url('avatars/<?php echo $_SESSION['avatar'];?>');"></div></a>
            <div class="txtprofil">
                <h1><?php echo $_SESSION['prenom'].' '.$_SESSION['nom']; ?></h1>
                <div><a href="index.php?action=profil"><i class="fas fa-user-edit"></i><p>Modifier mon profil</p></a></div>
            </div>
            <div class="deconnexion">
                <a href="index.php?action=deconnexion"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
        <div class="menu">
            <p>MENU</p>
            <div class="itemmenu"><a href="index.php?action=fil"><i class="fas fa-home"></i><p>Fil d'actus</p></a></div>
            <div class="itemmenu active"><a href="index.php?action=recherche"><i class="fas fa-search"></i><p>Recherche</p></a></div>
            <div class="itemmenu"><a href="index.php?action=mur"><i class="fas fa-user"></i></i><p>Mon mur</p></a></div>
            <div class="itemmenu"><a href="index.php?action=prives"><i class="fas fa-comment-dots"></i><p>Messenger</p></a></div>
        </div>
        
        <div class="partieamis">
        <?php
            $sql = "SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUTilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUTilisateur1=?)";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id'],$_SESSION['id']));
            $nbamis=$query->rowCount();
            if($nbamis == 0){
                echo '<h2>Vous n\'avez aucun ami</h2>';
            }else if($nbamis == 1){
                echo '<div class="itemmenu"><i class="fas fa-user-friends"></i><p onclick="afficherlisteamis();">Vous avez '.$nbamis.' ami</p></div>';
            }else{
                echo '<div class="itemmenu"><i class="fas fa-user-friends"></i><p onclick="afficherlisteamis();">Vous avez '.$nbamis.' amis</p></div>';
            }
            echo '<div id="mesamis">';
            while($line = $query->fetch()){
                echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a><a href="index.php?action=prives&id='.$line['id'].'"><i class="far fa-comment-alt chat"></i></a></div>';
            }
            echo '</div>';

            $sql = "SELECT utilisateurs.* FROM utilisateurs WHERE id IN(SELECT idUtilisateur1 FROM lien WHERE idUtilisateur2=? AND etat='attente') ";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id']));
            $nbrecues=$query->rowCount();
            /*if($nbrecues == 0){
                echo '<div class="itemmenu"><i class="fas fa-user-plus"></i><p>Aucune demande reçue</p></div>';
            }else */if($nbrecues == 1){
                echo '<div class="itemmenu"><i class="fas fa-user-plus"></i><p onclick="afficherlisterecues();">'.$nbrecues.' demande reçue</p></div>';
            }else if($nbrecues > 1){
                echo '<div class="itemmenu"><i class="fas fa-user-plus"></i><p onclick="afficherlisterecues();">'.$nbrecues.' demandes reçues</p></div>';
            }
            echo '<div id="listerecues">';
            while($line = $query->fetch()){
                demandesrecues($line['id'],$line['avatar'],$line['prenom'],$line['nom']);
            }
            echo '</div>';

            $sql = "SELECT utilisateurs.* FROM utilisateurs INNER JOIN lien ON utilisateurs.id=idUtilisateur2 AND etat='attente' AND idUtilisateur1=?";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id']));
            $nbenvoyees=$query->rowCount();
            /*if($nbenvoyees == 0){
                echo '<div class="itemmenu"><i class="fas fa-user-check"></i><p>Aucune demande envoyée</p></div>';
            }else*/ if ($nbenvoyees == 1){
                echo '<div class="itemmenu"><i class="fas fa-user-check"></i><p onclick="afficherlisteenvoyees();">'.$nbenvoyees.' demande envoyée</p></div>';
            }else if($nbenvoyees > 1){
                echo '<div class="itemmenu"><i class="fas fa-user-check"></i><p onclick="afficherlisteenvoyees();">'.$nbenvoyees.' demandes envoyées</p></div>';
            }
            echo '<div id="listeenvoyees">';
            while($line = $query->fetch()){
                demandeenvoyees($line['id'],$line['avatar'],$line['prenom'],$line['nom']);
            }
            echo '</div>';

            ?>
        </div>

    </div>
    <div class="infoscentre">
        <div class='resultatsrecherche'>
            <?php
            if (isset($_GET['texterecherche']) && !empty($_GET['texterecherche']) && !empty($_SESSION['id'])){
                $texterecherche=htmlspecialchars(strtolower($_GET['texterecherche']));
                formrecherche();
                echo "<h2> Résultats de la recherche pour : $texterecherche</h2>";
                $separation= explode(' ', $texterecherche, 2);
                if (!isset($separation[1])){
                    $sql='SELECT * FROM utilisateurs WHERE lower(nom) LIKE "%"?"%"  OR lower(prenom) LIKE "%"?"%" ';
                    // Etape 1  : preparation
                    $query = $pdo->prepare($sql);
                    // Etape 2 : execution : 2 paramètres dans la requêtes !!
                    $query->execute(array($separation[0],$separation[0]));

                    while($line = $query->fetch()){
                        echo '<div class="ami"><div><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a></div>';
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
                                    echo '<form method="post" action="index.php?action=annulerajout">
                                    <input type="hidden" name="idAmi" value="'.$line['id'].'">
                                    <input type="hidden" name="texterecherche" value="'.$_GET['texterecherche'].'">
                                    <input type="submit" value="Annuler">
                                    </form></div>';
                                }else{
                                    echo '<div><form method="post" action="index.php?action=ajoutami">
                                    <input type="hidden" name="idAmi" value="'.$line['id'].'">
                                    <input type="submit" value="Accepter">
                                    </form>
                                    <form method="post" action="index.php?action=refusami">
                                    <input type="hidden" name="idAmi" value="'.$line['id'].'">
                                    <input type="submit" value="Refuser">
                                    </form></div></div>';
                                }
                                
                            }
                            if ($line1['etat'] == 'banni'){
                                echo '<div><i class="far fa-frown"></i><p> Utilisateur Banni</p></div></div>';
                            }
                        }else{
                            if($line['id'] != $_SESSION['id']){
                                echo '<form action="index.php?action=demandeami" method="POST"><input type="hidden" name="idAmi" value="'.$line['id'].'"><input type="hidden" name="texterecherche" value="'.$_GET['texterecherche'].'"><input type="submit" value="Ajouter"></form></div>';
                            }else{
                                echo '<div class="modifierprofil"><a href="index.php?action=profil"><i class="fas fa-user-edit"></i><p>Modifier mon profil</p></a></div></div>';
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
                echo '<h2>Entrez le nom ou le prénom d\'une personne pour la rechercher !</h2>';
                formrecherche();
            }

            ?>
        </div>
    </div>
</div>
