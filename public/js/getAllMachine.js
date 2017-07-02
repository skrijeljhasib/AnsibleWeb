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
            {"data": "hostgroup"},
            {"data": "ip"},
            {"data": "location"},
            {"data": "status"},
            {"data": "action"}
        ]
    });
    $('#machineTable_filter input').focus();

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
	$('#deletehostname').text(form[0].name.value);
    });

    $('#confirmSoftDeleteModal').on('show.bs.modal', function (e) {
        var form = $(e.relatedTarget).closest('form');
        $('#machinetosoftdelete').val(form[0].name.value);
	$('#deletedbhostname').text(form[0].name.value);
    });

    $('#hostEditModal').on('show.bs.modal', function (e) {
        var form = $(e.relatedTarget).closest('form');
        $('#machinetoedit').val(form[0].name.value);
	$('#edithostname').text(form[0].name.value);
	$('#hostgroup').val(form[0][1].value);
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

function edithost() {
        var output = document.getElementById('status');
        output.innerHTML = 'Modifying Host';
        $.ajax({
            type: 'GET',
            url: 'EditMachine',
            data: {
                name: $('#machinetoedit').val(),
		group: $('#hostgroup').val()
            }
        }).done(function () {

            machineTable.ajax.reload();

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });

        $('#hostEditModal').modal('hide');

        return false;
}

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

function deletesoft() {
    if (document.getElementById("confirmToSoftDeleteCheckBox").checked === false) {
        return false;
    } else {
        var output = document.getElementById('status');
        output.innerHTML = 'Deleting';
        $.ajax({
            type: 'GET',
            url: 'PlayBook',
            data: {
                playbook: 'deletemachinedb',
                name: $('#machinetosoftdelete').val()
            }
        }).done(function () {

            machineTable.ajax.reload();

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });

        $('#confirmSoftDeleteModal').modal('hide');

        return false;
    }
}
