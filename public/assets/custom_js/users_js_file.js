$( document ).ready(function() {
    $('.errormessage').hide();
    $('.modiferrormessage').hide();
});

function fn_delete_utilisateur(id) {
    $.ajax({
        url: "/dashboard/utilisateurs/delete/"+id,
        type: "GET",
        success: function (response) {
            $('#modal_theme_danger'+id).modal('toggle');
            fn_refresh_data_table();
            swal("Succès", "suppression effectuée avec succès!", "success");
        },
        error: function () {
            $('#delete_user_btn'+id).html('réessayer');

        }
    });
}

function fn_edit_utilisateur(id) {
    $('.modiferrormessage').hide();
    var form_modification_data = $('#modification_form'+id).serialize();

    $.ajax({
        url: "/dashboard/utilisateurs/edit/"+id,
        type: "POST",
        data: form_modification_data ,
        success: function (response) {
            var data = $.parseJSON(response);
            if(data.ok){
                $('#modal_theme_primary'+id).modal('toggle');
                fn_refresh_data_table();
                swal("Succès", "Modification effectuée avec succès!", "success");
            }else
            {
                $('.modiferrormessage').show();
                $('#modiferrormessage'+id).html(data.message);
                $('#edit_user_btn'+id).html('réessayer')
            }

        },
        error: function () {
            $('#edit_user_btn'+id).html('réessayer');
            sweetAlert("Oops...", "veuillez vérifier les champs", "error");
        }
    });
}


function fn_ajouter_utilisateur(){
    var form_ajouter_data = $('#ajouter_form').serialize();
    $.ajax({
        url: "/dashboard/utilisateurs/add",
        type: "POST",
        data: form_ajouter_data ,
        success: function (response) {
            var data = $.parseJSON(response);
            if(data.ok){
                $('#modal_theme_primary_ajout').modal('toggle');
                fn_refresh_data_table();
                swal("Succès", "Ajout effectuée avec succès!", "success");
            }else
            {
                $('#errormessage').html(data.message);
                $('.errormessage').show();
            }
        },
        error: function () {
            $('#ajouter_user_btn').html('réessayer');
            sweetAlert("Oops...", "veuillez vérifier les champs", "error");
        }
    });

}


function fn_refresh_data_table(){
    $.ajax({
        url: "/dashboard/utilisateurs/list_refresh",
        type: "GET",
        success: function (response) {
            $('#tablebody').html(response);
        },
        error: function () {

        }
    });
}


function recherche_clients_data_table(){
    $.ajax({
        url: "/dashboard/client/search",
        type: "POST",
        data: $('#formsearch').serialize() ,
        success: function (response) {
            $('#tablebody').html(response);
        },
        error: function () {

        }
    });
}


function recherche_fournisseurs_data_table(){
    $.ajax({
        url: "/dashboard/fournisseur/search",
        type: "POST",
        data: $('#formsearch').serialize() ,
        success: function (response) {
            $('#tablebody').html(response);
        },
        error: function () {

        }
    });
}