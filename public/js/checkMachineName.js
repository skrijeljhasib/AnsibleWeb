$(document).ready(function () {
    /*let machines = [];
    $('#pleaseWaitDialog').modal();

    $.ajax({
        type: 'GET',
        url: 'PlayBook',
        data: {
            playbook: 'getAllMachine'
        }
    }).done(function () {

        $.ajax({
            type: 'POST',
            url: api_ip+'/post_data',
            data: playbooks,
            dataType: 'json'
        }).done(function (response) {

            for (let i = 0; i < response[0].ansible_facts.openstack_servers.length; i++)
            {
                machines.push(response[0].ansible_facts.openstack_servers[i].name);
            }

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        }).then(function () {
            $('#pleaseWaitDialog').modal('hide');

            let hostname = $('#name');
            hostname.keyup(function() {
                if($.inArray(hostname.val(),machines) !== -1)
                {
                    hostname.val("");
                    hostname.attr("placeholder", "Name already taken. Type an other one.");
                }
            });
        });

    }).fail(function (error) {
        console.log(JSON.stringify(error));
    })*/

});