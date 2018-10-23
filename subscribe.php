<?php
use Medoo\Medoo;

include 'header.php';
require 'medoo.php';

if ($_SESSION['auth'] !== true) {
	header('Location: login.php');
	exit;
}

?>



<div class="panel panel-default">
<div class="panel-heading">
	<h3>
		<sup class="pull-right"><a href="login.php?act=logout">staff logout <span class="fa fa-sign-out"></span></a></sup>
		<strong>Subscribe Now</strong> - it's easy!
	</h3>
</div>
<div class="panel-body">

<?php


// just add 'bootstrap' as your wrapper when you instantiate Formr
$form = new Formr('bootstrap');
$form->id = 'subscribe';


if ($form->submit()) {

	$database = new Medoo([
		'database_type' => 'sqlite',
		'database_file' => 'bullcitycool.sqlite3'
	]);

	$mvp = $form->post("mvp");
	if (strlen($mvp) > 11)
	{
		$mvp = substr($mvp, 0, 11);
	}


	$count = $database->count("Subscribers", [
		"Code" => $mvp
	]);

	//Don't insert new records if the MVP number is already in the database
	if ($count == 0) {
		//Must start with 46
		if (substr($mvp,0,2) == "46")
		{
			$database->insert("Subscribers", [
				"FirstName" => $form->post("fname"),
				"LastName" => $form->post("lname"),
				"Phone" => $form->post("phone"),
				"Email" => $form->post("email"),
				"Notes" => $form->post("notes"),
				"Code" => $mvp,
				"User" => $_SESSION['auth_user']
			]);

			//DEVELOPMENT
			//CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/dev/copient-externalofferconnector/v1/addcustomertooffer/externalsourceid/cardnumber/clientofferid', array('cardnumber' => $mvp,'clientofferid' => '12540','externalsourceid' => 'AEM_FL'));
			// OLD DO NOT USE		CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/dev/copient-externalofferconnector/v1/addCustomers', array('cardnumber' => $mvp,'clientofferid' => '9999'));
			//PRODUCTION
			CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/prod/copient-externalofferconnector/v1/addcustomertooffer/externalsourceid/cardnumber/clientofferid', array('cardnumber' => $mvp,'clientofferid' => '164100','externalsourceid' => 'AEM_FL'));

			header('Location: thanks.php?d=0');
			exit;
		} else {
			echo('<p class="alert alert-danger">Valid MVP numbers should start with "46".</p>');
		}
	} else {
		$database->update("Subscribers", [
			"FirstName" => $form->post("fname"),
			"LastName" => $form->post("lname"),
			"Phone" => $form->post("phone"),
			"Email" => $form->post("email"),
			"Notes" => $form->post("notes"),
			"User" => $_SESSION['auth_user']
		],["Code" => $mvp]);

//PRODUCTION
		CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/prod/copient-externalofferconnector/v1/addcustomertooffer/externalsourceid/cardnumber/clientofferid', array('cardnumber' => $mvp,'clientofferid' => '164100','externalsourceid' => 'AEM_FL'));

		header('Location: thanks.php?d=1');
		exit;
	}
}




$form->required = 'fname,lname,phone,mvp';
$form->action = 'subscribe.php';
echo $form->form_open();

?>

<div class="pnl-subscribe">

<div class="col-xs-12">
<h3>Enrollment Details</h3>
</div>

<?php

echo '<div class="col-sm-6">';
echo $form->input_text('fname','First name:','','fname','placeholder="First name"');
echo '</div>';
echo '<div class="col-sm-6">';
echo $form->input_text('lname','Last name:','','lname','placeholder="Last name"');
echo '</div>';

echo '<div class="col-sm-6">';
echo $form->input_text('phone','Phone:','','phone','placeholder="Phone Number"');
echo '</div>';
echo '<div class="col-sm-6">';
echo $form->input_email('email','Email:','','email','placeholder="Email Address"');
echo '</div>';

echo '<div class="col-xs-12">';

echo $form->input_text('mvp','Food Lion MVP Number <span style="margin-left:50px;display:inline-block;"><a href="https://www.foodlion.com/mvp-program/" target="_blank"><span class="fa fa-exclamation-circle"></span> don\'t have a Food Lion MVP Number? click here</a></span>','','mvp','placeholder="Food Lion MVP Number"');
echo '</div>';


echo '<div class="col-xs-12">';
echo $form->input_text('notes','Notes:','','notes','placeholder="Notes"');
echo '</div>';


echo '<div class="col-xs-12">';
echo '<center><button type="submit" class="btn btn-primary btn-lg">Subscribe <span class="fa fa-check"></span></button></center>';
echo '</div>';

echo $form->form_close();

?>
</div>

</div>
</div>


<?php

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

	//Authentication to RetailBusinessServices
//DEVELOPMENT
//	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-IBM-Client-Id: fe18122c-e64a-473e-82fb-6483ea1bc38a'));
//PRODUCTION
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-IBM-Client-Id: 501cb9a5-0c94-4687-9bee-979829038af8'));

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
			break;
    }

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);    

	if (curl_errno($curl)) echo(curl_error($curl));
	
	//echo(print_r(curl_getinfo($curl)));

	curl_close($curl);

    return $result;
}

?>



<?php include 'footer.php' ?>