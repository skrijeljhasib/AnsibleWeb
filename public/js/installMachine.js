$(document).ready(function () {

    var socket = null;
    var finalName = '';

    $('#name').bind('input propertychange', function() {
        var hostname = $(this).val();

        $.ajax({
            type: 'GET',
            url: 'CheckHostName',
            data: {
                name: hostname
            }
        }).done(function (data) {
            if (data === 'ok') {
                $('#name').css('border-color', 'green');
                $('#SendToAnsibleApi').attr('disabled', false);
            }
            else {
                $('#name').css('border-color', 'red');
                $('#SendToAnsibleApi').attr('disabled', true);
            }
        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });
    });

    $('#createMachine').submit(function (event) {
        event.preventDefault();

        $('#result').text('');

        $(".progress-bar").animate({
            width: '0%'
        }, 250);
        $('.progress-bar').text('0%');

        $('#SendToAnsibleApi').attr('disabled', true);

        $('#progress').addClass("active");

        $.ajax({
            type: 'GET',
            url: 'PlayBook',
            data: {
                playbook: 'installmachine',
                host: checkHost(),
                packages: checkPackages(),
                webserver: checkWebServer(),
                database: checkDatabase()
            }
        }).done(function () {

            finalName = $('#name').val();

            websocket();

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });
    });


    function checkHost() {
        var host = {};

        $('input[name*="host"]').each(function () {
            host[this.id] = $(this).val();
        });

        return JSON.stringify(host);
    }

    function checkWebServer() {
        if ($('#apacheCheckbox').is(':checked')) {

            var array = {};

            array['webserver'] = 'apache';
            array['document_root'] = $('#apache_document_root').val();

            return JSON.stringify(array);
        }

        else if ($('#nginxCheckbox').is(':checked')) {

            var array = {};

            array['webserver'] = 'nginx';
            array['document_root'] = $('#nginx_document_root').val();

            return JSON.stringify(array);

        }
        else {
            return null;
        }
    }

    function checkDatabase() {
        if ($('#mysqlCheckbox').is(':checked')) {

            var array = {};

            array['database'] = 'mysql';
            array['mysql_root_password'] = $('#mysql_root_password').val();
            array['mysql_new_user'] = $('#mysql_new_user').val();
            array['mysql_new_user_password'] = $('#mysql_new_user_password').val();
            array['mysql_database'] = $('#mysql_database').val();

            return JSON.stringify(array);
        }

        else if ($('#mongodbCheckbox').is(':checked')) {

            var array = {};

            array['database'] = 'mongodb';
            array['mongodb_new_user'] = $('#mongodb_new_user').val();
            array['mongodb_new_user_password'] = $('#mongodb_new_user_password').val();
            array['mongodb_database'] = $('#mongodb_database').val();

            return JSON.stringify(array);
        }
        else
        {
            return null;
        }
    }

    function checkPackages() {

        let packages = $('[name="packages[]"]').val();

        if (packages) {
            return JSON.stringify(packages);
        }
        else {
            return null;
        }
    }

    function websocket() {

        try {
            socket = new WebSocket('ws://' + window.location.hostname + ':9000');

            socket.onerror = function () {
                console.log('connection error');
            };

            socket.onopen = function () {
                console.log('connection open');

                $.ajax({
                    type: 'GET',
                    url: 'GetNewMachineData',
                    data: {
                        name: finalName
                    }
                }).done(function (data) {

                    var machine = JSON.parse(data);

                    $('#ipaddress').text(machine.ip);
                    $('#status').text(machine.status);

                }).fail(function (error) {
                    console.log(JSON.stringify(error));
                });
            };

            socket.onmessage = function (msg) {

                var data = JSON.parse(msg['data']);

                if("task" in data) {
                    $('#task').text(data.task);
                }

                if("callback" in data) {
                    $('#result').append('<p>' + JSON.stringify(data.callback) + '</p>');
                }

                if("progress" in data) {
                    $(".progress-bar").animate({
                        width: data.progress + '%'
                    }, 1500);
                    $('.progress-bar').text(data.progress + '%');

                    if (data.progress === '100') {

                        $('#name').val('');

                        $('#progress').removeClass("active");

                        $('#SendToAnsibleApi').removeAttr("disabled");

                        socket.close();
                    }
                }
            };

            socket.onclose = function () {
                console.log('connection closed');

                $.ajax({
                    type: 'GET',
                    url: 'GetNewMachineData',
                    data: {
                        name: finalName
                    }
                }).done(function (data) {

                    var machine = JSON.parse(data);

                    $('#ipaddress').text(machine.ip);
                    $('#status').text(machine.status);

                }).fail(function (error) {
                    console.log(JSON.stringify(error));
                });
            };

        }
        catch (e) {
            console.log(e);
        }
    }
});
