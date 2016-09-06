CRM.$(function($) {
    $("#signup input.recaptchaSubmit").click(function () {
	/* Check if the captcha is complete */    
	CRM.$("div.captcha_dialog").dialog({
	    title: 'Captcha',
	    modal: true,
	    height: 200,
	    width: 350, 
	    buttons: {
		"Cancel": function() {
		    CRM.$(this).dialog("close");
		},
		'Submit': function() {
		    if (validateCaptcha()) {
			CRM.$(this).dialog("close");
		    }
		}
	    }
	});
	return false;
    });
    function validateCaptcha() {
	if ($("#g-recaptcha-response").val()) {
	    var callbackURL = CRM.url('civicrm/ajax/rest', {
		className: 'CRM_Brennancentre_Page_AJAX',
		fnName: 'validateCaptcha'
	    });
	    $.ajax({
		type: 'POST',
		url: callbackURL, // The file we're making the request to
		dataType: 'html',
		async: false,
		data: {
		    captchaResponse: $("#g-recaptcha-response").val() // The generated response from the widget sent as a POST parameter
		},
		success: function (data) {
		    var response = $.parseJSON(data);
		    if (response.isError == false) {
			return true;
		    }
		    alert(response.error_message);
		    return false;
		}
	    });
	}
	else {
	    alert("Please fill the captcha!");
	    return false;
	}
    }
});
