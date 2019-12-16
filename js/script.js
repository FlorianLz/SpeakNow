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