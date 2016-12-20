jQuery(document).ready(function() {
var recaptcha4;
var myCallBacks = function() {
	    recaptcha4 = grecaptcha.render('recaptcha4', {
		'sitekey' : '6Ld9NSkTAAAAAFMX73jZa1RAC6ImDWDsQV3_icUn', //Replace this with your Site key
		'theme' : 'light'
            });
	
    
};
myCallBacks();
    checkCaptcha = function() {
	cj("div.captcha_dialog3").dialog({
            title: 'Captcha',
            modal: true,
            height: 200,
            width: 350,
open: function (event, ui) {
 cj('.ui-dialog').css('z-index',10000);
},
            buttons: {
                "Cancel": function() {
                    cj(this).dialog("close");
                    return false;
                },
                'Submit': function() {
                    if (validateCaptcha()) {
                        cj(this).dialog("close");
                        cj("#submitonce3").click();
                    }
                }
            }

	});
    }

    cj("#submitbutton3").click(function (e) {
	e.preventDefault();
	checkCaptcha();
	return true;
    });

    function validateCaptcha() {
        var recaptchaValue = cj("#g-recaptcha-response-2").val();
	if (recaptchaValue) {
	    var callbackURL = '/civicrm/ajax/rest?className=CRM_Brennancentre_Page_AJAX&fnName=validateCaptcha';
	    var tdest = cj.ajax({
		type: 'POST',
		url: callbackURL, // The file we're making the request to
		dataType: 'html',
		async: false,
		data: {
		    captchaResponse: recaptchaValue // The generated response from the widget sent as a POST parameter
		},
                timeout: 2000
            }).responseText;
	    var response = cj.parseJSON(tdest);
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
