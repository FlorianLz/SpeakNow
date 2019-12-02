<div class="contenu">
    <div class="infoscote">
        <div class="monprofil">
            <a href="index.php?action=mur"><div class="imageprofil" style="background-image:url('avatars/<?php echo $_SESSION['avatar'];?>');"></div></a>
            <div class="txtprofil">
                <h1><?php echo $_SESSION['prenom'].' '.$_SESSION['nom']; ?></h1>
                <div><a href="index.php?action=profil"><i class="fas fa-user-edit"></i><p>Modifier mon profil</p></a></div>
            </div>
        </div>
        <div class="menu">
            <p>MENU</p>
            <div class="itemmenu"><a href="index.php?action=fil"><i class="fas fa-home"></i><p>Fil d'actus</p></a></div>
            <div class="itemmenu"><a href="index.php?action=fil"><i class="fas fa-search"></i><p>Recherche</p></a></div>
            <div class="itemmenu"><a href="index.php?action=fil"><i class="fas fa-user"></i></i><p>Mon mur</p></a></div>
            <div class="itemmenu active"><a href="index.php?action=fil"><i class="fas fa-comment-dots"></i><p>Messenger</p></a></div>
            <div class="itemmenu"><a href="index.php?action=fil"><i class="fas fa-user-friends"></i><p>Amis</p></a></div>
        </div>
        <div class="deconnexion">
            <a href="index.php?action=deconnexion"><i class="fas fa-sign-out-alt"></i></a>
        </div>

    </div>
    <div class="infoscentre">
        <div id="listemessages" class="listemessages">
            <?php 
            if (isset($_SESSION['id']) && isset($_GET['id']) && ($_GET['id'] != $_SESSION['id'])){
                //On vérif si on es amis avec la personne
                $id=$_SESSION['id'];
                $idPers = $_GET['id'];
                $sql = "SELECT * FROM lien JOIN utilisateurs ON (utilisateurs.id=?) WHERE etat='ami'AND ((idUtilisateur1=? AND idUtilisateur2=?) OR ((idUtilisateur1=? AND idUtilisateur2=?)))";
                $query = $pdo->prepare($sql);
                $query->execute(array($idPers,$id,$idPers,$idPers,$id));
                $line = $query->fetch();
                //Si un résultat existe, c'est qu'on est amis avec la personne
                if($line == true){
                    $avatarpers=$line['avatar'];
                    echo '<div class="infosmp"><div class="imgprives" style="background-image:url(avatars/'.$avatarpers.');"></div><h2>'.$line['prenom'].' '.$line['nom'].'</h2></div>';
                    echo '<div><div id="conteneurmp" class="conteneurmp">';
                    
                    echo '</div></div>';
                    formMP($idPers);
                
                }else{
                    header ('Location: index.php?action=mur');
                }
            }else{
                header ('Location: index.php?action=mur');
            }
            ?>
        </div>
    </div>
</div>
<script src="./js/messagesprives.js"></script>
<script src="./js/script.js"></script>
