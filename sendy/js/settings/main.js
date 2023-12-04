$(document).ready(function() {
	$("#settings-form").submit(function(e){
		e.preventDefault(); 
		
		var $form = $(this),
		uid = $form.find('input[name="uid"]').val(),
		personal_name = $form.find('input[name="personal_name"]').val(),
		company = $form.find('input[name="company"]').val(),
		email = $form.find('input[name="email"]').val(),
		password = $form.find('input[name="password"]').val(),
		aws_key = $form.find('input[name="aws_key"]').val(),
		aws_secret = $form.find('input[name="aws_secret"]').val(),
		paypal = $form.find('input[name="paypal"]').val(),
		timezone = $form.find('#timezone').val(),
		language = $form.find('#language').val(),
		ses_endpoint = $form.find('#ses_endpoint').val(),
		from_name = $form.find('input[name="from_name"]').val(),
		from_email = $form.find('input[name="from_email"]').val(),
		reply_to = $form.find('input[name="reply_to"]').val(),
		send_rate = $form.find('input[name="send_rate"]').val(),
		ses_send_rate = $form.find('input[name="ses_send_rate"]').val(),
		query_string = $form.find('input[name="query_string"]').val(),
		campaign_report_rows = $form.find('input[name="campaign_report_rows"]').val(),
		gdpr_only = $form.find('input[name="gdpr_only"]').prop('checked'),
		gdpr_only_ar = $form.find('input[name="gdpr_only_ar"]').prop('checked'),
		gdpr_options = $form.find('input[name="gdpr_options"]').prop('checked'),
		recaptcha_sitekey = $form.find('input[name="recaptcha_sitekey"]').val(),
		recaptcha_secretkey = $form.find('input[name="recaptcha_secretkey"]').val(),
		test_email_prefix = $form.find('input[name="test_email_prefix"]').val(),
		brands_rows = $form.find('input[name="brands_rows"]').val(),
		strict_delete = $form.find('input[name="strict_delete"]').val(),
		dark_mode = $form.find('input[name="dark_mode"]').val(),
		url = $form.attr('action');
		
		templates_lists_sorting = $("#sort-by").val();
		
		if(gdpr_only) gdpr_only = 1;
		if(gdpr_only_ar) gdpr_only_ar = 1;
		if(gdpr_options) gdpr_options = 1;
		
		//console.log(gdpr_only);
		
		//validate email
		AtPos = email.indexOf("@")
		StopPos = email.lastIndexOf(".")
		if (AtPos == -1 || StopPos == -1) email_valid = false;
		else email_valid = true;
		
		if(personal_name!="" && company!="" && email!="" && email_valid==true)
		$.post(url, { uid: uid, personal_name: personal_name, company: company, email: email, password: password, aws_key: aws_key, aws_secret: aws_secret, paypal: paypal, timezone: timezone, language:language, ses_endpoint:ses_endpoint, from_name: from_name, from_email: from_email, reply_to: reply_to, send_rate: send_rate, ses_send_rate: ses_send_rate, query_string: query_string, campaign_report_rows: campaign_report_rows, gdpr_only: gdpr_only, gdpr_only_ar: gdpr_only_ar, gdpr_options: gdpr_options, recaptcha_sitekey: recaptcha_sitekey, recaptcha_secretkey: recaptcha_secretkey, test_email_prefix: test_email_prefix, brands_rows: brands_rows, templates_lists_sorting: templates_lists_sorting, strict_delete: strict_delete, dark_mode: dark_mode },
		  function(data) {
		      if(data)
		      {
			      console.log('success');
		      	if(data=="email exists") $("#alert-error2").css("display", "block");
				else window.location = $("#redirect").val();
		      }
		      else
		      {
		      	$("#alert-error1").css("display", "block");
		      }
		  }
		);
	});
});