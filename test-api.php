<?php

include 'header.php';

?>



<div class="panel panel-default">
<div class="panel-heading">
	<h3>Test RetailBusinessServices API</h3>
</div>
<div class="panel-body">

<?php

error_reporting(E_ALL);


function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

	//Authentication to RetailBusinessServices
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-IBM-Client-Id: fe18122c-e64a-473e-82fb-6483ea1bc38a'));
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



//https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/dev/copient-externalofferconnector/v1/addcustomertooffer/externalsourceid/cardnumber/clientofferid?cardnumber=46932926833&clientofferid=12540

echo(CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/dev/copient-externalofferconnector/v1/addcustomertooffer/externalsourceid/cardnumber/clientofferid', array('cardnumber' => '46932926833','clientofferid' => '12540','externalsourceid' => 'AEM_FL')));

//echo(CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/dev/copient-kiosk/v1/lookupbyalternateId/guid/mobilenumber/bannerid', array('bannerid' => '2','mobilenumber' => '7044907203')));

/*

46932926833
46932933872
46932926834

echo(CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/dev/copient-externalofferconnector/v1/AddCustomerToOffer', array('cardnumber' => '046932926833','clientofferid' => '12540')));
echo "<br /><br />";
echo(CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/dev/copient-custweb/v1/offerlist/guid/cardnumber/cardtypeid', array('cardnumber' => '046928041645','cardtypeid' => '0')));
*/

//echo(CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/dev/copient-externalofferconnector/v1/addCustomers', array('cardnumber' => '046928041645','clientofferid' => '9999')));


//DEV needs 9999 offer id instead of production: 
//echo(CallAPI('GET', 'https://api.us.apiconnect.ibmcloud.com/delhaize-america-enterprise-services/dev/copient-externalofferconnector/v1/addCustomers', array('cardnumber' => '469280416450','clientofferid' => '12540')));



?>



</div>
</div>


<?php include 'footer.php' ?>