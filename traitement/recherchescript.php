<script>
    /* Partie recherche */
    $('.actionami').on('click', function (e) {
        let action=$(this).attr('data-action');
        let idami=$(this).attr('data-idami');
        let page=$(this).attr('data-page');
        let formData={
            'action' : action,
            'idami' : idami,
            'page' : page,
        };

        if(action === 'ajouter') {
            $.post("./traitement/demandeami.php", formData, function (data) {
                $('#ami' + idami).html(data);
            }).done(function () {
                $.post("./traitement/recherchescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
                    $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
                });
            });
        };

        if(action === 'annuler') {
            $.post("./traitement/annulerajout.php", formData, function (data) {
                $('#ami' + idami).html(data);
            }).done(function () {
                $.post("./traitement/recherchescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
                    $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
                });
            });
        }

        if(action === 'accepter') {
            $.post("./traitement/ajoutami.php", formData, function (data) {
                $('#ami' + idami).html(data);
            }).done(function () {
                $.post("./traitement/recherchescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
                    $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
                });
            });
        }

        if(action === 'refuser') {
            $.post("./traitement/refusami.php", formData, function (data) {
                $('#ami' + idami).html(data);
            }).done(function () {
                $.post("./traitement/recherchescript.php", formData, function (data) { //On envoi le tout vers la page de traitement
                    $('#script').html(data);//On affiche l'HTML retourné  par la page PHP dans la div
                });
            });
        }

    })
</script>