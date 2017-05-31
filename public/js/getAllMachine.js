$(document).ready(function () {

    $('#getAllMachine').click(function (event) {
        event.preventDefault();

        $.ajax({
            type: 'GET',
            url: 'PlayBook',
            data: {
                playbook: 'getallmachine'
            }
        }).done(function () {

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        })
    });

});
