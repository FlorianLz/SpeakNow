<script>
    $('.inputsupprimercomm').click(function (event) {
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
                    $(this)
                });
            }
        });
    });
</script>