function afficherlisterecues(){
    let liste = document.getElementById('listerecues');
    let amis = document.getElementById('mesamis');
    let envoyees = document.getElementById('listeenvoyees');
    liste.style.height='fit-content';
    amis.style.height='0px';
    envoyees.style.height='0px';
}
function afficherlisteamis(){
    let liste = document.getElementById('listerecues');
    let amis = document.getElementById('mesamis');
    let envoyees = document.getElementById('listeenvoyees');
    amis.style.height='fit-content';
    liste.style.height='0px';
    envoyees.style.height='0px';
}
function afficherlisteenvoyees(){
    let liste = document.getElementById('listerecues');
    let amis = document.getElementById('mesamis');
    let envoyees = document.getElementById('listeenvoyees');
    envoyees.style.height='fit-content';
    amis.style.height='0px';
    liste.style.height='0px';
}

/* Taille auto des textarea pour les commentaires */
$(document).one('focus.autoExpand', 'textarea.autoExpand', function(){
        var savedValue = this.value;
        this.value = '';
        this.baseScrollHeight = this.scrollHeight;
        this.value = savedValue;
    })
    .on('input.autoExpand', 'textarea.autoExpand', function(){
        var minRows = this.getAttribute('data-min-rows')|0, rows;
        this.rows = minRows;
        rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 16);
        this.rows = minRows + rows;
    });

var loadFile = function(event) {
    var sortie = document.getElementById('apercuimg');
    sortie.style.display="block";
    sortie.src = URL.createObjectURL(event.target.files[0]);
  };

function accueil(){
    document.location.href="index.php?action=mur"; 
}

//Rafraichissement automatique de la recherche
$('input[name=texterecherche]').on('keyup', function (event){
    let texterecherche = $(this).val();
    if(texterecherche.trim() == 0){
    }else{
        let formData = { //On créer un tableau avec les données du formulaire
            'texterecherche': texterecherche
        };

        $.get("./traitement/recherche.php", formData, function (data) {
            $('#resultatsrecherche').html(data);
        });
    }
});

$('.inputlike').on('click', function (event) {
    event.preventDefault();
    let idPost= $(this).attr('data-like');

    let formData = { //On créer un tableau avec les données du formulaire
        'idPost': idPost
    };

    $.post("./traitement/like.php", formData, function (data) { //On envoi le tout vers la page de traitement
        $('#labellike'+idPost).html(data); //On affiche l'HTML retourné  par la page PHP dans la div #contenu
    });
});

$('.inputsupprimercomm').on('click',function (event) {
    event.preventDefault();
    let idPost= $(this).attr('data-idpost');
    let idComm= $(this).attr('data-idcomm');

    let formData = { //On créer un tableau avec les données du formulaire
        'idPost': idPost,
        'idCommentaire': idComm
    };

    $.post("./traitement/supprimercommentaire.php", formData, function (data) { //On envoi le tout vers la page de traitement
        if(data=='ok'){
            $('#comm'+idComm).slideUp(400, function(){
                $(this).remove();

                $.post("./traitement/ajoutcommescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
                    $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
                });
            });
        }
    });
});

$('.formcomm').on('submit',function (event) {
    event.preventDefault();
    let idpost = $(this).attr('data-idpost');
    let comm = $(this).find("textarea").val();

    let formData = { //On créer un tableau avec les données du formulaire
        'idpost': idpost,
        'comm': comm
    };
    $.post("./traitement/ajoutcommentaire.php", formData, function (data) { //On envoi le tout vers la page de traitement
        $('#commentairespost'+idpost).append(data);//On affiche l'HTML retourné  par la page PHP dans la div
        $('textarea[name=comm]').val('');

    }).done(function () {
        $.post("./traitement/ajoutcommescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
            $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
        });
    });
});