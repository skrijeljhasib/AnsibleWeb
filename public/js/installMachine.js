$(document).ready(function () {

    let random_string = Math.random().toString(36).substring(7);

    $('#createMachine').submit(function (event) {
        event.preventDefault();

        let host = {};
        $('input[name*="host"]').each(function(){
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

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'installmachine',
                    host: host,
                    tmp_file: random_string
                }
            }).done(function () {

                $.ajax({
                    type: 'GET',
                    url: 'PlayBook',
                    data: {
                        playbook: 'waitssh',
                        tmp_file: random_string
                    }
                }).done(function () {

                    $.ajax({
                        type: 'GET',
                        url: 'PlayBook',
                        data: {
                            playbook: 'update',
                            tmp_file: random_string
                        }
                    }).done(function () {

                        $.ajax({
                            type: 'GET',
                            url: 'PlayBook',
                            data: {
                                playbook: 'installdependencies',
                                tmp_file: random_string
                            }
                        }).done(function () {

                            $.ajax({
                                type: 'GET',
                                url: 'PlayBook',
                                data: {
                                    playbook: 'installpackage',
                                    packages: packages,
                                    tmp_file: random_string
                                }
                            }).done(function () {

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
            return $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'apache',
                    tmp_file: random_string,
                    document_root: $('#apache_document_root').val(),
                }
            }).done(function () {

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }

        else if($('#nginxCheckbox').is(':checked'))
        {
            return $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'nginx',
                    tmp_file: random_string,
                    document_root: $('#nginx_document_root').val(),
                }
            }).done(function () {

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }
    }

    function checkDatabase()
    {
        if($('#mysqlCheckbox').is(':checked'))
        {
            return $.ajax({
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

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }

        else if($('#mongodbCheckbox').is(':checked'))
        {
            return $.ajax({
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

            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }
    }

});