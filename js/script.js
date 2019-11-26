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