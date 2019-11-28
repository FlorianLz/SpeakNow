  /* MP */
function refreshTchat(){
    if (i==0){
        setTimeout("scrollbas()",500);
        i++;
    }
    var idAmiMP = document.getElementById("idAmiMP").value;
    $.ajax({
        type: "GET", 
        url: "./traitement/messagesprives.php", 
        data: "afaire=refresh&idAmiMP="+idAmiMP, 
        success: function(msg){ 
        document.getElementById("conteneurmp").innerHTML = msg; } });
    setTimeout("refreshTchat()",1000); // ce bout de code permet de relancer la function refreshTchat toutes les 1000 milliSecondes c'est à dire toutes les secondes :)
}
    
     
function envoi(){
    var message = document.getElementById("messageMP").value;
    var idAmiMP = document.getElementById("idAmiMP").value;
    $.ajax({ 
        type: "GET", 
        url: "./traitement/messagesprives.php", 
        data: "afaire=envoi"+"&message="+message+"&idAmiMP="+idAmiMP, 
        success: function(){ 
            document.getElementById("formMP").reset();
            setTimeout("scrollbas()",1000);
        }
    });
    
}

function scrollbas(){
    element = document.getElementById('conteneurmp');
    element.scrollTop = element.scrollHeight;
}
var i=0;
refreshTchat();