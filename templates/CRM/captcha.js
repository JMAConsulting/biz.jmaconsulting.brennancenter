CRM.$(function($) {
    $("#signup input.form-submit").click(function () {
	/* Check if the captcha is complete */
	validateCaptcha();
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
