jQuery(document).ready(function($) {

    $("form#signup").bind("keypress", function (e) {
      if (e.keyCode == 13) {
        return false;
      }
    });

    checkCaptcha = function(captchanumber) {
	$("div.captcha_dialog" + captchanumber).dialog({
            title: 'Captcha',
            modal: true,
            height: 200,
            width: 350,
            buttons: {
                "Cancel": function() {
                    $(this).dialog("close");
                    return false;
                },
                'Submit': function() {
                    if (validateCaptcha(captchanumber)) {
                        $(this).dialog("close");
                        $("#submitonce" + captchanumber).click();
                    }
                }
            }

	});
    }

    $("#submitbutton, #submitbutton1, #submitbutton2, #submitbutton3").click(function (e) {
	e.preventDefault();
	var captchanumber = $(this).attr('captchanumber');
	checkCaptcha(captchanumber);
	return true;
    });

    function validateCaptcha(captchanumber) {
	var extraId = '';
	if (captchanumber) {
            extraId = '-' + captchanumber;
        }
	if ($("#g-recaptcha-response" + extraId).val()) {
	    var callbackURL = '/civicrm/ajax/rest?className=CRM_Brennancentre_Page_AJAX&fnName=validateCaptcha';
	    var tdest = $.ajax({
		type: 'POST',
		url: callbackURL, // The file we're making the request to
		dataType: 'html',
		async: false,
		data: {
		    captchaResponse: $("#g-recaptcha-response" + extraId).val() // The generated response from the widget sent as a POST parameter
		},
                timeout: 2000
            }).responseText;
	    var response = $.parseJSON(tdest);
	    if (response.isError == false) {
		return true;
	    }
	    return false;
	}
	else {
	    alert("Please fill the captcha!");
	    return false;
	}
    }

});
