$(document).ready(function () {

    $('select').select2(
        {
            placeholder: "Choose ...",
            closeOnSelect: false,
        });

    let scrollTop;
    $('select').on("select2:selecting", function( event ){
        let $pr = $('#' + event.params.args.data._resultId).parent();
        scrollTop = $pr.prop('scrollTop');
    });
    $('select').on("select2:select", function( event ){
        let $pr = $('#' + event.params.data._resultId).parent();
        $pr.prop('scrollTop', scrollTop );
    });

    $("#selectAllorNot").click(function()
    {
        if($("#selectAllorNot").is(':checked'))
        {
            let selectedItems = [];
            let allOptions = $("#packages").find('option');
            allOptions.each(function() {
                selectedItems.push($(this).val());
            });
            $("#packages").val(selectedItems).trigger("change");
        }
        else
        {
            $('#packages').find('option').removeAttr('selected').parent().trigger('change');
        }
    });

    $('input[type="checkbox"]').on('change', function() {
        $('input[name="' + this.name + '"]').not(this).prop('checked', false);

        let nginx_document_root = $('#nginx_document_root')[0];
        let apache_document_root = $('#apache_document_root')[0];

        let mysql_database = $('#mysql_database')[0];
        let mysql_root_password = $('#mysql_root_password')[0];
        let mysql_new_user = $('#mysql_new_user')[0];
        let mysql_new_user_password = $('#mysql_new_user_password')[0];

        let mongodb_database = $('#mongodb_database')[0];
        let mongodb_new_user = $('#mongodb_new_user')[0];
        let mongodb_new_user_password = $('#mongodb_new_user_password')[0];

        nginx_document_root.disabled = !$('#nginxCheckbox').is(":checked");

        apache_document_root.disabled = !$('#apacheCheckbox').is(":checked");

        if($('#mongodbCheckbox').is(":checked"))
        {
            mongodb_new_user.disabled = false;
            mongodb_new_user_password.disabled = false;
            mongodb_database.disabled = false;
        }
        else
        {
            mongodb_new_user.disabled = true;
            mongodb_new_user_password.disabled = true;
            mongodb_database.disabled = true;
        }

        if($('#mysqlCheckbox').is(":checked"))
        {
            mysql_new_user.disabled = false;
            mysql_new_user_password.disabled = false;
            mysql_root_password.disabled = false;
            mysql_database.disabled = false;
        }
        else
        {
            mysql_new_user.disabled = true;
            mysql_new_user_password.disabled = true;
            mysql_root_password.disabled = true;
            mysql_database.disabled = true;
        }
    });
});