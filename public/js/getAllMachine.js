var machineTable;


$('#confirmDeleteModal').on('show.bs.modal', function (e) {

    var form = $(e.relatedTarget).closest('form');

    $('#machinetodelete').val(form[0].name.value);

});

machineTable = $('#machineTable').DataTable({
    iDisplayLength: 25,
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

$('#getAllMachine').click(function (event) {
    event.preventDefault();

    $.ajax({
        type: 'GET',
        url: 'PlayBook',
        data: {
            playbook: 'getallmachine'
        }
    }).done(function () {

        $('#refresh').attr('disabled', true);

        $('#status').text('Please wait ...');

        setTimeout(function(){
            machineTable.ajax.reload();
            $('#status').text('Done');
            $('#refresh').attr('disabled', false);

        }, 60000);

    }).fail(function (error) {
        console.log(JSON.stringify(error));
    })
});


function check(form) {
    if (form.confirmToDelete.checked === false) {

        alert('You must confirm at first!');
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

            setTimeout(function(){
                machineTable.ajax.reload();
            }, 60000);

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });

        $('#confirmDeleteModal').modal('hide');

        return false;
    }
}
