$(document).ready(function () {

    var machineTable = '';

    $('#getAllMachine').click(function (event) {
        event.preventDefault();

        $.ajax({
            type: 'GET',
            url: 'PlayBook',
            data: {
                playbook: 'getallmachine'
            }
        }).done(function () {

            machineTable.ajax.reload();

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        })
    });

    machineTable = $('#machineTable').DataTable({
        responsive: true,
        ajax: {
            url: 'GetAllMachine',
            type: 'GET'
        },
        columns: [
            {"data": "id"},
            {"data": "name"},
            {"data": "ip"},
            {"data": "location"},
            {"data": "status"},
            {"data": "action"}
        ]
    });

});

$('#confirmDeleteModal').on('show.bs.modal', function (e) {

    var form = $(e.relatedTarget).closest('form');

    $('#machinetodelete').val(form[0].name.value);

});

function check(form) {
    if (form.confirmToDelete.checked === false) {

        alert('You must check the checkbox!');
        return false;

    } else {

        $.ajax({
            type: 'GET',
            url: 'PlayBook',
            data: {
                playbook: 'deletemachine',
                name: $('#machinetodelete').val()
            }
        }).done(function () {

            machineTable.ajax.reload();

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });

        $('#confirmDeleteModal').modal('hide');

        return false;
    }
}