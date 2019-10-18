<div class="contenurecherche">
    <div class="resultatsrecherche">
        <?php
        if (isset($_GET['texterecherche']) && !empty($_GET['texterecherche']) && !empty($_SESSION['id'])){
            $texterecherche=htmlspecialchars(strtolower($_GET['texterecherche']));
            echo "<h1> Résultats de la reccherche pour : $texterecherche</h1>";
            $separation= explode(' ', $texterecherche, 2);
            if (!isset($separation[1])){
                $sql='SELECT * FROM utilisateurs WHERE lower(nom) LIKE "%"?"%"  OR lower(prenom) LIKE "%"?"%" ';
                // Etape 1  : preparation
                $query = $pdo->prepare($sql);
                // Etape 2 : execution : 2 paramètres dans la requêtes !!
                $query->execute(array($separation[0],$separation[0]));

                while($line = $query->fetch()){
                    echo '<div class="ami"><a href="index.php?action=mur&id='.$line['id'].'"><img class="imgami" src="avatars/'.$line['avatar'].'"></a><a href="index.php?action=mur&id='.$line['id'].'"><p>'.$line['prenom'].' '.$line['nom'].'</p></a>';
                    $sql1='SELECT * FROM lien WHERE (idUtilisateur1=? AND idUtilisateur2=?) OR ((idUtilisateur1=? AND idUtilisateur2=?))';
                    $query1 = $pdo->prepare($sql1);
                    $query1->execute(array($_SESSION['id'],$line['id'],$line['id'],$_SESSION['id']));
                    if ($line1 = $query1->fetch()){
                        if ($line1['etat'] == 'ami'){
                            echo '<p> Vous êtes amis</p></div>';
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
                                echo '<p> Vous a demandé en ami</p></div>';
                            }
                            
                        }
                        if ($line1['etat'] == 'banni'){
                            echo '<p> Utilisateur Banni</p></div>';
                        }
                    }else{
                        if($line['id'] != $_SESSION['id']){
                            echo '<form action="index.php?action=demandeami" method="POST"><input type="hidden" name="idAmi" value="'.$line['id'].'"><input type="hidden" name="texterecherche" value="'.$_GET['texterecherche'].'"><input type="submit" value="Ajouter"></form></div>';
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
            echo '<h1>Aucun résultat trouvé !</h1>';
        }

        ?>
    </div>
</div>