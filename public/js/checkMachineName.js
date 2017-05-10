$(document).ready(function () {

    if($('#name').val())
    {
        let hostname = $("#name");

        hostname.attr("disabled", "disabled");

        let machines = [];

        $.ajax({
            type: 'GET',
            url: '/playBookJSON',
            data: {
                playbook: 'getAllMachine'
            }
        }).done(function (data) {

            let playbooks = '[';
            playbooks += data;
            playbooks += ']';

            $.ajax({
                type: 'POST',
                url: api_ip + '/post_data',
                data: playbooks,
                dataType: 'json'
            }).done(function (response) {

                for (let i = 0; i < response[0].ansible_facts.openstack_servers.length; i++)
                {
                    machines.push(response[0].ansible_facts.openstack_servers[i].name);
                }

                console.log(machines);

                hostname.removeAttr("disabled");

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        }).then(function () {

            hostname.keyup(function() {
                if($.inArray(hostname.val(),machines) !== -1)
                {
                    hostname.val("");
                    hostname.attr("placeholder", "Name already taken. Type an other one.");
                }
            });
        });
    }


});