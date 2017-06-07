var machineTable;

$(document).ready(function () {
    var websocket_server = 'ws://' + window.location.hostname + ':9000';
    var socket = null;
    var output = document.getElementById('status');
    var print = function (message) {
        output.innerHTML = message;
        return;
    };

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

    try {
        socket = new WebSocket(websocket_server);
        socket.onopen = function () {
            print('Ready');
            return;
        };
        socket.onmessage = function (msg) {
            var jsonObject = JSON.parse(msg.data);
            if (jsonObject.progress == "100") {
                output.innerHTML = 'Done';
                machineTable.ajax.reload();
                $('#refresh').attr('disabled', false);
            }
            return;
        };
        socket.onclose = function () {
            print('connection is closed');
            return;
        };
    } catch (e) {
        console.log(e);
    }

    $('#confirmDeleteModal').on('show.bs.modal', function (e) {
        var form = $(e.relatedTarget).closest('form');
        $('#machinetodelete').val(form[0].name.value);

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

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        })
    });

});

function check() {
    if (document.getElementById("confirmToDeleteCheckBox").checked === false) {
        return false;
    } else {
        var output = document.getElementById('status');
        output.innerHTML = 'Deleting';
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