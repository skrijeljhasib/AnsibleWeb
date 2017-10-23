$(document).ready(function () {
    
    var templatejson = $('#selected').text();
    $('.dropdown-menu a').click(function(){
	$('#selected').text($(this).text());
    	templatejson = $(this).text();
    });

    var websocket_server = 'ws://' + window.location.hostname + ':9000';
    var socket = null;
    var finalName = '';

    $('#name').bind('input propertychange', function () {
        var hostname = $(this).val();
	if (/^(?=.{1,255}$)[0-9a-z](?:(?:[0-9a-z]|-){0,61}[0-9a-z])?(?:\.[0-9a-z](?:(?:[0-9a-z]|-){0,61}[0-9a-z])?)*\.?$/.test(hostname)) {
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
	} else {
		$('#name').css('border-color', 'red');
                $('#SendToAnsibleApi').attr('disabled', true);
	}
});

	websocket();

    $('#createMachine').submit(function (event) {
        event.preventDefault();
        var isHidden = $( "#outputbody" ).is( ":hidden" );
        if(isHidden) {
            $('#outputbody').toggle();
        }
        finalName = $('#name').val();
        $('#result').text('');
        $('#SendToAnsibleApi').attr('disabled', true);
        $('#expertbtn').removeAttr("data-toggle");
        $('#expertbtn').attr('disabled', true);
	$('#templateBtn').attr('disabled', true);
        $('#name').attr('disabled', true);
        $('#ProgressName').html('<b>' + $('#name').val() + '</b>');
        $('#progress').addClass("active");
        installmachine();
    });

    function checkHost() {
        var host = {};
        $('input[name*="host"]').each(function () {
            host[this.id] = $(this).val();
        });
        return JSON.stringify(host);
    }

    function checkWebServer() {
        var array = {};
        if ($('#apacheCheckbox').is(':checked')) {
            array['webserver'] = 'apache';
            array['document_root'] = $('#apache_document_root').val();
	    array['owner_directory'] = $('#apache_owner_directory').val();
            return JSON.stringify(array);
        } else if ($('#nginxCheckbox').is(':checked')) {
            array['webserver'] = 'nginx';
            return JSON.stringify(array);
        } else {
            return null;
        }
    }

    function checkProject() {
	var array = [];
	if ($('#connectCheckbox').is(':checked')) { array.push("connect"); }
	if ($('#connectadminCheckbox').is(':checked')) { array.push("connectadmin"); }
        if ($('#translateCheckbox').is(':checked')) { array.push("translate"); }
	if ($('#esmCheckbox').is(':checked')) { array.push("esm"); }
        if ($('#loggerCheckbox').is(':checked')) { array.push("logger"); }
        if ($('#mailerCheckbox').is(':checked')) { array.push("mailer"); }
        if ($('#auditCheckbox').is(':checked')) { array.push("audit"); }
        if ($('#filerCheckbox').is(':checked')) { array.push("filer"); }
	if ($('#chatCheckbox').is(':checked')) { array.push("chat"); }
	if ($('#chatappCheckbox').is(':checked')) { array.push("chatapp"); }
	if ($('#notificationCheckbox').is(':checked')) { array.push("notification"); }
        if ($('#paymentCheckbox').is(':checked')) { array.push("payment"); }
	return JSON.stringify(array);
    }

    function checkDatabase() {
        var array = {};
        if ($('#mysqlCheckbox').is(':checked')) {
            array['database'] = 'mysql';
            array['mysql_root_password'] = $('#mysql_root_password').val();
            array['mysql_new_user'] = $('#mysql_new_user').val();
            array['mysql_new_user_password'] = $('#mysql_new_user_password').val();
            array['mysql_database'] = $('#mysql_database').val();
            return JSON.stringify(array);
        } else if ($('#mongodbCheckbox').is(':checked')) {
            array['database'] = 'mongodb';
            array['mongodb_new_user'] = $('#mongodb_new_user').val();
            array['mongodb_new_user_password'] = $('#mongodb_new_user_password').val();
            array['mongodb_database'] = $('#mongodb_database').val();
            return JSON.stringify(array);
        } else {
            return null;
        }
    }

    function checkPackages() {
        let packages = $('[name="packages[]"]').val();
        if (packages) {
            return JSON.stringify(packages);
        } else {
            return null;
        }
    }

    function checkLanguage() {
        var array = {};
        if ($('#phpCheckbox').is(':checked')) {
            array['language'] = 'php';
            array['php_version'] = $('#php_version option:selected').val();
            return JSON.stringify(array);
        } else {
            return null;
        }
    }

    function checkTemplateJson() {
        return JSON.stringify(templatejson);
    }

    function checkDns() {
        var array = {};
        if ($('#dnsCheckbox').is(':checked')) {
            array['dns_domain_name'] = $('#dns_domain_name').val();
            array['dns_type'] = $('#dns_type').val();
            return JSON.stringify(array);
        } else {
            return null;
        }
    }

    function installmachine() {
                $.ajax({
                    type: 'GET',
                    url: 'api/PlayBook',
                    data: {
                        playbook: 'installmachine',
                        host: checkHost(),
                        packages: checkPackages(),
                        webserver: checkWebServer(),
                        database: checkDatabase(),
                        language: checkLanguage(),
			templatejson : checkTemplateJson(),
                        dns: checkDns(),
			project: checkProject()
                    }
                }).done(function () {
                    $.ajax({
                        type: 'GET',
                        url: 'GetNewMachineData',
                        data: {
                            name: finalName
                        }
                    }).done(function (data) {
                        var machine = JSON.parse(data);
                        $('#ipaddress').html('<b>' + machine.ip) + '</b>';
                        $('#status').html('<b>' + machine.status + '</b>');
                    }).fail(function (error) {
                        console.log(JSON.stringify(error));
                    });
                }).fail(function (error) {
                    console.log(JSON.stringify(error));
                });
    }

    function websocket() {
        try {
            socket = new WebSocket(websocket_server);
            socket.onerror = function () {
                console.log('connection error');
            };
	    socket.onopen = function () {
                console.log('connection open');
            };

            socket.onmessage = function (msg) {
                var data = JSON.parse(msg.data);
                if ("progress" in data) {
			var percent = parseInt(document.getElementById("progress-bar-animated").style.width.replace('%','')) + 2;
			document.getElementById("progress-bar-animated").style.width = percent + '%';
                }
                if ("task" in data) {

                    if (data.progress == "0") {
                        $('#task').text(data.task);
                        $('#result').append('Start (' + new Date().toLocaleString('en-GB') + ') : <b>' + data.task + '</b><br>');
                    }
                    if ((data.progress == "100") && (data.task != "Install Machine Notification")) {
                        $('#task').text(data.task);
                        $('#result').append('Stop  (' + new Date().toLocaleString('en-GB') + ') : <b>' + data.task + '</b><br>');
                    }
		    if ((data.progress == "100") && (data.task == "Install Ubuntu 16.04")) {
			$.ajax({
                        	type: 'GET',
                        	url: 'GetNewMachineData',
                        	data: {
                            		name: finalName
                        	}
                    	}).done(function (data) {
                        	var machine = JSON.parse(data);
                        	$('#ipaddress').html('<b>' + machine.ip + '</b>');
                    	}).fail(function (error) {
                        	console.log(JSON.stringify(error));
                    	});
                    }
                    if ((data.progress == "100") && (data.task == "Install Machine Notification")) {
			$('#result').append('Stop  (' + new Date().toLocaleString('en-GB') + ') : <b>' + data.task + '</b><br>');
                        $('#name').val('');
                        $('#name').css('border-color', '');
                        $('#progress').removeClass("active");
                        $('#SendToAnsibleApi').removeAttr("disabled");
                        $('#expertbtn').removeAttr("disabled");
                        $('#expertbtn').attr('data-toggle', 'collapse');
			$('#templateBtn').removeAttr("disabled");
                        $('#name').removeAttr("disabled");
                        $('#task').text('Installation Completed');
                        $('#result').append('Installation Completed<br>');
			document.getElementById("progress-bar-animated").style.width = '100%';
                        socket.close();
                    }

                }
            };

            socket.onclose = function () {
                console.log('connection closed');
                $('#name').val('');
                $('#progress').removeClass("active");
                $('#SendToAnsibleApi').removeAttr("disabled");
                $('#expertbtn').removeAttr("disabled");
		$('#templateBtn').removeAttr("disabled");
                $('#name').removeAttr("disabled");
                $.ajax({
                    type: 'GET',
                    url: 'GetNewMachineData',
                    data: {
                        name: finalName
                    }
                }).done(function (data) {
                    var machine = JSON.parse(data);
                    $('#ipaddress').html('<b>' + machine.ip + '</b>');
                    $('#status').html('<b>' + machine.status + '</b>');
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
