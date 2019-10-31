<div class="contenuamis">
    <div class="demandesamis">
        <div class="recues">
            <?php 
            
            $sql = "SELECT utilisateurs.* FROM utilisateurs WHERE id IN(SELECT idUtilisateur1 FROM lien WHERE idUtilisateur2=? AND etat='attente') ";
            $query = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id']));
            $query1 = $pdo->prepare($sql);
            $query1->execute(array($_SESSION['id']));
            $i=0;
            while($line1 = $query1->fetch()){
                $i++;
            }
            if($i==0){
                echo '<h2>Aucune demande reçue :(</h2>';
            }
            if($i==1){
                echo '<h2> 1 demande reçue :)</h2>';
            }
            if($i>1){
                echo "<h2>$i demandes reçues :)</h2>";
            }
            while($line = $query->fetch()){
                echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a>';
                echo '<form method="post" action="index.php?action=ajoutami">
                            <input type="hidden" name="idAmi" value="'.$line['id'].'">
                            <input type="submit" value="Accepter">
                            </form>
                            <form method="post" action="index.php?action=refusami">
                            <input type="hidden" name="idAmi" value="'.$line['id'].'">
                            <input type="submit" value="Refuser">
                        </form></div>';
            }
            echo '</div><div class="envoyees">';
            

            $sql = "SELECT utilisateurs.* FROM utilisateurs INNER JOIN lien ON utilisateurs.id=idUtilisateur2 AND etat='attente' AND idUtilisateur1=?";
            $query = $pdo->prepare($sql);
            $query1 = $pdo->prepare($sql);
            $query->execute(array($_SESSION['id']));
            $query1->execute(array($_SESSION['id']));
            $i=0;
            while($line1 = $query1->fetch()){
                $i++;
            }
            if($i==0){
                echo '<h2>Aucune demande envoyée :(</h2>';
            }
            if($i==1){
                echo '<h2> 1 demande envoyée :)</h2>';
            }
            if($i>1){
                echo "<h2>$i demandes envoyées :)</h2>";
            }
            while($line = $query->fetch()){
                echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a>';
                echo '<form method="post" action="index.php?action=annulerajout">
                            <input type="hidden" name="idAmi" value="'.$line['id'].'">
                            <input type="submit" value="Annuler">
                            </form></div>';
            }
            echo '</div>';
            ?>
    </div>
    <div class="listeami">
        <?php 
        $sql = "SELECT * FROM utilisateurs WHERE id IN ( SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur1=utilisateurs.id AND etat='ami' AND idUTilisateur2=? UNION SELECT utilisateurs.id FROM utilisateurs INNER JOIN lien ON idUtilisateur2=utilisateurs.id AND etat='ami' AND idUTilisateur1=?)";
        $query = $pdo->prepare($sql);
        $query->execute(array($_SESSION['id'],$_SESSION['id']));
        $query1 = $pdo->prepare($sql);
        $query1->execute(array($_SESSION['id'],$_SESSION['id']));
        $i=0;
        while($line1 = $query1->fetch()){
            $i++;
        }
        if($i==0){
            echo '<h2>Vous n\'avez pas encore d\'amis :(</h2>';
        }
        if($i==1){
            echo '<h2> Vous avez un seul ami :)</h2>';
        }
        if($i>1){
            echo "<h2>Vous avez $i amis :)</h2>";
        }
        while($line = $query->fetch()){
            echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a></div>';
        }
        
        ?>
    </div>
</div>