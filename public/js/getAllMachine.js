var machineTable;

function tabledeploy(bool) {
 if (bool) { return ''; }
 else { return 'danger'; }
}

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
	    console.log(msg.data);
            if (jsonObject.progress == "100") {
                output.innerHTML = 'Done';
		$('#status').removeClass('alert alert-warning');
                $('#status').addClass('alert alert-info');
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

    $('#confirmDeleteModal').on('show.bs.modal', function (e) {
        $('#status').html('Ready');
        $('#status').removeClass('alert alert-warning');
        $('#status').addClass('alert alert-info');
    });

    $('#confirmSoftDeleteModal').on('show.bs.modal', function (e) {
        var form = $(e.relatedTarget).closest('form');
        $('#machinetosoftdelete').val(form[0].name.value);
	$('#deletedbhostname').text(form[0].name.value);

    });

    $('#confirmSoftDeleteModal').on('hide.bs.modal', function (e) {
        $('#status').html('Ready');
        $('#status').removeClass('alert alert-warning');
        $('#status').addClass('alert alert-info');
    });

    $('#hostEditModal').on('show.bs.modal', function (e) {
        $('#status').html('Modifying Host');
	$('#status').removeClass('alert alert-info');
        $('#status').addClass('alert alert-warning');
        var form = $(e.relatedTarget).closest('form');
        $('#machinetoedit').val(form[0].name.value);
	$('#edithostname').text(form[0].name.value);
	$('#edithostgroup').val(form[0][1].value);
    });

    $('#hostEditModal').on('hide.bs.modal', function (e) {
	$('#status').html('Ready');
        $('#status').removeClass('alert alert-warning');
        $('#status').addClass('alert alert-info');
    });

    $('#hostEditStaticModal').on('show.bs.modal', function (e) {
        $('#status').html('Modifying Host');
	$('#status').removeClass('alert alert-info');
        $('#status').addClass('alert alert-warning');
        var form = $(e.relatedTarget).closest('form');
        $('#staticmachinetoedit').val(form[0].name.value);
        $('#editstatichostname').text(form[0].name.value);
        $('#statichostgroup').val(form[0][1].value);
	$('#statichostip').val(form[0][2].value);
	$('#statichostlocation').val(form[0][3].value);
    });

    $('#deployAppModal').on('show.bs.modal', function (e) {
        $('#status').html('Deploying Application');
        $('#status').removeClass('alert alert-info');
        $('#status').addClass('alert alert-warning');
        var form = $(e.relatedTarget).closest('form');
        $('#deployhostname').val(form[0].name.value);
        $('#deployip').val(form[0].hostip.value);
	$.ajax({
            type: 'GET',
            url: 'GetMachinesServices',
            data: {
                name: $('#deployhostname').val()
            }
        }).done(function (data) {
		myObj = JSON.parse(data);
            	var txt = '<table class="table table-responsive">';
		txt += '<tr class="' + tabledeploy(myObj.connect) + '"><td>connect</td><td>' + myObj.connect + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.connectadmin) + '"><td>connect-admin</td><td>' + myObj.connectadmin + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.logger) + '"><td>logger</td><td>' + myObj.logger + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.audit) + '"><td>audit</td><td>' + myObj.audit + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.filer) + '"><td>filer</td><td>' + myObj.filer + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.mailer) + '"><td>mailer</td><td>' + myObj.mailer + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.translate) + '"><td>translate</td><td>' + myObj.translate + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.payment) + '"><td>payment</td><td>' + myObj.payment + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.chat) + '"><td>chat</td><td>' + myObj.chat + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.chatdemo) + '"><td>chat-demo</td><td>' + myObj.chatdemo + '</td></tr>';
		txt += '<tr class="' + tabledeploy(myObj.esm) + '"><td>esm</td><td>' + myObj.esm + '</td></tr>';
		txt += '</table>';
		$('#projectlist').html(txt);

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        })
    });
  
    $('#deployAppModal').on('hide.bs.modal', function (e) {
        $('#status').html('Ready');
        $('#status').removeClass('alert alert-warning');
        $('#status').addClass('alert alert-info');
    });
 
    $('#hostEditStaticModal').on('hide.bs.modal', function (e) {
        $('#status').html('Ready');
        $('#status').removeClass('alert alert-warning');
        $('#status').addClass('alert alert-info');
    });

    $('#hostCreateStaticModal').on('hide.bs.modal', function (e) {
        $('#status').html('Ready');
        $('#status').removeClass('alert alert-warning');
        $('#status').addClass('alert alert-info');
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
	    $('#status').removeClass('alert alert-info');
            $('#status').addClass('alert alert-warning');
            $('#status').text('Please wait ...');

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        })
    });

});

function edithost() {
        $.ajax({
            type: 'GET',
            url: 'EditMachine',
            data: {
                name: $('#machinetoedit').val(),
		group: $('#edithostgroup').val()
            }
        }).done(function () {

            machineTable.ajax.reload();

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });
        $('#hostEditModal').modal('hide');
        return false;
}

function editstatichost() {
        $.ajax({
            type: 'GET',
            url: 'EditMachine',
            data: {
                name: $('#staticmachinetoedit').val(),
                group: $('#statichostgroup').val(),
        	ip: $('#statichostip').val(),
        	hostlocation: $('#statichostlocation').val()
            }
        }).done(function () {
            machineTable.ajax.reload();
        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });
        $('#hostEditStaticModal').modal('hide');
        return false;
}

function createstatichost() {
        $.ajax({
            type: 'GET',
            url: 'EditMachine',
            data: {
                name: $('#createstaticmachinetoedit').val(),
                group: $('#createstatichostgroup').val(),
                ip: $('#createstatichostip').val(),
		hoststatus: "STATIC",
                hostlocation: $('#createstatichostlocation').val()
            }
        }).done(function () {
            machineTable.ajax.reload();
        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });
        $('#hostCreateStaticModal').modal('hide');
        return false;
}

function check() {
    if (document.getElementById("confirmToDeleteCheckBox").checked === false) {
        return false;
    } else {
        var output = document.getElementById('status');
	$('#status').removeClass('alert alert-info');
        $('#status').addClass('alert alert-warning');
        output.innerHTML = 'Deleting';
        $.ajax({
            type: 'GET',
            url: 'PlayBook',
            data: {
                playbook: 'deletemachine',
                name: $('#machinetodelete').val()
            }
        }).success(function () {
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
	$('#status').removeClass('alert alert-info');
	$('#status').addClass('alert alert-warning');
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

function deployapp() {
	var array = [];
        array.push($('#selectproject').val());
        var output = document.getElementById('status');
        $('#status').removeClass('alert alert-info');
        $('#status').addClass('alert alert-warning');
        output.innerHTML = 'Deploying ' + $('#selectproject').val();
        $.ajax({
            type: 'GET',
            url: 'PlayBook',
            data: {
                playbook: 'redeployproject',
		name: $('#deployhostname').val(),
		ip: $('#deployip').val(),
                project: JSON.stringify(array)
            }
        }).done(function () {
            machineTable.ajax.reload();

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });

        $('#deployAppModal').modal('hide');
	$('#status').html('Deploying Application');
        $('#status').removeClass('alert alert-info');
        $('#status').addClass('alert alert-warning');

        return false;
}
