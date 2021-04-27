var tbody = `#table-liste tbody`;
var body_modal_add = `#modal-add .modal-body`;
var body_modal_edit = `#modal-edit .modal-body`;

function routeBringer(route) {
    $(tbody).LoadingOverlay("show", {image: "", fontawesome: "icon-spinner3 spinner"});
    let paramFiltre = form_filtre_serialize + (form_filtre_serialize != "" ? '&' : '');
    $.ajax({
        type: "GET",
        url: route,
        data: paramFiltre + "sort=" +sort + "&order=" + order + "&limit=" + limit,
        dataType: 'json',
        complete: function () {
            $(tbody).LoadingOverlay("hide", true);
        },
        success: function (json) {
            $(tbody).html(json.content);
            $('.pagination').html(json.pagination);
        },
        error: function (jqXHR) {
            my_alert(jqXHR.responseText, 'danger', '#modal-message-alert');
        }
    });
}

var form_filtre_serialize = '';
var sort = 'id';
var order = 'DESC';
var limit = $('select[name="limit"]').val();

$('select[name="limit"]').change(function () {
    if ($(tbody).find('tr').length == 1)
        return;

    limit = $(this).val();

    $(tbody).LoadingOverlay("show", {image: "", fontawesome: "icon-spinner3 spinner"});
    let paramFiltre = form_filtre_serialize + (form_filtre_serialize != "" ? '&' : '');
    $.ajax({
        type: "GET",
        data: paramFiltre + "sort=" +sort + "&order=" + order + "&limit=" + limit,
        dataType: 'json',
        complete: function () {
            $(tbody).LoadingOverlay("hide", true);
        },
        success: function (json) {
            $(tbody).html(json.content);
            $('.pagination').html(json.pagination);
        },
        error: function (jqXHR) {
            my_alert(jqXHR.responseText, 'danger', '#modal-message-alert');
        }
    });
});

$('th[data-sort]').click(function () {
    if ($(tbody).find('tr').length == 1)
        return;

    var $this = $(this);
    var clas = $this.hasClass('down') ? 'up' : 'down';
    order = $this.hasClass('down') ? 'ASC' : 'DESC';
    sort = $this.data('sort');

    $(tbody).LoadingOverlay("show", {image: "", fontawesome: "icon-spinner3 spinner"});
    let paramFiltre = form_filtre_serialize + (form_filtre_serialize != "" ? '&' : '');
    $.ajax({
        type: "GET",
        data: paramFiltre + "sort=" +sort + "&order=" + order + "&limit=" + limit,
        dataType: 'json',
        complete: function () {
            $(tbody).LoadingOverlay("hide", true);
        },
        success: function (json) {
            $(tbody).html(json.content);
            $('.pagination').html(json.pagination);
            $this.closest('tr').find('th').removeClass('up').removeClass('down');
            $this.addClass(clas);
        },
        error: function (jqXHR) {
            my_alert(jqXHR.responseText, 'danger', '#modal-message-alert');
        }
    });
});

$('#btn-export-xsl').click(function () {
    if ($(tbody).find('tr').length == 1)
        return;

    $(tbody).LoadingOverlay("show", {image: "", fontawesome: "icon-spinner3 spinner"});
    let paramFiltre = form_filtre_serialize + (form_filtre_serialize != "" ? '&' : '');
    $.ajax({
        type: "GET",
        data: paramFiltre + "sort=" +sort + "&order=" + order + "&limit=" + limit + "&export-xsl=1",
        dataType: 'json',
        complete: function () {
            $(tbody).LoadingOverlay("hide", true);
        },
        success: function (json) {
            var dataXLS = json.data;
            $("#dvjson").excelexportjs({
                containerid: "dvjson"
                , datatype: 'json'
                , dataset: dataXLS
                , columns: getColumns(dataXLS)
            });
        },
        error: function (jqXHR) {
            my_alert(jqXHR.responseText, 'danger', '#modal-message-alert');
        }
    });
})

$('#modal-add form').submit(function (e) {
    e.preventDefault();

    try {
        if (CKEDITOR !== undefined)
            for (instance in CKEDITOR.instances)
                CKEDITOR.instances[instance].updateElement();
    } catch (e) {
    }

    let submit = true;
    $('#modal-add form textarea[data-required]').each(function () {
        if ($(this).val() == "") {
            viewAlert('veuillez remplir le champ ' + $(this).prev('label').html().replace('<span class="symbol required"></span>', ''), 'danger');
            submit = false;
        }
    });

    if (!submit)
        return;

    $(body_modal_add).LoadingOverlay("show", {image: "", fontawesome: "icon-spinner3 spinner"});

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'html',
        complete: function () {
            $(body_modal_add).LoadingOverlay("hide", true);
        },
        success: function (content) {
            $(tbody).prepend(content);
            $('span#count-item').html(parseInt($('span#count-item').html()) + 1);
            $('#modal-add').modal('hide');
            resetForm($('#modal-add form'));
            $('#zero-item').remove();
        },
        error: function (jqXHR) {
            my_alert(jqXHR.responseText, 'danger', '#modal-message-alert');
        }
    });

});

$(document).on('submit', '#modal-edit form', function (e) {
    e.preventDefault();

    try {
        if (CKEDITOR !== undefined)
            for (instance in CKEDITOR.instances)
                CKEDITOR.instances[instance].updateElement();
    } catch (e) {
    }

    let submit = true;
    $('#modal-edit form textarea[data-required]').each(function () {
        if ($(this).val() == "") {
            viewAlert('veuillez remplir le champ ' + $(this).prev('label').html().replace('<span class="symbol required"></span>', ''), 'danger');
            submit = false;
        }
    });

    if (!submit)
        return;

    $(body_modal_edit).LoadingOverlay("show", {image: "", fontawesome: "icon-spinner3 spinner"});

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'html',
        complete: function () {
            $(body_modal_edit).LoadingOverlay("hide", true);
        },
        success: function (content) {
            object = $('<div/>').html(content).contents();
            $(tbody + " tr#" + $('input#id').val()).html(object.html());
            $('#modal-edit').modal('hide');
        },
        error: function (jqXHR) {
            my_alert(jqXHR.responseText, 'danger', '#modal-message-alert');
        }
    });

});

$('form[name="filtre"]').submit(function (e) {
    e.preventDefault();

    $(tbody).LoadingOverlay("show", {image: "", fontawesome: "icon-spinner3 spinner"});
    form_filtre_serialize = $(this).serialize();
    let paramFiltre = form_filtre_serialize + (form_filtre_serialize != "" ? '&' : '');
    $.ajax({
        type: "GET",
        data: paramFiltre + "sort=" +sort + "&order=" + order + "&limit=" + limit,
        dataType: 'json',
        complete: function () {
            $(tbody).LoadingOverlay("hide", true);
        },
        success: function (json) {
            $(tbody).html(json.content);
            $('.pagination').html(json.pagination);
            $('span#count-item').html(json.countItem);
        },
        error: function (jqXHR) {
            my_alert(jqXHR.responseText, 'danger', '#modal-message-alert');
        }
    });

});

function resetForm(form) {
    form.get(0).reset();
    form.find('.styled').uniform();
    form.find('.select-search').select2();
    form.find('.select').select2({minimumResultsForSearch: Infinity});

    try {
        if (CKEDITOR !== undefined)
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].setData('');
            }
    } catch (e) {
    }
}

$(document).on('click', '.btn-delete', function () {
    let url = $(this).data('action');
    $tr = $(this).closest('tr');
    $td = $(this).closest('td');
    let fntAction = function () {
        $td.html('<i class="icon-spinner2 spinner"></i>');
        $.ajax({
            url: url,
            success: function () {
                $tr.remove();
                $('span#count-item').html(parseInt($('span#count-item').html()) - 1);

                if ($(tbody).find('tr').length == 0)
                    $(tbody).append(`<tr id="zero-item">
                                    <td class="text-center" colspan="${$('#table-liste th').length}">${language.zeroItem}</td>
                                </tr>`);
            },
            error: function (jqXHR) {
                $.jGrowl(jqXHR.responseText, {
                    header: 'Erreur',
                    theme: 'alert-styled-left bg-danger'
                });
            }
        });
    };
    demandConfirm(language.confirmSupp.title, language.confirmSupp.msg, language.confirmSupp.btn, fntAction);
});


$(document).ready(function () {

    $("#but_upload").click(function () {

        var fd = new FormData();
        var files = $('#file')[0].files[0];
        fd.append('file', files);

        $.ajax({
            url: 'upload.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response != 0) {
                    $("#img").attr("src", response);
                    $(".preview img").show(); // Display image element
                } else {
                    alert('file not uploaded');
                }
            },
        });
    });
});

$(document).on('click', '.btn-edit', function () {
    $(body_modal_edit).LoadingOverlay("show", {image: "", fontawesome: "icon-spinner3 spinner"});
    $.ajax({
        type: "POST",
        url: $(this).data('action'),
        dataType: 'html',
        complete: function () {
            $(body_modal_edit).LoadingOverlay("hide", true);
        },
        success: function (content) {
            $(body_modal_edit).html(content);
        },
        error: function (jqXHR) {
            my_alert(jqXHR.responseText, 'danger', '#modal-message-alert');
        }
    });
});