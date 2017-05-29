$(document).ready(function () {

    let socket = null;

    let progessbar_count = 0;

    $('#name').keyup(function () {
        let hostname = $(this).val();

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

        progessbar_count = 0;

        $('#result').text('');

        $(".progress-bar").animate({
            width: '0%'
        }, 250);
        $('.progress-bar').text('0%');

        $('#SendToAnsibleApi').attr('disabled', true);

        $('#progress').addClass("active");

        let host = {};
        $('input[name*="host"]').each(function () {
            host[this.id] = $(this).val();
        });
        host = JSON.stringify(host);

        progessbar_count++;

        $.ajax({
            type: 'GET',
            url: 'PlayBook',
            data: {
                playbook: 'installmachine',
                host: host
            }
        }).done(function () {

            checkPackages();
            checkWebServer();
            checkDatabase();

        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });
    });


    function checkWebServer() {
        if ($('#apacheCheckbox').is(':checked')) {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'apache',
                    document_root: $('#apache_document_root').val(),
                }
            }).done(function () {

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }

        else if ($('#nginxCheckbox').is(':checked')) {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'nginx',
                    document_root: $('#nginx_document_root').val(),
                }
            }).done(function () {

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }
    }

    function checkDatabase() {
        if ($('#mysqlCheckbox').is(':checked')) {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'mysql',
                    mysql_root_password: $('#mysql_root_password').val(),
                    mysql_new_user: $('#mysql_new_user').val(),
                    mysql_new_user_password: $('#mysql_new_user_password').val(),
                    mysql_database: $('#mysql_database').val(),
                }
            }).done(function () {

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }

        else if ($('#mongodbCheckbox').is(':checked')) {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'mongodb',
                    mongodb_new_user: $('#mongodb_new_user').val(),
                    mongodb_new_user_password: $('#mongodb_new_user_password').val(),
                    mongodb_database: $('#mongodb_database').val(),
                }
            }).done(function () {

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }
    }

    function checkPackages() {

        let packages = $('[name="packages[]"]').val();

        if (packages) {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'installpackage',
                    packages: packages
                }
            }).done(function () {

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }
    }

    try {
        socket = new WebSocket('ws://' + window.location.hostname + ':9000');

        socket.onerror = function () {
            console.log('connection error');
        };

        socket.onopen = function () {
            console.log('connection open');
        };

        socket.onmessage = function (msg) {

            let progress = Math.round(100 / progessbar_count * 100) / 100;

            $(".progress-bar").animate({
                width: progress + '%'
            }, 1500);
            $('.progress-bar').text(progress + '%');

            $('#result').append('<p>' + msg.data + '</p>');

            progessbar_count--;

            if (progessbar_count === 0) {
                $('#progress').removeClass("active");

                $('#SendToAnsibleApi').removeAttr("disabled");
            }
        };

        socket.onclose = function () {
            console.log('connection closed');
        };

    }
    catch (e) {
        console.log(e);
    }

});
