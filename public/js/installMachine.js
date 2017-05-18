$(document).ready(function () {

    let playbooks = [];
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
            url: '/playBookJSON',
            data: {
                playbook: 'clean',
                tmp_file: random_string
            }
        }).done(function (clean) {
            playbooks.push(JSON.parse(clean));

            $.ajax({
                type: 'GET',
                url: '/playBookJSON',
                data: {
                    playbook: 'installmachine',
                    host: host,
                    tmp_file: random_string
                }
            }).done(function (installmachine) {
                playbooks.push(JSON.parse(installmachine));

                $.ajax({
                    type: 'GET',
                    url: '/playBookJSON',
                    data: {
                        playbook: 'waitssh',
                        tmp_file: random_string
                    }
                }).done(function (waitssh) {
                    playbooks.push(JSON.parse(waitssh));

                    $.ajax({
                        type: 'GET',
                        url: '/playBookJSON',
                        data: {
                            playbook: 'update',
                            tmp_file: random_string
                        }
                    }).done(function (update) {
                        playbooks.push(JSON.parse(update));

                        $.ajax({
                            type: 'GET',
                            url: '/playBookJSON',
                            data: {
                                playbook: 'installdependencies',
                                tmp_file: random_string
                            }
                        }).done(function (installdependencies) {

                            playbooks.push(JSON.parse(installdependencies));

                            $.ajax({
                                type: 'GET',
                                url: '/playBookJSON',
                                data: {
                                    playbook: 'installpackage',
                                    packages: packages,
                                    tmp_file: random_string
                                }
                            }).done(function (installpackage) {
                                playbooks.push(JSON.parse(installpackage));

                                $.when(

                                    checkDatabase(),
                                    checkWebServer()

                                ).then(function () {

                                    console.log(JSON.stringify(playbooks));
                                    $.ajax({
                                        type: 'POST',
                                        url: api_ip+'/post_data',
                                        data: JSON.stringify(playbooks),
                                        dataType: 'json',
                                        timeout: 0
                                    }).done(function (response) {

                                        console.log(response);
                                        $('#result').html(response[2].invocation.module_args.host);

                                    }).fail(function (error) {
                                        console.log(JSON.stringify(error));
                                    });

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
                url: '/playBookJSON',
                data: {
                    playbook: 'apache',
                    tmp_file: random_string,
                    document_root: $('#apache_document_root').val(),
                }
            }).done(function (apache) {
                playbooks.push(JSON.parse(apache));
            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }

        else if($('#nginxCheckbox').is(':checked'))
        {
            return $.ajax({
                type: 'GET',
                url: '/playBookJSON',
                data: {
                    playbook: 'nginx',
                    tmp_file: random_string,
                    document_root: $('#nginx_document_root').val(),
                }
            }).done(function (nginx) {
                playbooks.push(JSON.parse(nginx));
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
                url: '/playBookJSON',
                data: {
                    playbook: 'mysql',
                    tmp_file: random_string,
                    mysql_root_password: $('#mysql_root_password').val(),
                    mysql_new_user: $('#mysql_new_user').val(),
                    mysql_new_user_password: $('#mysql_new_user_password').val(),
                    mysql_database: $('#mysql_database').val(),
                }
            }).done(function (apache) {
                playbooks.push(JSON.parse(apache));
            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }

        else if($('#mongodbCheckbox').is(':checked'))
        {
            return $.ajax({
                type: 'GET',
                url: '/playBookJSON',
                data: {
                    playbook: 'mongodb',
                    tmp_file: random_string,
                    mongodb_new_user: $('#mongodb_new_user').val(),
                    mongodb_new_user_password: $('#mongodb_new_user_password').val(),
                    mongodb_database: $('#mongodb_database').val(),
                }
            }).done(function (apache) {
                playbooks.push(JSON.parse(apache));
            }).fail(function (error) {
                console.log(JSON.stringify(error));
            });
        }
    }

});