$(document).ready(function () {

    const loading = $('#loading').hide();
    $(document)
        .ajaxStart(function ()
        {
            loading.show();
        })
        .ajaxStop(function ()
        {
            loading.hide();
        });

    $('#myTabs').find('a').click(
        function (e)
        {
            e.preventDefault();
            $(this).tab('show');
        }
    );

    $('select').select2(
        {
            placeholder: "Choose ..."
        });

    $("#selectAllorNot").click(function()
    {
        if($("#selectAllorNot").is(':checked'))
        {
            var selectedItems = [];
            var allOptions = $("#packages").find('option');
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
});