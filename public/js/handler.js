$(document).ready(function () {

    $("#selectAllorNot").click(function(){
        if($("#selectAllorNot").is(':checked') ){
            $('#packages').find('option').attr('selected', true).parent().trigger('change')
        } else {
            $('#packages').find('option').attr('selected', false).parent().trigger('change')
        }
    });

    const $loading = $('#loading').hide();
    $(document)
        .ajaxStart(function () {
            $loading.show();
        })
        .ajaxStop(function () {
            $loading.hide();
        });

    $('#myTabs').find('a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('select').select2({
        placeholder: "Choose ..."
    });
});