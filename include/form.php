<?php

// Processing if the form was posted
if ($_POST["form_email_address"]) {

	// We need to validate form inputs

	// Make the API call to Mill33.com
	$service_url = "http://clients.mill33.com/api/subscriber_lists/$listid_value/subscribers";

	$curl = curl_init($service_url);
	
	$curl_post_data = array(
		'email' => $_POST["form_email_address"],
		'email_format' => 'text',
	);

	$curl_headers = array(
		"Authorization: Token token=\"$apikey_value\"",
	);


	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_VERBOSE, true);
		curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $curl_headers);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
	$curl_response = curl_exec($curl);
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	// Check the response
	if ($http_status == '202') {
		echo "<center><h2>You have been subscribed to the mailing list.</h2></center>";
	} else {
		// Display Error
		echo "<center><h2>We were unable to subscribe you to the mailing list. Please contact $contactemail_value for assistance.</h2></center>";
	}
}

?>

<script type="text/javascript">

function focuson() { document.cfwc_contactform.number.focus()}

function check(){
var str1 = document.getElementById("contact_email").value;
var filter=/^(.+)@(.+).(.+)$/i
if (!( filter.test( str1 ))){alert("Incorrect email address!");return false;}
if(document.getElementById("recaptcha_response_field").value=="")
   {
       alert("Please enter captcha");
       return false;
   }
}
</script>

<div id="wp_mill33_subscribe">
<form action="" method="POST" name="wp_mill33_subscribe_form" onsubmit="return check();">

<center><h2>Subscribe to the <?php echo $listname_value; ?> mailing list.</h2></center>

<table>
<tbody>
	<tr>
  	<td>Email Address: </td>
  	<td><input name="form_email_address" type="text" value="<?php if(isset($_POST['form_email_address']) && !$resp->is_valid ) echo $_POST['form_email_address']; ?>"/></td>
	</tr>
</tbody>
</table>

<input name="submit" value="Submit" type="submit">

</form>
</div>
