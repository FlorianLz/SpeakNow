<div class="contenurecherche">
    <div class="resultatsrecherche">
        <?php
        if (isset($_POST['texterecherche']) && !empty($_POST['texterecherche']) && !empty($_SESSION['id'])){
            $texterecherche=htmlspecialchars(strtolower($_POST['texterecherche']));
            $separation= explode(' ', $texterecherche, 2);
            if (!isset($separation[1])){
                $sql='SELECT * FROM utilisateurs WHERE lower(nom) LIKE "%"?"%"  OR lower(prenom) LIKE "%"?"%" ';
                // Etape 1  : preparation
                $query = $pdo->prepare($sql);
                // Etape 2 : execution : 2 paramètres dans la requêtes !!
                $query->execute(array($separation[0],$separation[0]));

                while($line = $query->fetch()){
                    echo $line['nom'].' '.$line['prenom'];
                }
            }else{
                $sql='SELECT * FROM utilisateurs WHERE lower(nom) LIKE "%"?"%" AND lower(prenom) LIKE "%"?"%" OR lower(nom) LIKE "%"?"%" AND lower(prenom) LIKE "%"?"%" ';
                // Etape 1  : preparation
                $query = $pdo->prepare($sql);
                // Etape 2 : execution : 2 paramètres dans la requêtes !!
                $query->execute(array($separation[0],$separation[1],$separation[1],$separation[0]));

                while($line = $query->fetch()){
                    echo $line['nom'].' '.$line['prenom'];
                }
                }
            
        }

        ?>
    </div>
</div>