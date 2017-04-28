$(document).ready(function () {
            $('#installServer').submit(function (event) {
                event.preventDefault();

                let serverpackages = $('[name="serverpackages[]"]').val();

                let playbooks = '[';

                $.ajax({
                    type: 'GET',
                    url: '/playBookJSON',
                    data: {
                        playbook: 'machine',
                    }
                }).done(function(data) {
                    playbooks += data;
                    playbooks += ',';
                    $.ajax({
                        type: 'GET',
                        url: '/playBookJSON',
                        data: {
                            playbook: 'package',
                            packages: serverpackages
                        }
                    }).done(function(data) {
                        playbooks += data;
                        playbooks += ']';

                        $.ajax({
                            type: 'POST',
                            url: 'http://localhost:8080/post_data',
                            data: playbooks,
                            dataType : 'json'
                        }).done(function(data) {
                            console.log("ok");
                            $('#result')
                                .html(data);
                        }).fail(function (err) {
                            console.log("not ok");
                        });

                    }).fail(function () {
                        console.log('failed to generate the package json');
                    });
                }).fail(function () {
                    console.log('failed to generate the machine json');
                });

            });
});