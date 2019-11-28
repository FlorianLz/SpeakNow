<?php

function gras($v) {
    return "<b>$v</b>";

}

function options($attributes) {
    $o = "";
    foreach ($attributes as $attr => $v) {
        $o = $o . "$attr='$v'";
    }
    return $o;
}

function lien($link, $texte, $attributes = array()) {
    $o = "";
    foreach ($attributes as $attr => $v) {
        $o = $o . "$attr='$v'";
    }
    return "<a href='$link' $o>$texte</a>";
}

function item($contenu, $attributes = array()) {
    $o = options($attributes);
    return "<li $o>$contenu</li>";
}

function table($table2Dim) {
    $tmp = "";
    foreach ($table2Dim as $table1Dim) {  // Je parcours ma table à 2 Dim, chaque entréee est
        // une table à 1 dim
        $tmp = $tmp . "<tr>"; // J'ai donc une nouvelle ligne
        foreach ($table1Dim as $cellule) { // Chaque entrée de la table à 1 dim est une donnée
            $tmp = $tmp . "<td>$cellule</td>"; // Je la met entre td!
        }

        $tmp = $tmp . "</tr>"; // Je dois fermer la ligne

    }

    return $tmp;
}

function message($msg) {
    $_SESSION['info'] = $msg;
}

//Affichage du bouton like
function formlike($idpost,$idredirection,$lieuredirection,$typebouton,$action){
    echo "<form action='index.php?action=$action' method='POST'>
            <input type='hidden' name='idPost' value='$idpost'>
            <input type='hidden' name='$lieuredirection' value='$idredirection'>
            <label for='like$idpost'><i class='far fa-thumbs-up $typebouton'></i></label>
            <input id='like$idpost' type='submit' class='inputlike'>
            </form><br><br>";
}

//Ajout commentaire
function formajoutcommentaire($idpost,$idredirection,$lieuredirection){
    echo '<form method="post" action="index.php?action=ajoutcommentaire">
         <img class="imgpost" src="avatars/'.$_SESSION['avatar'].'">
        <textarea name="comm" placeholder="Votre commentaire..." class="autoExpand" rows="1" data-min-rows="1"></textarea>
        <input type="hidden" name='.$lieuredirection.' value="'.$idredirection.'">
        <input type="hidden" name="idpost" value="'.$idpost.'">
        <input type="submit" value="" name="submit" id="submit'.$idpost.'"><label for="submit'.$idpost.'"><i class="fas fa-paper-plane"></i></label>
    </form>';
}
//Suppression commentaire
function formsupprimercommentaire($idpost,$idcomm,$comm,$idredirection,$lieuredirection){
    echo '<form method="post" action="index.php?action=supprimercommentaire">
                                <input type="hidden" name="idCommentaire" value="'.$idcomm.'">
                                <input type="hidden" name="commentaire" value="'.$comm.'">
                                <input type="hidden" name="idpost" value="'.$idpost.'">
                                <input type="hidden" name='.$lieuredirection.' value="'.$idredirection.'">
                                <label for="supprimercomm'.$idcomm.'"><i class="fas fa-times"></i></label>
                                <input type="submit" value="" id="supprimercomm'.$idcomm.'">                                
                                </form>';
}
//Affichage erreurs liees aux commentaires
function alertecomm($lineid){
    if(isset($_SESSION['alertecomm'.$lineid])){
        echo $_SESSION['alertecomm'.$lineid];
        unset($_SESSION['alertecomm'.$lineid]);
    }
}
//Suppression post
function formsupprimerpost($id,$titre,$lieuredirection,$idredirection,$contenu,$image,$date){
    echo '<form method="post" action="index.php?action=supprimerpost">
                    <input type="hidden" name="id" value="'.$id.'">
                    <input type="hidden" name="titre" value="'.$titre.'">
                    <input type="hidden" name='.$lieuredirection.' value="'.$idredirection.'">
                    <input type="hidden" name="message" value="'.$contenu.'">';
                    if(isset($image) && !empty($image)){
                        echo '<input type="hidden" name="image" value="'.$image.'">';
                    }
                echo '<input type="hidden" name="date" value="'.$date.'">
                    <label for="supprimer'.$id.'"><i class="fas fa-times"></i></label>
                    <input type="submit" value="" id="supprimer'.$id.'">
                    </form>';
}

function formajoutpost($idpers,$prenompers){
    echo "<div class='poster'><form enctype='multipart/form-data' class='formposter' action='index.php?action=poster' method='post'>";
    if(!empty($prenompers)){
        echo "<h3>Ecrire un message à $prenompers</h3>";
    }else{
        echo "<h3>Nouveau speak</h3>";
    }
    echo "<input type='text' name='titre' placeholder='Titre...'>
    <input type='hidden' name='idpers' value='$idpers'>
    <textarea name='message' placeholder='Message...'></textarea>
    <div class='uploadimage'>
        <label class='uploadfile' for='image'><i class='fas fa-image'></i></label>
        <div class='cacherbtnfile'>
            <input type='file' name='photo' id='image' class='inputfile' onchange='loadFile(event)'>
        </div>
    </div>
    <div class='apercuimage'><img src='' alt='Aperçu de l'image sélectionnée' id='apercuimg'></div>
    <input type='submit' value='Speaker'>
</form></div>";
}

function demandesrecues($idpers,$avatarpers,$prenompers,$nompers){
    echo '<div class="ami"><a href="index.php?action=mur&id='.$idpers.'"><img class="imgami" src="avatars/'.$avatarpers.'"></a><a href="index.php?action=mur&id='.$idpers.'"><p>'.$prenompers.' '.$nompers.'</p></a>';
            echo '<form method="post" action="index.php?action=ajoutami">
                        <input type="hidden" name="idAmi" value="'.$idpers.'">
                        <input type="submit" value="Accepter">
                        </form>
                        <form method="post" action="index.php?action=refusami">
                        <input type="hidden" name="idAmi" value="'.$idpers.'">
                        <input type="submit" value="Refuser">
                        </form></div>';
}

function demandeenvoyees($idpers,$avatarpers,$prenompers,$nompers){
    echo '<div class="ami"><a href="index.php?action=mur&id='.$idpers.'"><img class="imgami" src="avatars/'.$avatarpers.'"></a><a href="index.php?action=mur&id='.$idpers.'"><p>'.$prenompers.' '.$nompers.'</p></a>';
            echo '<form method="post" action="index.php?action=annulerajout">
                        <input type="hidden" name="idAmi" value="'.$idpers.'">
                        <input type="submit" value="Annuler">
                        </form></div>';
}

function afficherpost($idpost,$idauteur,$avatarauteur,$prenomauteur,$nomauteur,$dateecrit,$idsession,$titre,$contenu,$image,$date,$idredirection){
    echo '<div class="postmur" id="post'.$idpost.'">
    <div class="auteur"><div><a href="index.php?action=mur&id='.$idauteur.'"><img class="imgpost" src="avatars/'.$avatarauteur.'">
    <div><p>'.$prenomauteur.' '.$nomauteur.'</p></a><p>'.$dateecrit.'</p></div></div>
    <div>';
    if($idauteur == $idsession){
        formsupprimerpost($idpost,$titre,"murredirection",$idredirection,$contenu,$image,$date);
    }
    echo '</div></div>
    <p class="titrepost">'.$titre.'</p><br>
    <p>'.$contenu.'</p>';
}

function afficherpostfil2($idpost,$idauteur,$avatarauteur,$prenomauteur,$nomauteur,$dateecrit,$idsession,$titre,$contenu,$image,$date,$idredirection,$iddest,$prenomdest,$nomdest){
    echo '<div class="postmur" id="post'.$idpost.'">
    <div class="auteur"><div><a href="index.php?action=mur&id='.$idauteur.'"><img class="imgpost" src="avatars/'.$avatarauteur.'">
    <div>';
    if($idauteur == $idsession){
        echo '<p class="infosbold">'.$prenomauteur.' '.$nomauteur.'</p>';
    }else{
        echo'<p>'.$prenomauteur.' '.$nomauteur.'</p>';
    }
    echo '</a><a href="index.php?action=mur&id='.$iddest.'">';
    if ($iddest==$idsession){
        echo '<p class="infosbold">> '.$prenomdest.' '.$nomdest.'</p></a><p>'.$dateecrit.'</p></div></div>
    <div>';
    }else{
        echo '<p>> '.$prenomdest.' '.$nomdest.'</p></a><p>'.$dateecrit.'</p></div></div>
    <div>';
    }
    if($idauteur == $idsession){
        formsupprimerpost($idpost,$titre,"filredirection",$idredirection,$contenu,$image,$date);
    }
    echo '</div></div>
    <p class="titrepost">'.$titre.'</p><br>
    <p>'.$contenu.'</p>';
}

function afficherpostfil($idpost,$idauteur,$avatarauteur,$prenomauteur,$nomauteur,$dateecrit,$idsession,$titre,$contenu,$image,$date,$idredirection){
    echo '<div class="postmur" id="post'.$idpost.'">
    <div class="auteur"><div><a href="index.php?action=mur&id='.$idauteur.'"><img class="imgpost" src="avatars/'.$avatarauteur.'">
    <div><p>'.$prenomauteur.' '.$nomauteur.'</p></a><p>'.$dateecrit.'</p></div></div>
    <div>';
    if($idauteur == $idsession){
        formsupprimerpost($idpost,$titre,"filredirection",$idredirection,$contenu,$image,$date);
    }
    echo '</div></div>
    <p class="titrepost">'.$titre.'</p><br>
    <p>'.$contenu.'</p>';
}

function formMP($idPers){
    echo '<form onsubmit="envoi();return false;" id="formMP">
                <input type="text" id="messageMP" name="message" placeholder="Votre message...">
                <input type="hidden" id="idAmiMP" name="idAmiMP" value="'.$idPers.'">
                <i class="fas fa-paper-plane" onclick="envoi();"></i>
                </form>';
}

?>















