jQuery(document).ready(function() {

CRM.$("form#Edit").bind("keypress", function (e) {
      if (CRM.$('#splashify').length !== 0 && e.keyCode == 13) {
        return false;
      }
    });


var recaptcha4;
var myCallBacks = function() {
	    recaptcha4 = grecaptcha.render('recaptcha4', {
		'sitekey' : '6Ld9NSkTAAAAAFMX73jZa1RAC6ImDWDsQV3_icUn', //Replace this with your Site key
		'theme' : 'light'
            });
	
    
};
myCallBacks();
    checkCaptcha = function() {
	CRM.$("div.captcha_dialog3").dialog({
            title: 'Captcha',
            modal: true,
            height: 200,
            width: 350,
open: function (event, ui) {
 CRM.$('.ui-dialog').css('z-index',10000);
},
            buttons: {
                "Cancel": function() {
                    CRM.$(this).dialog("close");
                    return false;
                },
                'Submit': function() {
                    if (validateCaptcha()) {
                        CRM.$(this).dialog("close");
                        CRM.$("#submitonce3").click();
                    }
                }
            }

	});
    }

    CRM.$("#submitbutton3").click(function (e) {
	e.preventDefault();
	checkCaptcha();
	return true;
    });

    function validateCaptcha() {
        var recaptchaValue = CRM.$("#g-recaptcha-response-2").val();
	if (recaptchaValue) {
	    var callbackURL = '/civicrm/ajax/rest?className=CRM_Brennancentre_Page_AJAX&fnName=validateCaptcha';
	    var tdest = CRM.$.ajax({
		type: 'POST',
		url: callbackURL, // The file we're making the request to
		dataType: 'html',
		async: false,
		data: {
		    captchaResponse: recaptchaValue // The generated response from the widget sent as a POST parameter
		},
                timeout: 2000
            }).responseText;
	    var response = CRM.$.parseJSON(tdest);
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
