<div class="listeamismobile">
    <?php
            $sql = "SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUTilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUTilisateur1=?)";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id'],$_SESSION['id']));
            $nbamis=$query->rowCount();
            if($nbamis == 0){
                echo '<h2>Vous n\'avez aucun ami</h2>';
            }else if($nbamis == 1){
                echo '<div class="itemmenu black"><i class="fas fa-user-friends black"></i><p onclick="afficherlisteamis();">Vous avez '.$nbamis.' ami</p></div>';
            }else{
                echo '<div class="itemmenu black"><i class="fas fa-user-friends black"></i><p onclick="afficherlisteamis();">Vous avez '.$nbamis.' amis</p></div>';
            }
            echo '<div id="mesamis">';
            while($line = $query->fetch()){
                echo '<div class="ami"><a href="./mur-'.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="./mur-'.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a><a href="./prives-'.$line['id'].'"><i class="fas fa-comment-dots chat"></i></a></div>';
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
                $p=1;
                demandeenvoyees($line['id'],$line['avatar'],$line['prenom'],$line['nom'],$p);
            }
            echo '</div>';

            ?>
    </div>
    <script src="./js/script.js"></script>