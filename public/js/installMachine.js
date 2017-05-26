$(document).ready(function () {

    let socket = null;

    let progessbar_count = 0;

    let random_string = Math.random().toString(36).substring(7);

    $('#createMachine').submit(function (event) {
        event.preventDefault();

        $('#result').text('');

        $(".progress-bar").animate({
            width: '0%'
        }, 250);
        $('.progress-bar').text('0%');

        $('#SendToAnsibleApi').attr('disabled', true);

        $('#progress').addClass("active");

        progessbar_count = 0;

        let host = {};
        $('input[name*="host"]').each(function () {
            host[this.id] = $(this).val();
        });
        host = JSON.stringify(host);

        let packages = $('[name="packages[]"]').val();

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'clean',
                    tmp_file: random_string
                }
            }).done(function () {

                progessbar_count++;
                socket.send('Clean');

                $.ajax({
                    type: 'GET',
                    url: 'PlayBook',
                    data: {
                        playbook: 'installmachine',
                        host: host,
                        tmp_file: random_string
                    }
                }).done(function () {

                    progessbar_count++;
                    socket.send('Install Machine');

                    $.ajax({
                        type: 'GET',
                        url: 'PlayBook',
                        data: {
                            playbook: 'waitssh',
                            tmp_file: random_string
                        }
                    }).done(function () {

                        progessbar_count++;
                        socket.send('Wait SSH');

                            $.ajax({
                                type: 'GET',
                                url: 'PlayBook',
                                data: {
                                    playbook: 'installdependencies',
                                    tmp_file: random_string
                                }
                            }).done(function () {

                                progessbar_count++;
                                socket.send('Install Dependencies');

                                checkPackages(packages);
                                checkDatabase();
                                checkWebServer();

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


    function checkWebServer()
    {
        if($('#apacheCheckbox').is(':checked'))
        {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'apache',
                    tmp_file: random_string,
                    document_root: $('#apache_document_root').val(),
                }
            }).done(function () {
                socket.send('Install Webserver');
            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }

        else if($('#nginxCheckbox').is(':checked'))
        {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'nginx',
                    tmp_file: random_string,
                    document_root: $('#nginx_document_root').val(),
                }
            }).done(function () {
                socket.send('Install Webserver');
            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }
    }

    function checkDatabase()
    {
        if($('#mysqlCheckbox').is(':checked'))
        {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'mysql',
                    tmp_file: random_string,
                    mysql_root_password: $('#mysql_root_password').val(),
                    mysql_new_user: $('#mysql_new_user').val(),
                    mysql_new_user_password: $('#mysql_new_user_password').val(),
                    mysql_database: $('#mysql_database').val(),
                }
            }).done(function () {
                socket.send('Install Database');
            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }

        else if($('#mongodbCheckbox').is(':checked'))
        {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'mongodb',
                    tmp_file: random_string,
                    mongodb_new_user: $('#mongodb_new_user').val(),
                    mongodb_new_user_password: $('#mongodb_new_user_password').val(),
                    mongodb_database: $('#mongodb_database').val(),
                }
            }).done(function () {
                socket.send('Install Database');
            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }
    }

    function checkPackages(packages)
    {
        if(packages)
        {
            progessbar_count++;

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'installpackage',
                    packages: packages,
                    tmp_file: random_string
                }
            }).done(function () {
                socket.send('Install Packages');
            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }

    }

    try
    {
        socket = new WebSocket('ws://'+window.location.hostname+':9000');

        socket.onopen = function () {
            console.log('connection open');
        };

        socket.onmessage = function (msg) {

            let progress = Math.round(100/progessbar_count * 100) / 100;

            $(".progress-bar").animate({
                width: progress+'%'
            }, 1500);
            $('.progress-bar').text(progress+'%');

            $('#result').append('<p>'+msg.data+'</p>');

            progessbar_count--;

            if(progessbar_count === 0)
            {
                $('#progress').removeClass("active");

                $('#SendToAnsibleApi').removeAttr("disabled");
            }
        };

        socket.onclose = function () {
            console.log('connection closed');
        };

    }
    catch (e)
    {
        console.log(e);
    }

});