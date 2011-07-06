<?php

// Include the service wrapper class and the constants file
require_once 'lib/AdaptivePayments.php';
//require_once 'web_constants.php';

// Create request object
$payRequest = new PayRequest();
$payRequest->actionType = "PAY";
$payRequest->cancelUrl = 'http://178.254.1.64/flux/website/account_home.php' ;
$payRequest->returnUrl = 'http://178.254.1.64/flux/website/account_home.php';
$payRequest->clientDetails = new ClientDetailsType();
$payRequest->clientDetails->applicationId ='APP-80W284485P519543T';
$payRequest->clientDetails->deviceId = '127001';
$payRequest->clientDetails->ipAddress = '127.0.0.1';
$payRequest->currencyCode = 'EUR';
$payRequest->senderEmail = 'seller_1309852781_biz@yahoo.com';
$payRequest->requestEnvelope = new RequestEnvelope();
$payRequest->requestEnvelope->errorLanguage = 'en_US';

$payRequest->fundingConstraint = "BALANCE";
			
$receiver1 = new receiver();
$receiver1->email = 'lsnpre_1235312757_per@yahoo.com';
$receiver1->amount = '1000.00';
//paymentType=personal is very important otherwise we pay more fees
//but there's still a 1% for cross-country transactions apparently
//not counting the 2.5% currency conversion fee.
$receiver1->paymentType = "PERSONAL";
	
$payRequest->receiverList = array($receiver1);


// Create service wrapper object
$ap = new AdaptivePayments();

// invoke business method on service wrapper passing in appropriate request params
$response = $ap->Pay($payRequest);

// Check response
if(strtoupper($ap->isSuccess) == 'FAILURE')
{
	$soapFault = $ap->getLastError();
	echo "Transaction Pay Failed: error Id: ";
	if(is_array($soapFault->error)) {
		echo $soapFault->error[0]->errorId . ", error message: " . $soapFault->error[0]->message ;
	} else {
		echo $soapFault->error->errorId . ", error message: " . $soapFault->error->message ;
	}
} else {
	$token = $response->payKey;
	//echo "Transaction Successful! PayKey is $token \n";
	header( 'Location: http://178.254.1.64/flux/website/account_home.php' );
}

?>
