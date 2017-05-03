$(document).ready(function () {
    $('#createMachine').submit(function (event) {
        event.preventDefault();


        let host = {};
        $('input[name*="host"]').each(function(){
            host[this.id] = $(this).val();
        });

        host = JSON.stringify(host);

        let packages = $('[name="packages[]"]').val();

        let playbooks = '[';

        $.ajax({
            type: 'GET',
            url: '/playBookJSON',
            data: {
                playbook: 'clean'
            }
        }).done(function (data) {
            playbooks += data;
            playbooks += ',';
            $.ajax({
                type: 'GET',
                url: '/playBookJSON',
                data: {
                    playbook: 'machine',
                    host: host
                }
            }).done(function (data) {
                playbooks += data;
                playbooks += ',';

                $.ajax({
                    type: 'GET',
                    url: '/playBookJSON',
                    data: {
                        playbook: 'wait',
                    }
                }).done(function (data) {
                    playbooks += data;
                    playbooks += ',';

                    $.ajax({
                        type: 'GET',
                        url: '/playBookJSON',
                        data: {
                            playbook: 'package',
                            packages: packages
                        }
                    }).done(function (data) {
                        playbooks += data;
                        playbooks += ']';

                        console.log(playbooks);

                        $.ajax({
                            type: 'POST',
                            url: 'http://localhost:8080/post_data',
                            data: playbooks,
                            dataType: 'json'
                        }).done(function (response) {
                            $('#result').html(JSON.stringify(response));
                        }).fail(function (error) {
                            console.log(JSON.stringify(error));
                        });
                    }).fail(function (error) {
                        console.log(JSON.stringify(error));
                    });
                }).fail(function (error) {
                    console.log(JSON.stringify(error));
                });
            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });
    });
});