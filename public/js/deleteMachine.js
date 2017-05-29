$(document).ready(function () {

    $('#nametodelete').keyup(function() {
        let hostname = $(this).val();
	if (hostname.length <3) return; 

        $.ajax({
            type: 'GET',
            url: 'ListHostName',
            data: {
		nametodelete: hostname
		}
        }).done(function (data) {
	if (data == "null") {
		$('#namedelete').css('border-color','red');
                $('#deletebtn').attr('disabled','disabled');
		return;
	}
	var obj = JSON.parse(data);
	var length = Object.keys(obj).length;
	if(length > 0) {
                $('#nametodelete').css('border-color','green');
		$('#deletebtn').removeAttr('disabled');
            }
            else {
                $('#namedelete').css('border-color','red');
		$('#deletebtn').attr('disabled','disabled');
            }
        }).fail(function (error) {
            console.log(JSON.stringify(error));
        });
    });

    $('#confirmDeleteMachine').click(function (event) {
        event.preventDefault();

            $.ajax({
                type: 'GET',
                url: 'PlayBook',
                data: {
                    playbook: 'deletemachine',
		    name: document.getElementById("nametodelete").value ,
		    location: 'BHS1' 
                }
            })
	    .done(function () {
	    });
	});

});
