CRM.$(function($) {
    $("#signup input.form-submit").click(function () {
	/* Check if the captcha is complete */
	if ($("#g-recaptcha-response").val()) {
	    var callbackURL = CRM.url('civicrm/ajax/rest', {
		className: 'CRM_Brennancentre_Page_Captcha',
		fnName: 'validateCaptcha'
	    });
	    $.ajax({
		type: 'POST',
		url: callbackURL, // The file we're making the request to
		dataType: 'html',
		async: true,
		data: {
		    captchaResponse: $("#g-recaptcha-response").val() // The generated response from the widget sent as a POST parameter
		},
		success: function (data) {
		    return true;
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
		    alert("Invalid Captcha");
		    return false;
		}
	    });
	} 
	else {
	    alert("Please fill the captcha!");
	    return false;
	}
    });
});
