jQuery(document).ready(function($) {


checkCaptcha = function(event) {

$("div.captcha_dialog").dialog({
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
                    if (validateCaptcha()) {
                        $(this).dialog("close");
                        $("#submitonce").click();
                    }
                }
            }

});
}

    $("#submitbutton").click(function (e) {
      e.preventDefault();
      checkCaptcha();
      return true;
    });
    function validateCaptcha() {
	if ($("#g-recaptcha-response").val()) {
	    var callbackURL = '/civicrm/ajax/rest?className=CRM_Brennancentre_Page_AJAX&fnName=validateCaptcha';
	    var tdest = $.ajax({
		type: 'POST',
		url: callbackURL, // The file we're making the request to
		dataType: 'html',
		async: false,
		data: {
		    captchaResponse: $("#g-recaptcha-response").val() // The generated response from the widget sent as a POST parameter
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
